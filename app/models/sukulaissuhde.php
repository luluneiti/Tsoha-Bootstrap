<?php

class Sukulaissuhde extends BaseModel {

    public $suhdetunnus, $vanhempitunnus, $vnimi, $lapsitunnus, $suhdetyyppi;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeVanhemmat($rekisterinumero) { //haetaan vanhempien tiedot

     $kysely = DB::connection()->prepare('SELECT a.suhdetunnus, a.vanhempitunnus, a. lapsitunnus, a.suhdetyyppi, 
b.nimi as vnimi from sukulaissuhde a, koira b where a.vanhempitunnus=b.rekisterinumero and lapsitunnus=:rekisterinumero'); 
     $kysely->execute(array('rekisterinumero' => $rekisterinumero));

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

    
    public function poista($rekisterinumero) {

        $kysely = DB::connection()->prepare('delete from sukulaissuhde where vanhempitunnus=:rekisterinumero or lapsitunnus=:rekisterinumero RETURNING suhdetunnus');

        $kysely->execute(array('rekisterinumero' => $rekisterinumero));
        $rivit = $kysely->fetchAll();

        //Kint::trace();
        Kint::dump($rivit);

        //$this->suhdetunnus = $rivi['suhdetunnus'];
    }


}
