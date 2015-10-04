<?php

class Omistaja extends BaseModel { //tätä käytetään yleisiin omistaja-kyselyihin

    public $tunnus, $nimi, $osoite, $yhteystietojenNaytto, $omistajatietojenNaytto;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeKaikki() { //kaikki omistajat

        $kysely = DB::connection()->prepare("SELECT a.tunnus, b.nimi, b.osoite, a.yhteystietojennaytto, a.omistajannaytto 
FROM Omistaja a, Kayttaja b where a.tunnus=b.tunnus and b.rooli='omistaja'");
        $kysely->execute();

        $rivit = $kysely->fetchAll();

        $omistajat = array();

        foreach ($rivit as $rivi) {

            $omistajat[] = new Omistaja(array(
                'tunnus' => $rivi['tunnus'],
                'nimi' => $rivi['nimi'],
		'osoite' => $rivi['osoite'],
		'yhteystietojenNaytto' => $rivi['yhteystietojennaytto'],
		'omistajatietojenNaytto' => $rivi['omistajannaytto'],
               
            ));
        }

        return $omistajat;
    }

    

    
}
