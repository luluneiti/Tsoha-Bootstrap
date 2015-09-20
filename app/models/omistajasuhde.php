<?php

class Omistajasuhde extends BaseModel {

    public $omistajasuhdetunnus, $omistajatunnus, $koiratunnus, $omnimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeSuhteet($rekisterinumero) { //kaikki koiraan littyv�t omistajasuhteet

        $kysely = DB::connection()->prepare('SELECT a.omistajasuhdetunnus, a.omistajatunnus, a.koiratunnus, c.nimi as omnimi FROM Omistajasuhde a inner join Omistaja b on a.omistajatunnus=b.omistajatunnus inner join Kayttaja c on b.tunnus=c.tunnus'  ); //WHERE a.koiratunnus = $rekisterinumero
        $kysely->execute();

        $rivit = $kysely->fetchAll();

        $suhteet = array();

        foreach ($rivit as $rivi) {

            $suhteet[] = new Omistajasuhde(array(
                'omistajasuhdetunnus' => $rivi['omistajasuhdetunnus'],
                'omistajatunnus' => $rivi['omistajatunnus'],
		'koiratunnus' => $rivi['koiratunnus'],
		'omnimi' => $rivi['omnimi']
               
            ));
        }

        return $suhteet;
    }

    
}
