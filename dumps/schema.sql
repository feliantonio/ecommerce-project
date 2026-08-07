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
-- If you already ran an earlier version of this file (without
-- categorie/produttori), use schema_produttori_categorie.sql instead —
-- it adds just those tables + FK constraints to an existing database.
-- Similarly, if you're missing ordini/ordine_dettagli, use schema_ordini.sql.

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
