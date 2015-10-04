<?php

class Omistajasuhde extends BaseModel {

    public $omistajasuhdetunnus, $omistajatunnus, $koiratunnus, $omnimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeSuhteet($rekisterinumero) { //kaikki koiraan littyvät omistajasuhteet

        $kysely = DB::connection()->prepare('SELECT a.omistajasuhdetunnus, a.omistajatunnus, a.koiratunnus, 
c.nimi as omnimi FROM Omistajasuhde a inner join Omistaja b on a.omistajatunnus=b.omistajatunnus inner join Kayttaja c on b.tunnus=c.tunnus WHERE a.koiratunnus =:rekisterinumero');  //

        $kysely->execute(array('rekisterinumero' => $rekisterinumero));

        $rivit = $kysely->fetchAll();

	$suhteet=array();

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


      public function tallenna($omistajatunnus, $koiratunnus) { 
        $query = DB::connection()->prepare('INSERT INTO Omistajasuhde (omistajatunnus, koiratunnus) 
VALUES (:omistajatunnus, :koiratunnus) RETURNING koiratunnus');

        $query->execute(array('omistajatunnus' => $omistajatunnus, 'koiratunnus' => $koiratunnus));

        $row = $query->fetch();

        //Kint::trace();
        //Kint::dump($row);

        $rekisterinumero = $row['koiratunnus'];
    }

     public function poista($rekisterinumero) {

        $kysely = DB::connection()->prepare('delete from omistajasuhde where koiratunnus=:rekisterinumero');

        $kysely->execute(array('rekisterinumero' => $rekisterinumero));
        $rivi = $kysely->fetch();

        //Kint::trace();
        Kint::dump($rivi);

        //$this->koiratunnus = $rivi['koiratunnus'];
    }


    
}
