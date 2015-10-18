<?php

class KoeNayttelyTulos extends BaseModel {

    public $tyyppi, $alityyppi, $tulos, $tuloslisatieto, $paikkakunta, $tulospv, $koiratunnus, $tapahtumatunnus;

    public function __construct($attributes) {
        parent::__construct($attributes);
	$this->validoitavat = array('validoiTulos','validoiTuloslisatieto', 'validoiTulospv');
    }


    public function validoiTulos() {

    	return BaseModel::tarkistaMjononPituus('tulos','Tulos', 2);

    }
     public function validoiTuloslisatieto() {

    	return BaseModel::tarkistaMjononPituus('tuloslisatieto','Tuloslisatieto', 2);

    }


    public function validoiTulospv() {

	return BaseModel::tarkistaPaivamaara('tulospv', 'Tulospaiva');
    	

    }

    

    public static function haeTulokset($rekisterinumero) { //haetaan koira tulokset
        $kysely = DB::connection()->prepare('SELECT b.tyyppi, b.alityyppi, a.tulos, a.tuloslisatieto, b.paikkakunta, a.tulospv from koenayttelytulos a, koenayttely b where a.tapahtumatunnus=b.tapahtumatunnus and a.koiratunnus=:rekisterinumero');
        $kysely->execute(array('rekisterinumero' => $rekisterinumero));
        $rivit = $kysely->fetchAll();

        $tulokset = array();

        foreach ($rivit as $rivi) {

            $tulokset[] = new koeNayttelyTulos(array(
                'tyyppi' => $rivi['tyyppi'],
                'alityyppi' => $rivi['alityyppi'],
                'tulos' => $rivi['tulos'],
                'tuloslisatieto' => $rivi['tuloslisatieto'],
                'paikkakunta' => $rivi['paikkakunta'],
                'tulospv' => $rivi['tulospv']
            ));

            
        }
	return $tulokset;
       
    }


     public static function haeKaikki() { //haetaan kaikki tulokset
        $kysely = DB::connection()->prepare('SELECT * from koenayttelytulos');
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $tulokset = array();

        foreach ($rivit as $rivi) {

            $tulokset[] = new koeNayttelyTulos(array(
		'tapahtumatunnus' => $rivi['tapahtumatunnus'],
		'koiratunnus' => $rivi['koiratunnus'],
                'tulos' => $rivi['tulos'],
                'tuloslisatieto' => $rivi['tuloslisatieto'],
                'tulospv' => $rivi['tulospv']
            ));

           
        }

         return $tulokset;
    }

     public function tallenna() { 
        $query = DB::connection()->prepare('INSERT INTO KoeNayttelyTulos (tapahtumatunnus, koiratunnus, tulos, tulospv, tuloslisatieto) VALUES (:tapahtumatunnus, :koiratunnus, :tulos, :tulospv, :tuloslisatieto) RETURNING tulostunnus');
        $query->execute(array('tapahtumatunnus' => $this->tapahtumatunnus, 'koiratunnus' => $this->koiratunnus, 'tulos' => $this->tulos, 'tulospv' => $this->tulospv, 'tuloslisatieto' => $this->tuloslisatieto));
        $row = $query->fetch();

        $this->tulostunnus = $row['tulostunnus'];
    }

    public function poista($rekisterinumero) {

        $kysely = DB::connection()->prepare('delete from koenayttelytulos where koiratunnus=:rekisterinumero RETURNING koiratunnus');
        $kysely->execute(array('rekisterinumero' => $rekisterinumero));
        $rivi = $kysely->fetch();

    }

}
