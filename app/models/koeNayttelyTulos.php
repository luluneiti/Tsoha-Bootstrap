<?php

class KoeNayttelyTulos extends BaseModel {

    public $tyyppi, $alityyppi, $tulos, $tuloslisatieto, $paikkakunta, $tulospv;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeTulokset($rekisterinumero) { //haetaan koira tulokset

     $kysely = DB::connection()->prepare
('SELECT b.tyyppi, b.alityyppi, a.tulos, a.tuloslisatieto, b.paikkakunta, a.tulospv from koenayttelytulos a, koenayttely b where a.tapahtumatunnus=b.tapahtumatunnus');//and a.koiratunnus=:rekisterinumero
     $kysely->execute();

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

	 return $tulokset;
        }

       	return null;
    }

    
}