<?php

   class KayttajaController extends BaseController{

    
   public static function naytaKirjaudu(){
      View::make('kirjaudu.html');
   }

   public static function kirjaudu(){
    $params = $_POST;

    $kayttaja = kayttaja::tarkistaOikeudet($params['kayttajatunnus'], $params['salasana']);

    if(!$kayttaja){
      View::make('kirjaudu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'kayttajatunnus' => $params['kayttajatunnus']));
    }else{
      $_SESSION['kayttaja'] = $kayttaja->kayttajatunnus;

      Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $kayttaja->nimi . '!'));
    }
  }

  public static function logout(){
    $_SESSION['kayttaja'] = null;
    Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
  }
}