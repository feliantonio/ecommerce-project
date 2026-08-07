-- seed_prodotti.sql
-- Sample product data built from the images already in /images, now with
-- real relations into categorie/produttori (see seed_categorie.sql and
-- seed_produttori.sql — import those two first, prodotti has FK
-- constraints on both CategoriaId and ProduttoreId).
--
-- NomeImmagine matches each filename WITHOUT the .jpg extension, since
-- dataDb/dbCatalogo.php renders <img src="../images/$NomeImmagine.jpg">.
-- logoShopping.png is the site logo, not a product, so it's excluded.
--
-- Import order: seed_categorie.sql, seed_produttori.sql, then this file.
--   mysql -u root catalogo23_5cat < seed_categorie.sql
--   mysql -u root catalogo23_5cat < seed_produttori.sql
--   mysql -u root catalogo23_5cat < seed_prodotti.sql
--
-- CategoriaId reference (matches seed_categorie.sql insertion order):
--   1  Notebook           6  Monitor            11 Stampante Laser
--   2  PC Desktop         7  Tastiera           12 Multifunzione Inkjet
--   3  All in One         8  Mouse              13 Multifunzione Laser
--   4  Chromebook         9  Kit Tastiera e Mouse
--   5  Tablet            10  Stampante Inkjet
-- ProduttoreId reference (matches seed_produttori.sql insertion order):
--   1 Lenovo   4 HP         7 Samsung    10 Epson    13 MSI
--   2 Asus     5 Acer       8 Logitech   11 Canon
--   3 Dell     6 Apple      9 Microsoft  12 Brother

USE catalogo23_5cat;

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
