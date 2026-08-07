-- seed_produttori.sql
-- Producers used by seed_prodotti.sql (import order: categorie +
-- produttori first, then prodotti, since prodotti has FK constraints on
-- both).
--
-- Run via phpMyAdmin "Import", or:
--   mysql -u root catalogo23_5cat < seed_produttori.sql

USE catalogo23_5cat;

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
