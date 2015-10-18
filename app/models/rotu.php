<?php

class Rotu extends BaseModel {

    public $rotutunnus, $rnimi, $kuvaus;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeKaikki() { //kaikki rodut

        $kysely = DB::connection()->prepare('SELECT * FROM Rotu');
        $kysely->execute();

        $rivit = $kysely->fetchAll();

        $rodut = array();

        foreach ($rivit as $rivi) {

            $rodut[] = new Rotu(array(
                'rotutunnus' => $rivi['rotutunnus'],
                'rnimi' => $rivi['nimi'],
		'kuvaus' => $rivi['kuvaus']
               
            ));
        }

        return $rodut;
    }

     public static function haeNimella($nimi) { //rodun haku nimellä
        $kysely = DB::connection()->prepare("SELECT * from rotu WHERE nimi like '$nimi'");
        $kysely->execute();
        $rivi = $kysely->fetch();

        if ($rivi) {
             $rotu[] = new Rotu(array(
                'rotutunnus' => $rivi['rotutunnus'],
                'rnimi' => $rivi['nimi'],
		'kuvaus' => $rivi['kuvaus']
               
            ));

            return $rotu;
        }

        return null;
    }
   

    public static function haeTunnuksella($tunnus) { //rodun haku tunnuksella
        $kysely = DB::connection()->prepare('SELECT * FROM rotu where rotutunnus=:tunnus');
        $kysely->execute(array('tunnus' => $tunnus));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $rotu[] = new Rotu(array(
                'rotutunnus' => $rivi['rotutunnus'],
                'rnimi' => $rivi['nimi'],
		'kuvaus' => $rivi['kuvaus']
               
            ));

            return $rotu;
        }

        return null;
    }
  


    
}
