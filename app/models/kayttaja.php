<?php

class Kayttaja extends BaseModel {

    public $kayttajatunnus, $salasana, $rooli, $nimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function tarkistaOikeudet($kayttajatunnus, $salasana) { 

       $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajatunnus = :kayttajatunnus AND salasana= :salasana LIMIT 1'); 
	$kysely->execute(array('kayttajatunnus' => $kayttajatunnus, 'salasana' => $salasana));
	$rivi = $kysely->fetch();
	if($rivi){
  	         $kayttaja = new Kayttaja(array(
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'rooli' => $rivi['rooli'],
		'nimi' => $rivi['nimi']
            ));

            return $kayttaja;

	
	}else{
  	// Käyttäjää ei löytynyt, palautetaan null
	return null;
	}
          
    }


    public static function haeTunnuksella($kayttajatunnus) { //kayttajan haku tunnuksella
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajatunnus = :kayttajatunnus');
        $kysely->execute(array('kayttajatunnus' => $kayttajatunnus));

        $rivi = $kysely->fetch();

        if ($rivi) {
             $kayttaja = new Kayttaja(array(
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'rooli' => $rivi['rooli'],
		'nimi' => $rivi['nimi']

                            ));

            return $kayttaja;
        }

        return null;
    }

    
}
