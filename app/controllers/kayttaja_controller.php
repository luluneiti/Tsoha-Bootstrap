<?php

class KayttajaController extends BaseController {

    public static function naytaKirjaudu() {
        View::make('kirjaudu.html');
    }

    public static function kirjaudu() {
        $params = $_POST;

        $kayttaja = kayttaja::tarkistaOikeudet($params['kayttajatunnus'], $params['salasana']);

        if (!$kayttaja) {
            View::make('kirjaudu.html', array('virheet' => 'Vaara kayttajatunnus tai salasana!'));
        } else {
            $_SESSION['kayttaja'] = $kayttaja->kayttajatunnus;

            Redirect::to('/', array('viesti' => 'Tervetuloa takaisin ' . $kayttaja->nimi . '!'));
        }
    }

    public static function kirjauduUlos() {
        $_SESSION['kayttaja'] = null;
        Redirect::to('/', array('viesti' => 'Olet kirjautunut ulos!'));
    }

    public static function naytaLisaa() { //nayta lisayslomake
        $roolit = KayttajaController::annaRoolit();
        View::make('kayttaja/kayttaja_luonti.html', array('roolit' => $roolit));
    }

    public static function annaRoolit() {

        /* if(self::get_user_logged_in()->rooli=="hoitaja"){ 	//mahdollistaisi kaikki roolit
          $roolit = array('omistaja', 'kkirjaaja', 'hoitaja');
          }
          else { */     //vain omistaja rooli
        $roolit = array('omistaja');

        //}
        return $roolit;
    }

    public static function esittely($tunnus) { //hae yksi kayttaja
        $kayttaja = Kayttaja::haeTunnuksella($tunnus);

        if ($kayttaja->rooli == "omistaja") {
            $omistaja = Omistaja::haeTunnuksella($tunnus);
            View::make('kayttaja/kayttaja_esittely.html', array('kayttaja' => $kayttaja, 'omistaja' => $omistaja));
        } else {
            View::make('kayttaja/kayttaja_esittely.html', array('kayttaja' => $kayttaja));
        }
    }

    public static function lisaa() { //kayttajan lisays
        $params = $_POST;

        $tanaan = date('d-m-Y');
        $kayttaja = array(
            'nimi' => $params['nimi'],
            'osoite' => $params['osoite'],
            'kayttajatunnus' => $params['kayttajatunnus'],
            'salasana' => $params['salasana'],
            'rooli' => $params['rooli'],
            'luontipv' => $tanaan,
            'alkupv' => $tanaan
        );

        $tark = Kayttaja::haeKTunnuksella($params['kayttajatunnus']);

        if ($tark != null) { //käyttäjä on jo
            $roolit = KayttajaController::annaRoolit();
            View::make('kayttaja/kayttaja_luonti.html', array('viesti' => 'Kayttajatunnus on varattu.', 'roolit' => $roolit, 'kayttaja' => $kayttaja));
        } else {


            $kayt = new Kayttaja($kayttaja);

            $virheet = $kayt->errors();

            if (count($virheet) > 0) { //jos virheitä tiedoissa
                $roolit = KayttajaController::annaRoolit();
                View::make('kayttaja/kayttaja_luonti.html', array('virheet' => $virheet, 'kayttaja' => $kayttaja, 'roolit' => $roolit));
            } else { //ok
                $kayt->tallenna();

		//Kint::dump($params['yhteystietojenNaytto'] );
                if ($params['yhteystietojenNaytto'] == "Ei") {
                      $tietNaytto = 'f';
           } else {
                    $tietNaytto = 't';
           }

                $omistaja = array(
                    'tunnus' => $kayt->tunnus,
                    'yhteystietojenNaytto' => $tietNaytto
                );

                $omis = new Omistaja($omistaja);
                $omis->tallenna();

                Redirect::to('/kayttaja/' . $kayt->tunnus, array('viesti' => 'Kayttaja on lisatty.'));
            }
        }
    }

    public static function naytaMuuta($tunnus) { //nayta editointilomake
        $kayttaja = Kayttaja::haeTunnuksella($tunnus);
        //$roolit = KayttajaController::annaRoolit();
        $omistaja = Omistaja::haeTunnuksella($tunnus);

        View::make('kayttaja/kayttaja_muokkaus.html', array('kayttaja' => $kayttaja,  'omistaja' => $omistaja)); 
    }

    public static function paivitys($tunnus) {

        $params = $_POST;
        $ka = Kayttaja::haeTunnuksella($tunnus);
        $kayttaja = array(
            'nimi' => $params['nimi'],
            'osoite' => $params['osoite'],
            'salasana' => $params['salasana'],
            'kayttajatunnus' => $ka->kayttajatunnus,
            'tunnus' => $tunnus
        );

        $kayt = new Kayttaja($kayttaja);
        $virheet = $kayt->errors();

        if (count($virheet) > 0) { //virheitä tiedoissa
            //$roolit = KayttajaController::annaRoolit();
            View::make('kayttaja/kayttaja_muokkaus.html', array('virheet' => $virheet, 'kayttaja' => $kayttaja)); 
        } else { //ok
            $kayt->paivita();

           if ($params['yhteystietojenNaytto'] == "Ei") {
                    $tietNaytto = 'f';
           } else {
                    $tietNaytto = 't';
           }

	   
            $omistaja = array(
                'tunnus' => $kayt->tunnus,
                'yhteystietojenNaytto' => $tietNaytto
            );

            $omis = new Omistaja($omistaja);
	    Kint::dump($omis);
            $omis->paivita();
 	    Redirect::to('/kayttaja/' . $kayt->tunnus, array('viesti' => 'Käyttäjän tietoja on muokattu onnistuneesti!'));
        }
    }

    public static function poisto($tunnus) { //kayttajan poisto
        $kayttaja = new Kayttaja(array('tunnus' => $tunnus, 'loppupv' => date('d-m-Y', strtotime("+1 day")))); //käyttäjä loppupäiväksi seuraava päivä

        $kayttaja->poista();

        Redirect::to('/kayttaja/' . $kayttaja->tunnus, array('viesti' => 'Tilisi sulkeutuu huomenna!'));
    }

}