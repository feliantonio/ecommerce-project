# E-Commerce Project

A small PHP/MySQL e-commerce demo built as a course project (AFP Cuneo, 2022–2023), restored and cleaned up to run on modern PHP for portfolio purposes.

## Features

- Product catalog with pagination, text search, and sorting (by ID, name, or price)
- User registration and login with hashed passwords (`password_hash`/`password_verify`)
- Personal area: view/edit account info, change password
- Shopping cart: add products, update quantities, view cart total
- Simple role model: guest (`G`), registered user (`U`), admin (`A`)

## Tech stack

- **PHP 8.2** — no framework; a small hand-rolled MVC-ish structure (`classiMaster/` controllers, `models/` entities, `dataDb/` repositories, `templates/` + `include/` views)
- **PDO** with prepared statements for all database access (`dataDb/dbManager.php`) — no raw string-concatenated queries in the data layer
- **MySQL / MariaDB**
- **Bootstrap 5** (via CDN) for styling
- Native PHP sessions for auth/state (no JWT/OAuth — this is a course project, not a production auth system)

## Project structure

```
common.php              Bootstraps sessions, config, and core includes
config.ini               DB credentials (gitignored — see Setup below)
schema.sql                Reconstructed DB schema (no original dump existed)
classiMaster/            Page "controllers" (MasterHome, MasterCarrello, ...)
dataDb/                  Repository classes — all DB access goes through DbManager (PDO)
models/                  Plain entity classes (Utente, Prodotto, Carrello)
templates/, include/     View fragments
index/                   Entry points (index.php is the catalog home page)
.login/, .ur/, .carrello/, .dettaglio/, .areaRiservata/  Feature entry points
images/                  Product images
```

## Setup (local, XAMPP)

1. Copy the project folder into `C:\xampp\htdocs\` (e.g. `C:\xampp\htdocs\ecommerce-project`).
2. Start Apache and MySQL from the XAMPP control panel.
3. Copy `config.ini.example` to `config.ini` and adjust credentials if needed (defaults match a stock XAMPP install: user `root`, empty password, host `localhost`).
4. Create the database and tables:
   ```
   mysql -u root -p < schema.sql
   ```
   or import `schema.sql` via phpMyAdmin.
5. Visit `http://localhost/ecommerce-project/index/index.php` in your browser.

## Known limitations / honest notes

This was a learning project — a few things are intentionally left as-is rather than polished:

- `index/catalogoProdotti.php` is a legacy/unused prototype page (not linked from anywhere in the app) with an older, less safe approach to the catalog query. It's kept for reference but should not be considered part of the working app.
- Sorting/search on the live catalog page validates the sort column/direction against a fixed whitelist before building the query (PDO can't parameterize identifiers), but the overall input-validation coverage is course-project-level, not production-hardened.
- No automated tests.
- No `.env`/dependency manager — the project intentionally has zero third-party PHP dependencies, so there's no Composer setup.

## License

Personal/educational project — no license specified. Feel free to look around, but ask before reusing substantial parts commercially.
