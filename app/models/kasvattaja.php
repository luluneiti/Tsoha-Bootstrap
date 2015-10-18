<?php

class Kasvattaja extends BaseModel {

    public $kasvattajatunnus, $tunnus, $knimi, $paikkakunta, $alkupv;

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
                'knimi' => $rivi['nimi'],
                'paikkakunta' => $rivi['paikkakunta'],
            ));
        }

        return $kasvattaja;
    }

    public static function haeTunnuksella($tunnus) { //kasvattajan haku tunnuksella
        $kysely = DB::connection()->prepare('SELECT * FROM Kasvattaja where kasvattajatunnus=:tunnus');
        $kysely->execute(array('tunnus' => $tunnus));
        $rivi = $kysely->fetch();

        if ($rivi) {
           $kasvattaja = new Kasvattaja(array(
                'kasvattajatunnus' => $rivi['kasvattajatunnus'],
                'tunnus' => $rivi['tunnus'],
                'knimi' => $rivi['nimi'],
                'paikkakunta' => $rivi['paikkakunta'],
            ));


            return $kasvattaja;
        }

        return null;
    }
  


}
