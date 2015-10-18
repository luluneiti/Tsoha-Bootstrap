<?php

class KoeNayttely extends BaseModel {

    public $tapahtumatunnus, $tunnus, $nimi, $paikkakunta, $alkupv, $loppupv, $tyyppi, $alityyppi; 

    public function __construct($attributes) {
        parent::__construct($attributes);
	$this->validoitavat = array('validoiNimi','validoiPaikkakunta','validoiAlkupv','validoiAlkupv2','validoiLoppupv', 'validoiTyyppi', 'validoiAlityyppi');
    }

    public function validoiNimi() {

    	return BaseModel::tarkistaMjononPituus('nimi','Nimi', 5);

    }


    public function validoiPaikkakunta() {

    	return BaseModel::tarkistaMjononPituus('paikkakunta','Paikkakunta', 5);

    }


    public function validoiAlkupv() {

	return BaseModel::tarkistaPaivamaara('alkupv', 'Alkupaiva');
    	

    }
    public function validoiAlkupv2() {

    	return BaseModel::tarkistaPaivamaara2('alkupv', 'loppupv', 'Alkupaiva', 'Loppupaiva'); //ettei alkupv suurempi kuin loppupv

    }


   public function validoiLoppupv() {

    	return BaseModel::tarkistaPaivamaara('loppupv', 'Loppupaiva');

    }


   public function validoiTyyppi() {

    	return BaseModel::tarkistaMjononPituus('tyyppi','Tyyppi', 5);

    }


  public function validoiAlityyppi() {

    	return BaseModel::tarkistaMjononPituus('alityyppi','Alityyppi', 5);

    }



    public static function haeTunnuksella($vastuuhenkilo) { //haetaan vastuulla olevat kokeet ja nayttelyt
        $kysely = DB::connection()->prepare('SELECT * from koeNayttely where tunnus=:tunnus'); 
        $kysely->execute(array('tunnus' => $vastuuhenkilo));
        $rivit = $kysely->fetchAll();

        $koknayt = array();

        foreach ($rivit as $rivi) {

            $koknayt[] = new koeNayttely(array(
                'tapahtumatunnus' => $rivi['tapahtumatunnus'],
                'nimi' => $rivi['nimi'],
                'paikkakunta' => $rivi['paikkakunta'],
                'alkupv' => $rivi['alkupv'],
                'loppupv' => $rivi['loppupv'],
                'tyyppi' => $rivi['tyyppi'],
		'alityyppi' => $rivi['alityyppi'] //vasthlo!
            ));

            
        }
	return $koknayt;
       
    }


     public static function haeTunnuksella2($tapahtumatunnus) { //haetaan kokeet ja nayttelyt
        $kysely = DB::connection()->prepare('SELECT * from koeNayttely where tapahtumatunnus=:tapahtumatunnus LIMIT 1');
        $kysely->execute(array('tapahtumatunnus' => $tapahtumatunnus));
        $rivi = $kysely->fetch();

        if ($rivi) {

            $koknayt = new koeNayttely(array(
                'tapahtumatunnus' => $rivi['tapahtumatunnus'],
                'nimi' => $rivi['nimi'],
                'paikkakunta' => $rivi['paikkakunta'],
                'alkupv' => $rivi['alkupv'],
                'loppupv' => $rivi['loppupv'],
                'tyyppi' => $rivi['tyyppi'],
		'alityyppi' => $rivi['alityyppi'] //vasthlo!
            ));

            
        }
	return $koknayt;
       
    }



     public function tallenna() { 
        $query = DB::connection()->prepare('INSERT INTO KoeNayttely (nimi, paikkakunta, alkupv, loppupv, tyyppi, alityyppi, tunnus) VALUES (:nimi, :paikkakunta, :alkupv, :loppupv, :tyyppi, :alityyppi, :tunnus) RETURNING tapahtumatunnus');
        $query->execute(array('nimi' => $this->nimi, 'tunnus' => $this->tunnus, 'paikkakunta' => $this->paikkakunta, 'alkupv' => $this->alkupv, 'loppupv' => $this->loppupv, 'tyyppi' => $this->tyyppi, 'alityyppi' => $this->alityyppi));
        $row = $query->fetch();

        $this->tapahtumatunnus = $row['tapahtumatunnus'];
    }


}
