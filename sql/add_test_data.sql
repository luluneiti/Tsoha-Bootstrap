INSERT INTO Kayttaja (rooli, alkupv, luontipv, salasana, kayttajatunnus, osoite, nimi) VALUES ('omistaja', '2011-11-11', '2011-11-11', 'salasana', 'testiasiakas1@hotmail.com', 'lahiosoite, 00500 Helsinki', 'testi om1');
INSERT INTO Kayttaja (rooli, alkupv, luontipv, salasana, kayttajatunnus, osoite, nimi) VALUES ('omistaja', '2012-11-11', '2012-11-11', 'salasana', 'testiasiakas2@hotmail.com', 'lahiosoite, 00500 Helsinki', 'testi om2');
INSERT INTO Kayttaja (rooli, alkupv, luontipv, salasana, kayttajatunnus, osoite, nimi) VALUES ('kasvattaja', '2013-11-11', '2013-11-11', 'salasana', 'testiasiakas3@hotmail.com', 'lahiosoite, 00500 Helsinki', 'testi kasv1');
INSERT INTO Kayttaja (rooli, alkupv, luontipv, salasana, kayttajatunnus, osoite, nimi) VALUES ('kkirjaaja', '2014-11-11', '2014-11-11', 'salasana', 'testiasiakas4@hotmail.com', 'lahiosoite, 00500 Helsinki', 'testi koe nayttely kirjaaja');
INSERT INTO Kayttaja (rooli, alkupv, luontipv, salasana, kayttajatunnus, osoite, nimi) VALUES ('kasvattaja', '2014-11-11', '2015-11-11', 'salasana', 'testiasiakas5@hotmail.com', 'lahiosoite, 00500 Helsinki', 'testi kasv2');

INSERT INTO Rotu (nimi, kuvaus) VALUES ('Sileäkarvainen kettuterrieri', 'Sileäkarvainen kettuterrieri on säkäkorkeudeltaan vajaa 40 cm korkea, ruumiinrakenteeltaan neliömäinen koira. Kallo on yhtä pitkä kuin kuono-osa, ja molemmat kapenevat hieman.');
INSERT INTO Rotu (nimi, kuvaus) VALUES ('Seiskarinkoira', 'Nykyisten seiskarinkoirien jalostuksessa tavoitellaan seuraavan määritelmän mukaista tyyppiä: urosten korkeus 47–53 cm, narttujen 44–50 cm. Paino on suhteessa koiran korkeuteen, yleensä 14–20 kg.');

INSERT INTO Kasvattaja (nimi, paikkakunta, alkupv) VALUES ('Kennel Lapinpoika', 'Rovaniemi', '2014-11-11');
INSERT INTO Kasvattaja (nimi, paikkakunta, alkupv) VALUES ('Kennel Sara', 'Turku', '2013-11-11');


INSERT INTO Omistaja (yhteystietojenNaytto, omistajanNaytto) VALUES ('true', 'true');
INSERT INTO Omistaja (yhteystietojenNaytto, omistajanNaytto) VALUES ('false', 'true');

INSERT INTO KoeNayttely(tyyppi, alityyppi, nimi, paikkakunta, alkupv, loppupv) VALUES ('Näyttely', 'Erikoisnäyttely', 'Hieno näyttely', 'Espoo', '2013-11-11', '2013-11-11');
INSERT INTO KoeNayttely(tyyppi, alityyppi, nimi, paikkakunta, alkupv, loppupv) VALUES ('Koe', 'Luonnetesti', 'Hieno koe', 'Vantaa', '2012-11-11', '2012-11-11');

INSERT INTO Koira(rotutunnus, kasvattajatunnus, nimi, syntymapv, sukupuoli, rekisterointipv, tila) VALUES (1, 1, 'Hieno koira', '2010-11-11', 'N', '2013-11-11', 'valmis');
INSERT INTO Koira(rotutunnus, kasvattajatunnus, nimi, syntymapv, sukupuoli, rekisterointipv, tila) VALUES (2, 2, 'Hieno koira2', '2010-11-11', 'U', '2014-11-11', 'kesken');
INSERT INTO Koira(rotutunnus, kasvattajatunnus, nimi, syntymapv, sukupuoli, rekisterointipv, tila) VALUES (2, 2, 'Hieno koira3', '2015-01-01', 'U', '2014-11-11', 'kesken');

INSERT INTO Sukulaissuhde(vanhempitunnus, lapsitunnus, suhdetyyppi) VALUES (1, 3, 'emä');

INSERT INTO KoeNayttelyTulos(koiratunnus, tapahtumatunnus, tulos, tuloslisatieto, tulospv) VALUES (1, 1, 'LTEP', '+140', '2014-11-11');

INSERT INTO Omistajasuhde(omistajatunnus, koiratunnus) VALUES (1, 1);
