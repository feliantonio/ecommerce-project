-- schema_produttori_categorie.sql
-- Adds the missing produttori/categorie lookup tables + FK constraints on
-- the already-created "prodotti" table. Run this once against the
-- catalogo23_5cat database that schema.sql already created.
--
-- Usage:
--   mysql -u root catalogo23_5cat < schema_produttori_categorie.sql
-- or import via phpMyAdmin with catalogo23_5cat selected.
--
-- (schema.sql itself has also been updated with these same CREATE TABLE
-- blocks + FK clauses, for anyone doing a fresh install from scratch.)

USE catalogo23_5cat;

CREATE TABLE IF NOT EXISTS categorie (
    CategoriaId  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Nome         VARCHAR(100) NOT NULL,
    Descrizione  VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (CategoriaId),
    UNIQUE KEY uq_categorie_nome (Nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS produttori (
    ProduttoreId    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Nome            VARCHAR(100) NOT NULL,
    NazioneOrigine  VARCHAR(100) NOT NULL DEFAULT '',
    PRIMARY KEY (ProduttoreId),
    UNIQUE KEY uq_produttori_nome (Nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE prodotti
    ADD CONSTRAINT fk_prodotti_categoria  FOREIGN KEY (CategoriaId)  REFERENCES categorie (CategoriaId),
    ADD CONSTRAINT fk_prodotti_produttore FOREIGN KEY (ProduttoreId) REFERENCES produttori (ProduttoreId);
