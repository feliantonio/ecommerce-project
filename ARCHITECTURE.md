# Architecture

How the codebase is structured, the patterns behind it, and how a request actually flows through it. Written for future-me (or anyone reading the code) — not a framework, just a consistent hand-rolled convention followed throughout the project.

## The layers

```
common.php                    → bootstrap: session, config, core requires
  ├── classiMaster/            → "page controllers" (one per page family)
  ├── models/                  → plain data objects (no DB knowledge)
  ├── dataDb/                  → data access (the only layer that talks SQL)
  ├── templates/ + include/    → view fragments (pure HTML/PHP output)
  └── entry points (index.php, dettaglio.php, carrello.php, ...)
```

## The DB management stack (4 layers, not 1)

This is the most deliberate piece of the design — it's built like it expected to support multiple database engines someday, even though only MySQL is actually implemented.

```
IDbManager  (interface)
    ↑ implements
DbManager   (concrete PDO/MySQL driver)
    ↑ wrapped by
DbRepository (delegates to whichever driver matches config)
    ↑ extended by
DbCatalogo / DbProdotto / DbCategoria / DbProduttore / DbCarrello / DbUtente
    (entity-specific query methods)
```

**`IDbManager`** (`dataDb/IDbManager.php`) just declares the contract: `OpenConnection`, `CloseConnection`, `IsConnected`, `Select`, `Insert`, `Update`, `Delete`. Nothing else in the codebase is allowed to touch a `PDO` object directly except the class that implements this.

**`DbManager`** (`dataDb/dbManager.php`) is the only class that actually knows about PDO. Every method follows the same shape:
1. `OpenConnection()` — build the DSN from `$_SESSION["DbType"/"DbHost"/"DbName"]` (set once at login from `config.ini` via `Common::ReadFileConfig()`), `new PDO(...)`.
2. Check `IsConnected()`.
3. For writes (`Insert`/`Update`/`Delete`): `beginTransaction()` → `exec()` or `prepare()+execute($params)` → `commit()`, with `rollback()` in the catch.
4. For `Select`: `query()` (no params) or `prepare()+execute($params)` (with params), then `fetchAll(PDO::FETCH_ASSOC)`.
5. `finally { CloseConnection(); }` — every single call opens a fresh connection and closes it afterward. No persistent connection, no pooling — simple but means every query pays connection overhead.

**`DbRepository`** (`dataDb/dbRepository.php`) is a thin pass-through: its constructor picks a driver based on `$_SESSION["DbType"]` (`"mysql"` → `new DbManager()`; Postgres/SqlServer branches exist but are commented-out stubs), and every `IDbManager` method just forwards to `$this->db->TheSameMethod(...)`. This is the seam where you'd plug in `DbManagerPostgre` etc. without touching any entity repo.

**Entity repos** (`DbCatalogo`, `DbProdotto`, `DbCategoria`, `DbProduttore`, `DbCarrello`, `DbUtente`) each `extends DbRepository` and add the actual business queries — `GetProdById()`, `SelectCatalogo()`, `AggiungiProd()`, etc. They never build a `PDO` object themselves; they just write SQL strings with `:named` placeholders, put values into a plain assoc array (`$param['id'] = $id`), and call `parent::Select($sql, $param)` / `parent::Insert(...)`. This is where SQL injection would sneak in if someone forgot the params array and concatenated instead — which is exactly what happened with `ORDER BY $sortField` in `DbCatalogo`, and why that needed a whitelist fix.

Row → object mapping is always manual, never automatic: `Select()` returns `array` of assoc arrays (`PDO::FETCH_ASSOC`), and each repo method does `new Prodotto(); $p->SetX($row['X']); ...` by hand, column by column. No ORM, no reflection, no magic — which makes bugs like the `Um`/`UnitaMisura` or `ArticoloID`/`ArticoloId` mismatches easy to introduce (a typo in a string key just silently produces `null`/warnings, PHP won't catch it at "compile" time) and easy to fix once spotted, but nothing enforces the mapping stays correct.

Error handling is uniform everywhere: `try { ... } catch (Exception $e) { die("...[MethodName]: " . $e->getMessage()); }`. Every repo method tags its own name in the death message. There's no recoverable-error path anywhere in the data layer — any DB failure kills the whole request. Fine for a course project, not what you'd want in production.

## Model classes (`models/*.php`)

Pure data holders, zero DB awareness. The convention, followed exactly the same way in every model (`Prodotto`, `Utente`, `Carrello`, `Categoria`, `Produttore`):

- Every property is `private`, typed, with a **sentinel default** instead of `null` — `-1` for ints/floats meaning "not set", `''` for strings. This is why you'll never see a nullable property; "unset" is represented by a value, not by `null`.
- One `Get`/`Set` pair per property, PascalCase method names (`GetProdottoId()`/`SetProdottoId(int $value)`).
- A single `static` factory-ish helper (`Prodotto::SetProd(...)`, `Categoria::SetCategoria(...)`, `Produttore::SetProduttore(...)`) that takes an already-constructed object plus nullable scalars, and fills it in — falling back to the sentinel default whenever a value is `null`, via a ternary chain (`$x != null ? $obj->SetX($x) : $obj->SetX(default)`). In practice nothing in the repos actually calls this factory (they build objects by hand instead), so it's more of an established convention than something wired end-to-end — but it's consistently present on every model.

## The page-controller / template pattern (`classiMaster/`, `templates/`, `include/`)

`MasterBase` is a slot container — it just holds strings for `header`, `footer`, `asideLeft`, `asideRight`, `nav`, `contenuto`, and `template`, each with a `Get`/`Set` pair defaulting to `""`. It doesn't render anything itself.

Concrete subclasses (`MasterHome`, `MasterDettaglio`, `MasterCarrello`, `MasterPersonale`) preconfigure which `include/` fragments go into which slots — e.g. `MasterHome` wires up `header.php` + `asideLeft.php` + `asideRight.php` + `nav.php` + `footer.php`; `MasterDettaglio` only wires `headerP.php` (a lighter header) and leaves the rest empty, since a product detail page doesn't need the full catalog sidebar chrome.

An **entry-point script** (`index/index.php`, `.dettaglio/dettaglio.php`, `.carrello/carrello.php`, ...) is the actual controller for one page:
1. `require_once common.php` — this alone starts the session, loads `config.ini` into `$_SESSION`, and pulls in `DbManager`/`DbRepository`/`MasterBase`.
2. Instantiate the right `Master*` subclass.
3. `SetContenuto("someView_inc.php")` — picks which view fragment goes in the main content slot.
4. Do any request handling (POST processing — add to cart, login, etc.) using the entity repos/models.
5. `require_once($mst->GetTemplate())` — hands off to a `templates/templateX.php` file, which is the actual layout: it echoes `$mst->GetHeader()` (as a path, then `require`s it), then aside/nav, then `$mst->GetContenuto()`, then footer, stitching the slots together into the final HTML page.

So the chain for a real request looks like this, e.g. for the catalog page:

```
index/index.php                 (controller: builds MasterHome, handles POST add-to-cart)
  → templates/templateHome.php  (layout: assembles header+aside+nav+CONTENT+footer)
      → index/catalogoP.php     (content: calls the repo, loops results into HTML)
          → DbCatalogo::SelectCatalogo()
              → DbRepository::Select()
                  → DbManager::Select()  → PDO
```

and for the product detail page, the same shape, just a lighter master and a different content fragment:

```
.dettaglio/dettaglio.php        (controller: validates ?id=, handles POST add-to-cart)
  → DbProdotto / DbCategoria / DbProduttore   (three separate repo calls, no JOIN)
  → templates/templateHome.php
      → .dettaglio/dettaglio_inc.php   (content: renders $p/$categoria/$produttore)
```

Note that fetching a product's category and producer is **three separate round-trips** (`GetProdById`, `GetCategoriaById`, `GetProduttoreById`), not a SQL `JOIN` — consistent with the rest of the codebase, where every repo method maps exactly one table per query. It's simple and easy to follow, at the cost of N+1-style query counts on anything relational.

## Where the boundaries are respected (and where they leak)

- Entry points never touch PDO directly — good, the layering holds.
- View fragments (`*_inc.php`, `templates/*`) generally just read from already-built model objects/arrays — with the one now-fixed exception being `dettaglio_inc.php`, which used to ignore its model entirely and hard-code markup.
- `include/header.php` is the one file that blurs controller/view — it reads `$_GET` directly (sort/order/search) inside what's nominally a view fragment, which is why the SQL-injection/XSS fixes had to reach into it rather than staying purely in the data layer.
- `index/catalogoProdotti.php` (the dead file) breaks every convention at once — inline PDO connection, raw string-built SQL, no model/repo — which is a good marker that it predates (or was abandoned in favor of) this pattern rather than being part of it.
