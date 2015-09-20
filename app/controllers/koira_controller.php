<?php
require 'app/models/koira.php';
require 'app/models/kasvattaja.php';
require 'app/models/rotu.php';
require 'app/models/omistajasuhde.php';
require 'app/models/sukulaissuhde.php';
require 'app/models/koeNayttelyTulos.php';
class KoiraController extends BaseController {

    public static function listaaKaikki() { //hae kaikki koirat
        $koirat = Koira::haekaikki();
	Kint::dump($koirat);
        View::make('koira_listaus.html', array('koirat' => $koirat));
    }


    public static function esittely($rekisterinumero) { //hae yksi koira

	$suhteet = Omistajasuhde::haeSuhteet($rekisterinumero);
	$suku = Sukulaissuhde::haeVanhemmat($rekisterinumero);
	$tulokset = KoeNayttelyTulos::haeTulokset($rekisterinumero);


        $koira = Koira::haeTunnuksella($rekisterinumero);
	Kint::dump($suhteet);
	Kint::dump($suku);
	Kint::dump($koira);
        View::make('koira_esittely.html', array('koira' => $koira, 'suhteet' =>  $suhteet,  'suku' =>  $suku,  'tulokset' =>  $tulokset));
    }

    public static function naytaLisaa() { //nayta lisayslomake

	$rodut = Rotu::haekaikki();
	$kasvattajat = Kasvattaja::haekaikki();

	 Kint::dump($rodut);
    	 Kint::dump($kasvattajat);
	//$spuolet = Koira::haeSukupuolet(); 

        View::make('koira_luonti.html', array('rodut' => $rodut, 'kasvattajat' => $kasvattajat) ); 
    }

    public static function lisaa() { //koiran lisays

        $tanaan = date('Y/m/d');

        $params = $_POST;
        $koira = new Koira(array(
            'rotu' => $params['rotu'], 
            'kasvattaja' => $params['kasvattaja'],
            'nimi' => $params['nimi'],
            'syntymapv' => $params['syntymapv'],
            'sukupuoli' => $params['sukupuoli'],
            'rekisterointipv' => '2015-09-18', 
            'tila' => 'kesken'
        ));

        Kint::dump($params);

        $koira->tallenna();

        //Redirect::to('/koira/' . $koira->rekisterinumero, array('message' => 'Koira on lisätty.'));
    }

}