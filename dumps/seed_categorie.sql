-- seed_categorie.sql
-- Categories used by seed_prodotti.sql (import order: categorie +
-- produttori first, then prodotti, since prodotti has FK constraints on
-- both).
--
-- Run via phpMyAdmin "Import", or:
--   mysql -u root catalogo23_5cat < seed_categorie.sql

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
