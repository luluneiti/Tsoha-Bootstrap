
CREATE TABLE Kayttaja(
  tunnus SERIAL PRIMARY KEY, 
  rooli varchar(20) NOT NULL, 
  alkupv DATE NOT NULL, 
  loppupv DATE, 
  luontipv DATE NOT NULL, 
  salasana varchar(20) NOT NULL, 
  kayttajatunnus varchar(30) NOT NULL,
  osoite varchar(t100) NOT NULL,
  nimi varchar(50) NOT NULL
);


CREATE TABLE Rotu(
  rotutunnus SERIAL PRIMARY KEY, 
  nimi varchar(50) NOT NULL, 
  kuvaus text NOT NULL
);


CREATE TABLE Kasvattaja(
  kasvattajatunnus SERIAL PRIMARY KEY, 
  tunnus INTEGER REFERENCES Kayttaja(tunnus), 
  nimi varchar(50) NOT NULL, 
  paikkakunta varchar(100),
  alkupv DATE
);

CREATE TABLE Omistaja(
  omistajatunnus SERIAL PRIMARY KEY, 	
  tunnus INTEGER REFERENCES Kayttaja(tunnus), 
  yhteystietojenNaytto boolean DEFAULT FALSE, 
  omistajanNaytto boolean DEFAULT FALSE
);

CREATE TABLE KoeNayttely(
  tapahtumatunnus SERIAL PRIMARY KEY,
  tunnus INTEGER REFERENCES Kayttaja(tunnus),
  tyyppi varchar(15) NOT NULL,
  alityyppi varchar(25) NOT NULL,
  nimi varchar(30) NOT NULL,
  paikkakunta varchar(30),
  alkupv DATE NOT NULL,
  loppupv DATE
 
);


CREATE TABLE Koira(
  rekisterinumero SERIAL PRIMARY KEY, 
  rotutunnus INTEGER REFERENCES Rotu(rotutunnus), 
  kasvattajatunnus INTEGER REFERENCES Kasvattaja(kasvattajatunnus), 
  nimi varchar(50) NOT NULL, 
  syntymapv DATE NOT NULL,
  kuolinpv DATE,
  kuolinsyy varchar(50), 
  sukupuoli char(1) NOT NULL,
  rekisterointipv DATE NOT NULL,
  eronnut boolean DEFAULT FALSE,
  tila varchar(15) NOT NULL
);

CREATE TABLE Sukulaissuhde(
  suhdetunnus SERIAL PRIMARY KEY,
  vanhempitunnus INTEGER REFERENCES Koira(rekisterinumero), 
  lapsitunnus INTEGER REFERENCES Koira(rekisterinumero), 
  suhdetyyppi varchar(15) NOT NULL
 
);



CREATE TABLE KoeNayttelyTulos(
  tulostunnus SERIAL PRIMARY KEY,
  koiratunnus INTEGER REFERENCES Koira(rekisterinumero), 
  tapahtumatunnus INTEGER REFERENCES KoeNayttely(tapahtumatunnus), 
  tulos varchar(8) NOT NULL,
  tuloslisatieto varchar(8),
  tulospv DATE NOT NULL
 
);


CREATE TABLE Omistajasuhde(
  omistajasuhdetunnus SERIAL PRIMARY KEY,
  omistajatunnus INTEGER REFERENCES Omistaja(omistajatunnus), 
  koiratunnus INTEGER REFERENCES Koira(rekisterinumero)
 
);
