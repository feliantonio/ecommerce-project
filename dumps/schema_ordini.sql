-- schema_ordini.sql
-- Adds order-history tables (ordini/ordine_dettagli) to a database that
-- already exists (from schema.sql + schema_produttori_categorie.sql).
--
-- Usage:
--   mysql -u root catalogo23_5cat < schema_ordini.sql
-- or import via phpMyAdmin with catalogo23_5cat selected.
--
-- (schema.sql itself has also been updated with these same CREATE TABLE
-- blocks, for anyone doing a fresh install from scratch.)

USE catalogo23_5cat;

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
