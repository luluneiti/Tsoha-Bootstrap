<?php
   require 'app/models/koira.php';
   class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
	echo 'Tämä on etusivu!';
    }

    public static function sandbox(){

     	$doom = new Koira(array(
            'rotu' => 1, 
            'kasvattaja' => 1,
            'nimi' => 'joo',
            'syntymapv' => '88.99.2015',
            'sukupuoli' => 'N',
            'rekisterointipv' => '2015-09-18', 
            'tila' => 'kesken'
        ));

  	$errors = $doom->errors();

  	Kint::dump($errors);
        
    }

    public static function kirjaudu(){
      View::make('suunnitelmat/lokkaus.html');
    }

    public static function koira_listaus(){
       View::make('suunnitelmat/koira_listaus.html');
    }

    public static function koira_esittely(){
      View::make('suunnitelmat/koira_esittely.html');
    }

    public static function koira_luonti(){
      View::make('suunnitelmat/koira_luonti.html');
    }

    public static function koira_muokkaus(){
      View::make('suunnitelmat/koira_muokkaus.html');
    }

    public static function koiraHyvaksynta_listaus(){
      View::make('suunnitelmat/koiraHyvaksynta_listaus.html');
    }

    public static function koiraHyvaksynta_esittely(){
      View::make('suunnitelmat/koiraHyvaksynta_esittely.html');
    }

    public static function koeNayttely_listaus(){
      View::make('suunnitelmat/koeNayttely_listaus.html');
    }

    public static function koeNayttely_luonti(){
      View::make('suunnitelmat/koeNayttely_luonti.html');
    }

    public static function koeNayttely_muokkaus(){
      View::make('suunnitelmat/koeNayttely_muokkaus.html');
    }
    
    public static function koeNayttelyTulos_luonti(){
      View::make('suunnitelmat/koeNayttelyTulos_luonti.html');
    }
 
    public static function koeNayttelyTulos_muokkaus(){
      View::make('suunnitelmat/koeNayttelyTulos_muokkaus.html');
    }


}
