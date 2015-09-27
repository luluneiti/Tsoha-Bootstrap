<?php

class KoiraController extends BaseController {

    public static function listaaKaikki() { //hae kaikki koirat
        $koirat = Koira::haekaikki();
        View::make('koira_listaus.html', array('koirat' => $koirat));
    }


    public static function esittely($rekisterinumero) { //hae yksi koira

	$suhteet = Omistajasuhde::haeSuhteet($rekisterinumero);
	$suku = Sukulaissuhde::haeVanhemmat($rekisterinumero);
	$tulokset = KoeNayttelyTulos::haeTulokset($rekisterinumero);
        $koira = Koira::haeTunnuksella($rekisterinumero);

        View::make('koira_esittely.html', array('koira' => $koira, 'suhteet' =>  $suhteet,  'suku' =>  $suku,  'tulokset' =>  $tulokset));
    }

    public static function naytaLisaa() { //nayta lisayslomake

	$rodut = Rotu::haekaikki();
	$kasvattajat = Kasvattaja::haekaikki();
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
        $koira->tallenna();
        Redirect::to('/koira/' . $koira->rekisterinumero, array('viesti' => 'Koira on lisätty.'));
    }

    public static function naytaMuuta($rekisterinumero){ //nayta editointilomake

    	$koira = Koira::haeTunnuksella($rekisterinumero);
	$rodut = Rotu::haekaikki();
	$kasvattajat = Kasvattaja::haekaikki();

    	View::make('koira_muokkaus.html', array('koira' => $koira, 'rodut' => $rodut, 'kasvattajat' => $kasvattajat));
    }


    public static function paivitys($rekisterinumero){ //koiran editointi
    	$params = $_POST;

	//$rotu= Rotu::haeNimella($params['rotu']);

    	$attribuutit = array(
	    'rekisterinumero' => $rekisterinumero,
	    'rotu' => $params['rotu'],
            'kasvattaja' => $params['kasvattaja'],
            'nimi' => $params['nimi'],
            'syntymapv' => $params['syntymapv'],
            'sukupuoli' => $params['sukupuoli']
    	);
    
	$koira = new Koira($attribuutit);
	
    	//$virheet = $koira->virheet();

    	//if(count($virheet) > 0){
      		//View::make('koira/koira_muokkaus.html', array('errors' => $virheet, 'attribuutit' => $attribuutit));
    	//}else{

      	$koira->paivita();

      	Redirect::to('/koira/' . $koira->rekisterinumero, array('viesti' => 'Koiran tietoja on muokattu onnistuneesti!'));
    //}
  }


  public static function poisto($rekisterinumero){ //koiran poisto
    
    $koira = new Koira(array('rekisterinumero' => $rekisterinumero));

    $suhteet = Omistajasuhde::haeSuhteet($rekisterinumero);
    $suku = Sukulaissuhde::haeVanhemmat($rekisterinumero);
    $tulokset = KoeNayttelyTulos::haeTulokset($rekisterinumero);

    if($suhteet) { 
	Omistajasuhde::poista($rekisterinumero); 
    }
    if($suku) { 
	Sukulaissuhde::poista($rekisterinumero); 
    }
    if($tulokset) { 
	KoeNayttelyTulos::poista($rekisterinumero); 
    }

    Kint::dump($koira);

    $koira->poista();

    Redirect::to('/koira', array('viesti' => 'Koira on poistettu onnistuneesti!'));
  }

}