# E-Commerce Project

A small PHP/MySQL e-commerce demo built as a course project (AFP Cuneo, 2022–2023), restored and cleaned up to run on modern PHP for portfolio purposes.

## Features

- Product catalog with pagination, text search, sorting (by ID, name, or price), and multi-select checkbox filtering by category/producer
- User registration and login with hashed passwords (`password_hash`/`password_verify`)
- Personal area: view/edit account info, change password, order history
- Shopping cart: add products, update quantities, remove selected/all, checkout (mocked payment) into order history
- Admin panel (`.admin/`, `TipoUtente = 'A'`): create/edit products, categories, producers; deactivate products (soft delete via `Attivo`); view every user's orders
- Simple role model: guest (`G`), registered user (`U`), admin (`A`)

## Tech stack

- **PHP 8.2** — no framework; a small hand-rolled MVC-ish structure (`classiMaster/` controllers, `models/` entities, `dataDb/` repositories, `templates/` + `include/` views)
- **PDO** with prepared statements for all database access (`dataDb/dbManager.php`) — no raw string-concatenated queries in the data layer
- **MySQL / MariaDB**
- **Bootstrap 5** (via CDN) for styling
- Native PHP sessions for auth/state (no JWT/OAuth — this is a course project, not a production auth system)

## Project structure

```
common.php               Bootstraps sessions, config, and core includes
config.ini                DB credentials (gitignored — see Setup below)
css/                      Shared stylesheet (site.css), mirrors images/ as a plain static-asset dir
dumps/                    SQL dumps to (re)create the database — see Setup below
classiMaster/             Page "controllers" (MasterHome, MasterCarrello, MasterAdmin, ...)
dataDb/                   Repository classes — all DB access goes through DbManager (PDO)
models/                   Plain entity classes (Utente, Prodotto, Carrello, Ordine, ...)
templates/, include/      View fragments
index/                    Entry points (index.php is the catalog home page)
.login/, .ur/, .carrello/, .dettaglio/, .areaRiservata/, .admin/  Feature entry points
images/                   Product images
```

## Setup (local, XAMPP)

1. Install [XAMPP](https://www.apachefriends.org/) (bundles Apache, MySQL/MariaDB, PHP, and phpMyAdmin — no separate installs needed).
2. Copy the project folder into `C:\xampp\htdocs\` (e.g. `C:\xampp\htdocs\ecommerce-project`).
3. Open the **XAMPP Control Panel** and click **Start** next to both **Apache** and **MySQL**. Both must show a green "Running" status.
4. Copy `config.ini.example` to `config.ini` and adjust credentials if needed (defaults match a stock XAMPP install: user `root`, empty password, host `localhost`).
5. Create the database and load the sample catalog data — pick one:
   - **phpMyAdmin** (GUI): open `http://localhost/phpmyadmin`, click **Import**, choose `dumps/full_dump.sql`, click **Go**. This one file creates the `catalogo23_5cat` database, every table, and seeds `categorie`/`produttori`/`prodotti` with sample data — everything needed to browse the catalog.
   - **Command line**: from the project root,
     ```
     mysql -u root -p < dumps/full_dump.sql
     ```
     (omit `-p` if your MySQL root password is empty, the XAMPP default).
6. Visit `http://localhost/ecommerce-project/index/index.php` in your browser. The catalog should show ~40 seeded products.
7. Register an account (top-right "Registrati") to try the cart/checkout/account flows. To reach the admin panel, promote that account manually (no promotion UI by design):
   ```sql
   UPDATE utenti SET TipoUtente='A' WHERE Mail='your-registered-email@example.com';
   ```
   then log out and back in (role is loaded into the session at login), and look for the gear icon in the top navbar, or go directly to `.admin/index.php`.

### The `dumps/` folder

- **`dumps/full_dump.sql`** — the one-shot file described above: full schema + sample `categorie`/`produttori`/`prodotti` data. Deliberately excludes `utenti`/`carrello`/`ordini`/`ordine_dettagli` data — those are per-user and get created naturally the first time someone registers and shops, not shipped as fixtures.
- **`dumps/schema.sql`** — just the `CREATE TABLE` statements, no data, if you want an empty catalog.
- **`dumps/seed_data.sql`** — just the `INSERT` statements (categorie/produttori/prodotti), if you already have the schema and only need the sample data.

**Regenerating `seed_data.sql`/`full_dump.sql` after changing the catalog** (e.g. after adding/editing products through the admin panel and wanting to snapshot the new state for the repo):
```
mysqldump -u root --no-create-info --skip-comments catalogo23_5cat categorie produttori prodotti > dumps\seed_data_new.sql
```
This dumps only `INSERT` statements (`--no-create-info`) for those three tables, in FK-safe order. Prepend a short header comment (see the existing file for the format), replace `dumps/seed_data.sql` with it, then rebuild `dumps/full_dump.sql` by concatenating `dumps/schema.sql` and the new `dumps/seed_data.sql`:
```
type dumps\schema.sql dumps\seed_data.sql > dumps\full_dump.sql
```
(On the Bash/Git Bash shell this session used: `cat dumps/schema.sql dumps/seed_data.sql > dumps/full_dump.sql`.)

## Known limitations / honest notes

This was a learning project — a few things are intentionally left as-is rather than polished:

- `index/catalogoProdotti.php` is a legacy/unused prototype page (not linked from anywhere in the app) with an older, less safe approach to the catalog query. It's kept for reference but should not be considered part of the working app.
- Sorting/search on the live catalog page validates the sort column/direction against a fixed whitelist before building the query (PDO can't parameterize identifiers), but the overall input-validation coverage is course-project-level, not production-hardened.
- No automated tests.
- No `.env`/dependency manager — the project intentionally has zero third-party PHP dependencies, so there's no Composer setup.

## License

Personal/educational project — no license specified. Feel free to look around, but ask before reusing substantial parts commercially.
