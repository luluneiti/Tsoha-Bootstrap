<?php

class Omistaja extends BaseModel { //tätä käytetään yleisiin omistaja-kyselyihin

    public $tunnus, $nimi, $osoite, $yhteystietojenNaytto, $omistajatietojenNaytto;

    public function __construct($attributes) {
        parent::__construct($attributes);
	

    }

   

    public static function haeKaikki() { //kaikki omistajat

        $kysely = DB::connection()->prepare("SELECT a.tunnus, b.nimi, b.osoite, a.yhteystietojennaytto, a.omistajannaytto FROM Omistaja a, Kayttaja b where a.tunnus=b.tunnus and b.rooli='omistaja'");
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $omistajat = array();

        foreach ($rivit as $rivi) {

            $omistajat[] = new Omistaja(array(
                'tunnus' => $rivi['tunnus'],
                'nimi' => $rivi['nimi'],
		'osoite' => $rivi['osoite'],
		'yhteystietojenNaytto' => $rivi['yhteystietojennaytto'],
		'omistajatietojenNaytto' => $rivi['omistajannaytto']
               
            ));
        }

        return $omistajat;
    }

    public static function haeTunnuksella($tunnus) { //kayttajan haku tunnuksella
        $kysely = DB::connection()->prepare('SELECT tunnus, yhteystietojennaytto, omistajannaytto FROM Omistaja where tunnus=:tunnus');
        $kysely->execute(array('tunnus' => $tunnus));
        $rivi = $kysely->fetch();

        if ($rivi) {
           $omistaja = new Omistaja(array(
                'tunnus' => $rivi['tunnus'],
      		'yhteystietojenNaytto' => $rivi['yhteystietojennaytto'],
		'omistajatietojenNaytto' => $rivi['omistajannaytto']
               
            ));

            return $omistaja;
        }

        return null;
    }
  

     public function tallenna() { 
        $query = DB::connection()->prepare('INSERT INTO Omistaja (tunnus, yhteystietojenNaytto) VALUES (:tunnus, :yhteystietojenNaytto) RETURNING omistajatunnus');
        $query->execute(array('tunnus' => $this->tunnus, 'yhteystietojenNaytto' => $this->yhteystietojenNaytto));
        $rivi = $query->fetch();
	Kint::dump($rivi);

        $this->tunnus = $rivi['omistajatunnus'];
     }


   public function paivita() {

        $kysely = DB::connection()->prepare('update omistaja set yhteystietojenNaytto=:yhteystietojenNaytto where tunnus=:tunnus RETURNING omistajatunnus');
        $kysely->execute(array('tunnus' => $this->tunnus, 'yhteystietojenNaytto' => $this->yhteystietojenNaytto));
        $rivi = $kysely->fetch();
	Kint::dump($rivi);

	$this->tunnus = $rivi['omistajatunnus'];
    }
}
