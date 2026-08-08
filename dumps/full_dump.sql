-- full_dump.sql
-- One-shot dump: full schema + sample catalog data (categorie, produttori,
-- prodotti). Deliberately excludes utenti/carrello/ordini/ordine_dettagli
-- data - those are per-user and are created naturally the first time
-- someone registers and starts shopping.
--
-- Usage:
--   mysql -u root -p < full_dump.sql
-- or import via phpMyAdmin (creates the database itself, no need to
-- select one first).
--
-- This file is just schema.sql followed by seed_data.sql concatenated -
-- see those two files if you want schema and sample data separately, or
-- to see how to regenerate this file after the catalog data changes.

-- schema.sql
-- Full schema for the "catalogo23_5cat" e-commerce database, for a fresh
-- install. No original .sql dump was found in the project; this was
-- rebuilt by reading every SQL query in dataDb/*.php and cross-referencing
-- column names/types against the model classes (models/utente.php,
-- prodotto.php, carrello.php, produttore.php, categoria.php).
--
-- Usage:
--   mysql -u root -p < schema.sql
-- or import via phpMyAdmin (create the database first, or let the
-- CREATE DATABASE statement below do it).
--
-- Schema only, no data — see seed_data.sql for sample categorie/produttori/
-- prodotti, or full_dump.sql to get both in one file/command.

CREATE DATABASE IF NOT EXISTS catalogo23_5cat
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE catalogo23_5cat;

-- ---------------------------------------------------------------------
-- utenti — registered users (dataDb/dbUtente.php, models/utente.php)
-- TipoUtente: 'U' = registered user, 'A' = admin, 'G' = guest (session-only,
-- never persisted to this table).
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS utenti (
    UtenteId    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Nome        VARCHAR(100)  NOT NULL,
    Cognome     VARCHAR(100)  NOT NULL DEFAULT '',
    Mail        VARCHAR(255)  NOT NULL,
    Telefono    VARCHAR(30)   NOT NULL DEFAULT '',
    TipoUtente  CHAR(1)       NOT NULL DEFAULT 'U',
    Password    VARCHAR(255)  NOT NULL,      -- holds password_hash() output
    Indirizzo   VARCHAR(255)  NOT NULL DEFAULT '',
    Provincia   VARCHAR(100)  NOT NULL DEFAULT '',
    Cap         VARCHAR(10)   NOT NULL DEFAULT '',
    PRIMARY KEY (UtenteId),
    UNIQUE KEY uq_utenti_mail (Mail)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- categorie — product categories (dataDb/dbCategoria.php, models/categoria.php)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categorie (
    CategoriaId  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Nome         VARCHAR(100) NOT NULL,
    Descrizione  VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (CategoriaId),
    UNIQUE KEY uq_categorie_nome (Nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- produttori — product manufacturers (dataDb/dbProduttore.php, models/produttore.php)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS produttori (
    ProduttoreId    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Nome            VARCHAR(100) NOT NULL,
    NazioneOrigine  VARCHAR(100) NOT NULL DEFAULT '',
    PRIMARY KEY (ProduttoreId),
    UNIQUE KEY uq_produttori_nome (Nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- prodotti — product catalog (dataDb/dbProdotto.php, dbCatalogo.php,
-- models/prodotto.php)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS prodotti (
    ProdottoID    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Prodotto      VARCHAR(255)   NOT NULL,
    Descrizione   TEXT           NOT NULL,
    Um            VARCHAR(20)    NOT NULL DEFAULT '',   -- unit of measure
    Prezzo        DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    ProduttoreId  INT UNSIGNED   NOT NULL,
    CategoriaId   INT UNSIGNED   NOT NULL,
    NomeImmagine  VARCHAR(255)   NOT NULL DEFAULT '',   -- filename in /images, no extension
    Attivo        TINYINT(1)     NOT NULL DEFAULT 1,
    PRIMARY KEY (ProdottoID),
    KEY idx_prodotti_prodotto (Prodotto),
    CONSTRAINT fk_prodotti_categoria  FOREIGN KEY (CategoriaId)  REFERENCES categorie (CategoriaId),
    CONSTRAINT fk_prodotti_produttore FOREIGN KEY (ProduttoreId) REFERENCES produttori (ProduttoreId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- carrello — shopping cart line items (dataDb/dbCarrello.php,
-- models/carrello.php). One row per (UtenteId, ArticoloId) pair; quantity
-- is incremented in place when a product already in the cart is re-added.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS carrello (
    CarrelloId  INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    UtenteId    INT UNSIGNED   NOT NULL,
    ArticoloId  INT UNSIGNED   NOT NULL,
    Qta         INT UNSIGNED   NOT NULL DEFAULT 1,
    Prezzo      DECIMAL(10,2)  NOT NULL DEFAULT 0.00,   -- unit price snapshotted at add-to-cart time
    PRIMARY KEY (CarrelloId),
    UNIQUE KEY uq_carrello_utente_articolo (UtenteId, ArticoloId),
    CONSTRAINT fk_carrello_utente   FOREIGN KEY (UtenteId)   REFERENCES utenti (UtenteId)     ON DELETE CASCADE,
    CONSTRAINT fk_carrello_articolo FOREIGN KEY (ArticoloId) REFERENCES prodotti (ProdottoID)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- ordini / ordine_dettagli — order history (dataDb/dbOrdine.php,
-- models/ordine.php, models/ordineDettaglio.php). Created when the user
-- confirms the (mocked) payment on the cart page; snapshots quantity and
-- unit price at purchase time, independent of later changes to prodotti.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS ordini (
    OrdineId    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    UtenteId    INT UNSIGNED NOT NULL,
    DataOrdine  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Imponibile  DECIMAL(10,2) NOT NULL,
    Iva         DECIMAL(10,2) NOT NULL,
    Totale      DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (OrdineId),
    CONSTRAINT fk_ordini_utente FOREIGN KEY (UtenteId) REFERENCES utenti (UtenteId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ordine_dettagli (
    OrdineDettaglioId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    OrdineId          INT UNSIGNED NOT NULL,
    ProdottoId        INT UNSIGNED NOT NULL,
    Qta               INT UNSIGNED NOT NULL,
    PrezzoUnitario    DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (OrdineDettaglioId),
    CONSTRAINT fk_od_ordine   FOREIGN KEY (OrdineId)   REFERENCES ordini (OrdineId)     ON DELETE CASCADE,
    CONSTRAINT fk_od_prodotto FOREIGN KEY (ProdottoId) REFERENCES prodotti (ProdottoID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- seed_data.sql
-- Sample catalog data: categorie, produttori, prodotti (in that order —
-- prodotti has FK constraints on both). Deliberately excludes utenti,
-- carrello, ordini, ordine_dettagli: those are per-user data, created
-- naturally the first time someone registers/shops, not shipped as fixtures.
--
-- Run against a database that already has the tables (see schema.sql), or
-- just use full_dump.sql to get schema + this data in one shot:
--   mysql -u root catalogo23_5cat < seed_data.sql
--
-- CategoriaId reference (insertion order below):
--   1  Notebook           6  Monitor            11 Stampante Laser
--   2  PC Desktop         7  Tastiera           12 Multifunzione Inkjet
--   3  All in One         8  Mouse              13 Multifunzione Laser
--   4  Chromebook         9  Kit Tastiera e Mouse
--   5  Tablet            10  Stampante Inkjet
-- ProduttoreId reference (insertion order below):
--   1 Lenovo   4 HP         7 Samsung    10 Epson    13 MSI
--   2 Asus     5 Acer       8 Logitech   11 Canon
--   3 Dell     6 Apple      9 Microsoft  12 Brother
--
-- NomeImmagine matches each filename WITHOUT the .jpg extension, since
-- dataDb/dbCatalogo.php renders <img src="../images/$NomeImmagine.jpg">.
-- logoShopping.png is the site logo, not a product, so it's excluded.

USE catalogo23_5cat;

INSERT INTO categorie (Nome, Descrizione) VALUES
('Notebook',                  'Computer portatili per uso quotidiano, lavoro e studio.'),
('PC Desktop',                'Computer fissi da scrivania, per casa, ufficio o gaming.'),
('All in One',                'Computer con schermo e componenti integrati in un unico corpo.'),
('Chromebook',                'Notebook leggeri basati su ChromeOS, pensati per il web e lo studio.'),
('Tablet',                    'Dispositivi touch portatili per navigazione, intrattenimento e produttività.'),
('Monitor',                   'Schermi esterni per PC e notebook, da ufficio a gaming.'),
('Tastiera',                  'Tastiere cablate e wireless, standard e meccaniche.'),
('Mouse',                     'Mouse cablati e wireless, per uso quotidiano e gaming.'),
('Kit Tastiera e Mouse',      'Set combinati di tastiera e mouse venduti insieme.'),
('Stampante Inkjet',          'Stampanti a getto d''inchiostro per la casa e il piccolo ufficio.'),
('Stampante Laser',           'Stampanti laser per stampe ad alto volume e alta velocità.'),
('Multifunzione Inkjet',      'Stampanti inkjet con funzioni di scansione e copia integrate.'),
('Multifunzione Laser',       'Stampanti laser con funzioni di scansione e copia integrate.');

INSERT INTO produttori (Nome, NazioneOrigine) VALUES
('Lenovo',    'Cina'),
('Asus',      'Taiwan'),
('Dell',      'Stati Uniti'),
('HP',        'Stati Uniti'),
('Acer',      'Taiwan'),
('Apple',     'Stati Uniti'),
('Samsung',   'Corea del Sud'),
('Logitech',  'Svizzera'),
('Microsoft', 'Stati Uniti'),
('Epson',     'Giappone'),
('Canon',     'Giappone'),
('Brother',   'Giappone'),
('MSI',       'Taiwan');

INSERT INTO prodotti (Prodotto, Descrizione, Um, Prezzo, ProduttoreId, CategoriaId, NomeImmagine, Attivo) VALUES
('Notebook Ultrabook 14"',        'Notebook leggero e sottile, ideale per l''uso quotidiano.',            'pz', 699.99,  1, 1, 'NOTEBOOK_1', 1),
('Notebook Gaming 15.6"',         'Notebook da gaming con scheda video dedicata.',                        'pz', 999.99,  2, 1, 'NOTEBOOK_2', 1),
('Notebook Business 13"',         'Notebook compatto orientato alla produttività in ufficio.',       'pz', 849.99,  3, 1, 'NOTEBOOK_3', 1),
('Notebook Economico 14"',        'Notebook entry-level per navigazione e uso base.',                     'pz', 449.99,  5, 1, 'NOTEBOOK_4', 1),
('Notebook Convertibile 2-in-1',  'Notebook con schermo touch convertibile in tablet.',                   'pz', 799.99,  4, 1, 'NOTEBOOK_5', 1),

('PC Desktop Office',             'PC desktop compatto pensato per l''uso in ufficio.',                   'pz', 499.99,  4, 2, 'PC_DESKTOP_1', 1),
('PC Desktop Gaming',             'PC desktop con componenti dedicati al gaming.',                        'pz', 1299.99, 13, 2, 'PC_DESKTOP_2', 1),
('PC Desktop Mini Tower',         'PC desktop compatto formato mini tower.',                              'pz', 599.99,  3, 2, 'PC_DESKTOP_3', 1),
('PC Desktop Workstation',        'PC desktop ad alte prestazioni per uso professionale.',                'pz', 1599.99, 1, 2, 'PC_DESKTOP_4', 1),

('PC All in One 24"',             'Computer All in One con schermo integrato da 24 pollici.',             'pz', 899.99,  4, 3, 'ALL_IN_ONE_1', 1),
('PC All in One 27" 4K',          'Computer All in One con schermo 4K da 27 pollici.',                    'pz', 1199.99, 6, 3, 'ALL_IN_ONE_2', 1),

('Chromebook 11.6"',              'Chromebook leggero con ChromeOS, ideale per lo studio.',               'pz', 299.99,  5, 4, 'CHROMEBOOK_1', 1),
('Chromebook 14"',                'Chromebook con schermo più ampio e batteria a lunga durata.',    'pz', 349.99,  2, 4, 'CHROMEBOOK_2', 1),

('Tablet 10" Wi-Fi',              'Tablet con connettività Wi-Fi e schermo da 10 pollici.',          'pz', 249.99,  7, 5, 'TABLET_1', 1),
('Tablet 10" LTE',                'Tablet con connettività LTE per la navigazione ovunque.',         'pz', 329.99,  7, 5, 'TABLET_2', 1),
('Tablet 8" Compatto',            'Tablet compatto e leggero, facile da trasportare.',                    'pz', 199.99,  1, 5, 'TABLET_3', 1),
('Tablet 11" Pro',                'Tablet professionale con supporto per pennino.',                      'pz', 399.99,  6, 5, 'TABLET_4', 1),
('Tablet 10" Kids',               'Tablet pensato per i più piccoli, con custodia protettiva.',      'pz', 279.99,  7, 5, 'TABLET_5', 1),
('Tablet 12" Ultra',              'Tablet di fascia alta con schermo grande ad alta risoluzione.',       'pz', 459.99,  6, 5, 'TABLET_6', 1),

('Monitor 24" Full HD',           'Monitor da 24 pollici con risoluzione Full HD.',                       'pz', 129.99,  4, 6, 'MONITOR_1', 1),
('Monitor 27" QHD',               'Monitor da 27 pollici con risoluzione QHD.',                           'pz', 219.99,  3, 6, 'MONITOR_2', 1),
('Monitor 24" Gaming 144Hz',      'Monitor da gaming con frequenza di aggiornamento 144Hz.',              'pz', 189.99,  2, 6, 'MONITOR_3', 1),
('Monitor 32" 4K',                'Monitor da 32 pollici con risoluzione 4K.',                            'pz', 349.99,  7, 6, 'MONITOR_4', 1),
('Monitor Curvo 27"',             'Monitor curvo da 27 pollici per un''esperienza immersiva.',            'pz', 279.99,  7, 6, 'MONITOR_5', 1),

('Tastiera Meccanica RGB',        'Tastiera meccanica con retroilluminazione RGB.',                       'pz', 59.99,   8, 7, 'TASTIERA_1', 1),
('Tastiera Wireless Compatta',    'Tastiera wireless compatta senza tastierino numerico.',                'pz', 39.99,   9, 7, 'TASTIERA_2', 1),

('Mouse Wireless Ergonomico',     'Mouse wireless con design ergonomico.',                                'pz', 19.99,   8, 8, 'MOUSE_1', 1),
('Mouse Gaming RGB',              'Mouse da gaming con illuminazione RGB e sensore di precisione.',       'pz', 34.99,   2, 8, 'MOUSE_2', 1),

('Kit Tastiera e Mouse Wireless', 'Kit combinato tastiera e mouse wireless.',                             'pz', 44.99,   8, 9, 'MOUSE_TASTIERA_1', 1),
('Kit Tastiera e Mouse USB',      'Kit combinato tastiera e mouse con cavo USB.',                         'pz', 29.99,   9, 9, 'MOUSE_TASTIERA_2', 1),

('Stampante Inkjet A4',           'Stampante a getto d''inchiostro formato A4.',                          'pz', 69.99,   10, 10, 'STAMPANTE_INKJET_1', 1),
('Stampante Inkjet Fotografica',  'Stampante inkjet ottimizzata per la stampa fotografica.',              'pz', 89.99,   11, 10, 'STAMPANTE_INKJET_2', 1),
('Stampante Inkjet Compatta',     'Stampante inkjet compatta per la casa.',                               'pz', 59.99,   10, 10, 'STAMPANTE_INKJET_3', 1),

('Stampante Laser B/N',           'Stampante laser monocromatica ad alta velocità.',                 'pz', 119.99,  12, 11, 'STAMPANTE_LASER_1', 1),
('Stampante Laser Colori',        'Stampante laser a colori per l''ufficio.',                             'pz', 199.99,  4, 11, 'STAMPANTE_LASER_2', 1),
('Stampante Laser Compatta',      'Stampante laser compatta per uso domestico.',                          'pz', 99.99,   12, 11, 'STAMPANTE_LASER_3', 1),

('Multifunzione Inkjet Base',     'Stampante multifunzione inkjet: stampa, scansione, copia.',            'pz', 79.99,   10, 12, 'MULTIFUNZIONE_INKJET_1', 1),
('Multifunzione Inkjet Wi-Fi',    'Multifunzione inkjet con connettività Wi-Fi.',                    'pz', 99.99,   11, 12, 'MULTIFUNZIONE_INKJET_2', 1),
('Multifunzione Inkjet Fronte/Retro', 'Multifunzione inkjet con stampa fronte/retro automatica.',        'pz', 109.99,  10, 12, 'MULTIFUNZIONE_INKJET_3', 1),
('Multifunzione Inkjet All-in-One', 'Multifunzione inkjet completa per casa e ufficio.',                  'pz', 129.99,  4, 12, 'MULTIFUNZIONE_INKJET_4', 1),

('Multifunzione Laser B/N',       'Multifunzione laser monocromatica per l''ufficio.',                    'pz', 189.99,  12, 13, 'MULTIFUNZIONE_LASER_1', 1),
('Multifunzione Laser Colori',    'Multifunzione laser a colori ad alte prestazioni.',                    'pz', 279.99,  4, 13, 'MULTIFUNZIONE_LASER_2', 1),
('Multifunzione Laser Wi-Fi',     'Multifunzione laser con connettività Wi-Fi integrata.',           'pz', 229.99,  11, 13, 'MULTIFUNZIONE_LASER_3', 1);
