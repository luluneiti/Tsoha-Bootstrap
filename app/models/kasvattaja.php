<?php

class Kasvattaja extends BaseModel {

    public $kasvattajatunnus, $tunnus, $nimi, $paikkakunta, $alkupv;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeKaikki() { //kaikki kasvattajat
        $kysely = DB::connection()->prepare('SELECT * FROM Kasvattaja');
        $kysely->execute();

        $rivit = $kysely->fetchAll();

        $kasvattaja = array();

        foreach ($rivit as $rivi) {

            $kasvattaja[] = new Kasvattaja(array(
                'kasvattajatunnus' => $rivi['kasvattajatunnus'],
		'tunnus' => $rivi['tunnus'],
                'nimi' => $rivi['nimi'],
                'paikkakunta' => $rivi['paikkakunta'],
                
            ));
        }

        return $kasvattaja;
    }

    
}