-- Testdata voor Kringloop Centrum Duurzaam

-- Eerst statussen toevoegen
INSERT INTO `status` (`id`, `status`) VALUES
(1, 'Nieuw'),
(2, 'Moet gerepareerd worden'),
(3, 'Gerepareerd'),
(4, 'Verkoop-gereed'),
(5, 'Verkocht');

-- Testgebruikers (wachtwoord voor allemaal: "test123")
INSERT INTO `gebruiker` (`id`, `gebruikersnaam`, `wachtwoord`, `rollen`, `is_geverifieerd`) VALUES
(1, 'directeur', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Directie', 1),
(2, 'magazijn1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Magazijnmedewerker', 1),
(3, 'winkel1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Winkelpersoneel', 1),
(4, 'chauffeur1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Chauffeur', 1);

-- Categorieën
INSERT INTO `categorie` (`id`, `categorie`) VALUES
(1, 'Kleding - Dameskleding'),
(2, 'Kleding - Herenkleding'),
(3, 'Kleding - Kinderkleding'),
(4, 'Meubels - Tafels'),
(5, 'Meubels - Stoelen'),
(6, 'Meubels - Bankstel'),
(7, 'Bedden - 1-persoons'),
(8, 'Bedden - 2-persoons'),
(9, 'Kledingkasten'),
(10, 'Witgoed - Wasmachines'),
(11, 'Witgoed - Koelkasten'),
(12, 'Bruingoed - Televisies'),
(13, 'Grijsgoed - ICT'),
(14, 'Boeken');

-- Klanten
INSERT INTO `klant` (`id`, `naam`, `adres`, `plaats`, `telefoon`, `email`) VALUES
(1, 'Jan Jansen', 'Hoofdstraat 123', 'Amsterdam', '0612345678', 'jan@email.nl'),
(2, 'Maria Pieters', 'Kerkstraat 45', 'Utrecht', '0623456789', 'maria@email.nl'),
(3, 'Piet de Vries', 'Dorpsweg 78', 'Rotterdam', '0634567890', 'piet@email.nl'),
(4, 'Sophie Bakker', 'Schoollaan 12', 'Den Haag', '0645678901', 'sophie@email.nl');

-- Artikelen
INSERT INTO `artikel` (`id`, `categorie_id`, `naam`, `prijs_ex_btw`) VALUES
(1, 6, 'Leren 3-zits bankstel bruin', 299.00),
(2, 4, 'Eiken eettafel 160x90cm', 149.00),
(3, 5, 'Set van 4 eetkamerstoelen', 79.00),
(4, 10, 'Bosch wasmachine 7kg', 189.00),
(5, 11, 'Siemens koelkast A+++', 249.00),
(6, 12, 'Samsung Smart TV 43 inch', 199.00),
(7, 8, 'Boxspring 2-persoons 180x200', 399.00),
(8, 9, '3-deurs kledingkast wit', 129.00),
(9, 1, 'Dames winterjas maat M', 29.00),
(10, 14, 'Romans en Thrillers - set van 10', 15.00);

-- Voorraad
INSERT INTO `voorraad` (`id`, `artikel_id`, `locatie`, `aantal`, `status_id`, `ingeboekt_op`) VALUES
(1, 1, 'Showroom', 1, 4, '2026-01-20 10:30:00'),
(2, 2, 'Showroom', 2, 4, '2026-01-21 11:00:00'),
(3, 3, 'Magazijn', 8, 4, '2026-01-22 09:15:00'),
(4, 4, 'Werkplaats', 1, 2, '2026-01-23 14:20:00'),
(5, 5, 'Magazijn', 2, 4, '2026-01-24 10:00:00'),
(6, 6, 'Showroom', 3, 4, '2026-01-25 13:30:00'),
(7, 7, 'Showroom', 1, 4, '2026-01-26 11:45:00'),
(8, 8, 'Magazijn', 2, 3, '2026-01-27 09:00:00'),
(9, 9, 'Winkel', 15, 4, '2026-01-27 10:30:00'),
(10, 10, 'Winkel', 5, 4, '2026-01-28 08:00:00');

-- Verkopen
INSERT INTO `verkopen` (`id`, `klant_id`, `artikel_id`, `verkocht_op`) VALUES
(1, 1, 9, '2026-01-15 14:20:00'),
(2, 2, 10, '2026-01-18 11:30:00'),
(3, 3, 3, '2026-01-22 15:45:00');

-- Planning
INSERT INTO `planning` (`id`, `artikel_id`, `klant_id`, `kenteken`, `ophalen_of_bezorgen`, `afspraak_op`) VALUES
(1, 1, 1, 'AB-123-CD', 'bezorgen', '2026-01-30 10:00:00'),
(2, 7, 4, 'AB-123-CD', 'bezorgen', '2026-01-30 14:00:00'),
(3, 5, 2, 'EF-456-GH', 'bezorgen', '2026-01-31 09:00:00');
