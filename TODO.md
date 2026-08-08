# Next steps

Backlog for the next working session. Cart overhaul (footer, cart summary/pay, delete-all/selected, order history, cart badge, category/producer filtering) is done and confirmed working — see `ARCHITECTURE.md` for how the codebase fits together.

## 1. UX pass — DONE
General polish, not a specific bug:
- Center/align elements that currently sit off — check catalog cards, cart cards, the new aside-left/aside-right panels on the cart page, forms in the account area.
- Look for repeated/redundant elements across pages (e.g. things echoed twice, duplicate spacing, inconsistent card styles between catalog/cart/order-history).
- General visual "glow up": consistent spacing/margins, button styles, maybe a bit more visual hierarchy (headings, card shadows) — currently pure default Bootstrap 5 with minimal customization.

Shipped: new shared `css/site.css`, deduped dead/duplicated CSS, consistent `.page-title-bar` heading across account/cart pages, fixed catalog grid gutters + nested-anchor bug, cart aside panels unified, dead `nav.php`/`asideRight.php` removed, `headerP.php` back-button centering fix, plus a couple of bugs found along the way (modPassword double-alert, missing `else`).

## 2. Admin panel — DONE
`TipoUtente = 'A'` (admin) already exists as a value in the `utenti` table/model but has **no UI anywhere** — there's no way to actually reach or use an admin role today. Needs:
- A way to designate/log in as an admin (currently `Register()` in `dataDb/dbUtente.php` hardcodes new users to `'U'` — need a path to create/promote an admin, even if just a manual DB update for now).
- Admin-only pages to manage `prodotti`, `categorie`, `produttori` (create/edit/deactivate — `Attivo` flag already exists on `prodotti`), and probably to view all `ordini` (not just your own).
- Route/page protection based on `Common::GetUserType() == "A"`, following the same guard pattern already used for guest-vs-user (`Common::GetUserType() != "G"`).

Shipped: `Common::RequireAdmin()` guards every `.admin/*.php` controller and renders a styled "Accesso negato" page (`include/accessoNegato.php`) for non-admins; new `.admin/` page family (`prodotti`, `categorie`, `produttori`, `ordini`) mirroring the `.areaRiservata/` pattern via a new `masterAdmin`/`AsideLeftAdmin`; prodotti support create/edit/deactivate (`Attivo` toggle, now actually filtered out of the public catalog — that filter didn't exist before), categorie/produttori support create/edit only (no delete, avoids FK issues); `ordini.php` shows every user's orders with buyer name/mail via a new `GetAllOrdini()`/`GetUtenteById()`. Every new admin-only repo method also checks `Common::GetUserType() === "A"` internally (not just the controller), as defense-in-depth beyond this codebase's usual controller-only-guard convention. Admin promotion stays a manual `UPDATE utenti SET TipoUtente='A'` — no promotion UI, as decided.

## 3. Filtering with checkboxes — DONE
Current category/producer filtering (`include/asideLeft.php`, added this round) is single-select (one category + one producer at a time, via plain links). Requested: multi-select checkboxes instead (e.g. filter by *several* categories or producers at once).
- Needs `DbCatalogo::PageParams()`/`SelectCatalogo()` to accept arrays of IDs instead of single `?int`, and build `IN (...)` clauses (same dynamic-placeholder pattern already used in `DbCarrello::DeleteSelected()`).
- Needs a `<form method="get">` in `asideLeft.php` instead of plain links (checkboxes need to submit multiple selected values together), while still preserving search/sort/page — reuse/extend `Common::BuildQuery()`.

Shipped: `PageParams()`/`SelectCatalogo()` take `?array` IDs and build `IN (...)` clauses; `asideLeft.php` is now a checkbox form with hidden search/sort/order/page fields; `header.php`'s search/sort form also carries hidden `categoria[]`/`produttore[]` fields so it no longer clobbers active filters (a related bug fixed alongside).
