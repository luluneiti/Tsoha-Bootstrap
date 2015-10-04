<?php

class KoiraController extends BaseController {

    public static function listaaKaikki() { //hae kaikki koirat tai nimellä tai nimen osalla
	
	$koirat = Koira::haekaikki(); 
        View::make('koira_listaus.html', array('koirat' => $koirat));
    }


    public static function listaaOmat() {

	self::check_logged_in();
	if(self::get_user_logged_in()->rooli=="omistaja"){
		$koirat =Koira::haeOmistajanTunnuksella(self::get_user_logged_in()->tunnus);
	}
	else { //hoitaja
		$koirat =Koira::haeHyvaksyttavat();
		
	}

        View::make('koira_listaus.html', array('koirat' => $koirat));
    }


    public static function esittely($rekisterinumero) { //hae yksi koira
	//self::check_logged_in();
	$suhteet = Omistajasuhde::haeSuhteet($rekisterinumero);
	$suku = Sukulaissuhde::haeVanhemmat($rekisterinumero);
	$tulokset = KoeNayttelyTulos::haeTulokset($rekisterinumero);
        $koira = Koira::haeTunnuksella($rekisterinumero);
	//Kint::dump($suku);
        View::make('koira_esittely.html', array('koira' => $koira, 'suhteet' =>  $suhteet,  'suku' =>  $suku,  'tulokset' =>  $tulokset));
    }

    public static function naytaLisaa() { //nayta lisayslomake
	//self::check_logged_in();
	$rodut = Rotu::haekaikki();
	$kasvattajat = Kasvattaja::haekaikki();
	$emat=Koira::haeEmotIsa('N');
	$isat=Koira::haeEmotIsa('U');
	$omistajat = Omistaja::haekaikki();
	//$spuolet = Koira::haeSukupuolet(); 

        View::make('koira_luonti.html', array('rodut' => $rodut, 'kasvattajat' => $kasvattajat, 'emat' => $emat, 'isat' => $isat, 'omistajat' => $omistajat) ); 
    }

    public static function lisaa() { //koiran lisays TALLENNA MYÖS OMISTAJASUHDE JA SUKULAISSUHDE

        $params = $_POST;
	Kint::dump($params);

        $koira = array(
            'rotu' => $params['rotu'], 
            'kasvattaja' => $params['kasvattaja'],
            'nimi' => $params['nimi'],
            'syntymapv' => $params['syntymapv'],
            'sukupuoli' => $params['sukupuoli'],
            'rekisterointipv' => '2015-09-18',  //sql lauseeseen current date
            'tila' => 'kesken' 
        );
	
	$koir = new Koira($koira);

  	//$virheet = $koir->errors();
	//Kint::dump($virheet);

	 //if(count($virheet) > 0){
		//$rodut = Rotu::haekaikki();
		//$kasvattajat = Kasvattaja::haekaikki();
    		//View::make('koira_luonti.html', array('virheet' => $virheet, 'koira' => $koira,'rodut' => $rodut, 'kasvattajat' => $kasvattajat));
         //}
	 //else{
		$koir->tallenna();
		Omistajasuhde::tallenna($params['omistaja'], $koir->rekisterinumero);
		Sukulaissuhde::tallenna($params['ema'], $koir->rekisterinumero, 'ema'); 
		Sukulaissuhde::tallenna($params['isa'], $koir->rekisterinumero, 'isa');

        	Redirect::to('/koira/' . $koir->rekisterinumero, array('viesti' => 'Koira on lisätty.'));
		
  	 //}

    }

    

    public static function naytaMuuta($rekisterinumero){ //nayta editointilomake
	self::check_logged_in();
    	$koira = Koira::haeTunnuksella($rekisterinumero);
	$rodut = Rotu::haekaikki();
	$kasvattajat = Kasvattaja::haekaikki();
	$omistajat = Omistaja::haekaikki();
	$emat=Koira::haeEmotIsa('N');
	$isat=Koira::haeEmotIsa('U');

    	View::make('koira_muokkaus.html', array('koira' => $koira, 'rodut' => $rodut, 'kasvattajat' => $kasvattajat, 'emat' => $emat, 'isat' => $isat, 'omistajat' => $omistajat));
    }


    
   
    public static function paivitys($rekisterinumero){ //koiran editointi TALLENNA MYÖS OMISTAJASUHDE JA SUKULAISSUHDE

    	$params = $_POST;

    	$koira = array(
	    'rekisterinumero' => $rekisterinumero,
	    'rotu' => $params['rotu'],
            'kasvattaja' => $params['kasvattaja'],
            'nimi' => $params['nimi'],
            'syntymapv' => $params['syntymapv'],
            'sukupuoli' => $params['sukupuoli']
    	);
    
	$koir = new Koira($koira);
    	//$virheet = $koir->errors();
	//Kint::dump($virheet);

    	//if(count($virheet) > 0){
		//$rodut = Rotu::haekaikki();
		//$kasvattajat = Kasvattaja::haekaikki();
		//View::make('koira_muokkaus.html', array('virheet' => $virheet, 'koira' => $koira,'rodut' => $rodut, 'kasvattajat' => $kasvattajat));
    	//}
	//else {
		$koir->paivita();
		$suhteet = Omistajasuhde::haeSuhteet($rekisterinumero);
    		$suku = Sukulaissuhde::haeVanhemmat($rekisterinumero);
   
    		if($suhteet) { 
			Omistajasuhde::poista($rekisterinumero); 
    		}
    		if($suku) { 
			Sukulaissuhde::poista($rekisterinumero); 
    		}

		KoiraController::muutSuhteet($koir->rekisterinumero, $params); 
      		Redirect::to('/koira/' . $koir->rekisterinumero, array('viesti' => 'Koiran tietoja on muokattu onnistuneesti!'));
		
	//}
      		
  }

   public static function muutSuhteet($rekisterinumero, $params) {

	$omistajat=$params['omistajat'];

	foreach($omistajat as $omist){
 	 	Omistajasuhde::tallenna($omist, $rekisterinumero); 
			
	}
	
	Sukulaissuhde::tallenna($params['ema'], $rekisterinumero, 'ema'); 
	Sukulaissuhde::tallenna($params['isa'], $rekisterinumero, 'isa');
	

    }
   


  public static function poisto($rekisterinumero){ //koiran poisto
    
    //self::check_logged_in();
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

  public static function hyvaksy($rekisterinumero) { //koiran hyväksyntä

        $koira = array(
            'rekisterinumero' => $rekisterinumero,
	     'tila' => 'valmis'
	    
        );
        
	$koir = new Koira($koira);
  	//$virheet = $koir->errors();
	//Kint::dump($virheet);

	 //if(count($virheet) > 0){
		//$rodut = Rotu::haekaikki();
		//$kasvattajat = Kasvattaja::haekaikki();
    		//View::make('koira_luonti.html', array('virheet' => $virheet, 'koira' => $koira,'rodut' => $rodut, 'kasvattajat' => $kasvattajat));
         //}
	 //else{
		$koir->hyvaksyHylkaa();
        	Redirect::to('/koira', array('viesti' => 'Koira on hyväksytty.'));
		
  	 //}

    }

   public static function hylkaa($rekisterinumero) { //koiran hylkäys

         $koira = array(
            'rekisterinumero' => $rekisterinumero,
	     'tila' => 'hylatty'
	    
        );
        
	$koir = new Koira($koira);
  	//$virheet = $koir->errors();
	//Kint::dump($virheet);

	 //if(count($virheet) > 0){
		//$rodut = Rotu::haekaikki();
		//$kasvattajat = Kasvattaja::haekaikki();
    		//View::make('koira_luonti.html', array('virheet' => $virheet, 'koira' => $koira,'rodut' => $rodut, 'kasvattajat' => $kasvattajat));
         //}
	 //else{
		$koir->hyvaksyHylkaa();
        	Redirect::to('/koira', array('viesti' => 'Koira on hylätty.'));
		
  	 //}

    }

   

}