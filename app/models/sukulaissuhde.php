<?php

class Sukulaissuhde extends BaseModel {

    public $suhdetunnus, $vanhempitunnus, $vnimi, $lapsitunnus, $suhdetyyppi;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeVanhemmat($rekisterinumero) { //haetaan vanhempien tiedot

     $kysely = DB::connection()->prepare('SELECT a.suhdetunnus, a.vanhempitunnus, a. lapsitunnus, a.suhdetyyppi, b.nimi as vnimi from sukulaissuhde a, koira b where a.vanhempitunnus=b.rekisterinumero');//where lapsitunnus=:rekisterinumero
     $kysely->execute();

        $rivit = $kysely->fetchAll();

        $suku = array();

        foreach ($rivit as $rivi) {

            $suku[] = new Sukulaissuhde(array(
                'suhdetunnus' => $rivi['suhdetunnus'],
                'vanhempitunnus' => $rivi['vanhempitunnus'],
		'lapsitunnus' => $rivi['lapsitunnus'],
		'suhdetyyppi' => $rivi['suhdetyyppi'],
                'vnimi' => $rivi['vnimi']

            ));

	 return $suku;
        }

       	return null;
    }

    
}
