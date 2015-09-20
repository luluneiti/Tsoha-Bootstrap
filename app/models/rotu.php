<?php

class Rotu extends BaseModel {

    public $rotutunnus, $nimi, $kuvaus;

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
                'nimi' => $rivi['nimi'],
		'kuvaus' => $rivi['kuvaus']
               
            ));
        }

        return $rodut;
    }

    
}
