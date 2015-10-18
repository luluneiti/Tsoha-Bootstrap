<?php

class KoiraController extends BaseController {

    public static function listaaKaikki() { //hae kaikki koirat tai nimellä tai nimen osalla
        $params = $_GET;
        $options = array();

        if (isset($params['haku'])) {
            $options['haku'] = $params['haku'];
            $koirat = Koira::haeKaikki($options);
        } else {
            $options['haku'] = '';
            $koirat = Koira::haekaikki($options);
        }
        if ($koirat == null) {

            View::make('koira/koira_haku.html', array('koirat' => $koirat, 'viesti' => 'Hakuehdolla ei loydy koiria!'));
        } else {
            View::make('koira/koira_haku.html', array('koirat' => $koirat));
        }
    }

    public static function listaaOmat() {

        self::check_logged_in();
        if (self::get_user_logged_in()->rooli == "omistaja") {
            $koirat = Koira::haeOmistajanTunnuksella(self::get_user_logged_in()->tunnus); //listaa omistajan koirat
            if ($koirat == null) {
                View::make('koira/koira_listaus.html', array('koirat' => $koirat, 'viesti' => 'Sinulla ei ole viela koiria!'));
            } else {
                View::make('koira/koira_listaus.html', array('koirat' => $koirat));
            }
        }
        if (self::get_user_logged_in()->rooli == "hoitaja") { //listaa hyväksyttävät koirat
            $koirat = Koira::haeHyvaksyttavat();
            if ($koirat == null) {
                View::make('koira/koira_listaus.html', array('koirat' => $koirat, 'viesti' => 'Ei hyvaksyttavia koiria!'));
            } else {
                View::make('koira/koira_listaus.html', array('koirat' => $koirat));
            }
        }
        if (self::get_user_logged_in()->rooli == "kkirjaaja") {

            Redirect::to("/koeNayttely");
        }
    }

    public static function esittely($rekisterinumero) { //hae yksi koira
        $suhteet = Omistajasuhde::haeSuhteet($rekisterinumero);
        $suku = Sukulaissuhde::haeVanhemmat($rekisterinumero);
        $tulokset = KoeNayttelyTulos::haeTulokset($rekisterinumero);
        $koira = Koira::haeTunnuksella($rekisterinumero);
        View::make('koira/koira_esittely.html', array('koira' => $koira, 'suhteet' => $suhteet, 'suku' => $suku, 'tulokset' => $tulokset));
    }

    public static function naytaLisaa() { //nayta lisayslomake
        $rodut = Rotu::haekaikki();
        $kasvattajat = Kasvattaja::haekaikki();
        $emat = Koira::haeEmotIsat('N', 0); //0 ei ole minkään koiran tunnus ja tässä kaikki nartut näy emoiksi
        $isat = Koira::haeEmotIsat('U', 0); //0 ei ole minkään koiran tunnus ja tässä kaikki urokset näy isaksi

        View::make('koira/koira_luonti.html', array('rodut' => $rodut, 'kasvattajat' => $kasvattajat, 'emat' => $emat, 'isat' => $isat));
    }

    public static function lisaa() { //koiran lisays 
        $params = $_POST;

        $tanaan = date('d-m-Y');

        $koira = array(
            'rotu' => $params['rotu'],
            'kasvattaja' => $params['kasvattaja'],
            'nimi' => $params['nimi'],
            'syntymapv' => $params['syntymapv'],
            'sukupuoli' => $params['sukupuoli'],
            'rekisterointipv' => $tanaan,
            'tila' => 'kesken'
        );

        $koir = new Koira($koira);

        $virheet = $koir->errors();

        if (count($virheet) > 0) { //virheitä tiedoissa
            $rodut = Rotu::haekaikki();
            $kasvattajat = Kasvattaja::haekaikki();
            $emat = Koira::haeEmotIsat('N', 0);
            $isat = Koira::haeEmotIsat('U', 0);
            View::make('koira/koira_luonti.html', array('virheet' => $virheet, 'koira' => $koira, 'rodut' => $rodut, 'kasvattajat' => $kasvattajat, 'emat' => $emat, 'isat' => $isat));
        } else { //ok
            $koir->tallenna();
            Omistajasuhde::tallenna(self::get_user_logged_in()->tunnus, $koir->rekisterinumero); //luodaan aina ensin vain kirjautuneen nimiin ja muuttaessa voi lisätä muita omistajia
            Sukulaissuhde::tallenna($params['ema'], $koir->rekisterinumero, 'ema');
            Sukulaissuhde::tallenna($params['isa'], $koir->rekisterinumero, 'isa');

            Redirect::to('/koira/' . $koir->rekisterinumero, array('viesti' => 'Koira on lisätty.'));
        }
    }

    public static function naytaMuuta($rekisterinumero) { //nayta editointilomake
        $koira = Koira::haeTunnuksella($rekisterinumero);
        $rodut = Rotu::haekaikki();
        $kasvattajat = Kasvattaja::haekaikki();
        $omistajat = Omistaja::haekaikki();
        $emat = Koira::haeEmotIsat('N', $rekisterinumero);
        $isat = Koira::haeEmotIsat('U', $rekisterinumero);

        View::make('koira/koira_muokkaus.html', array('koira' => $koira, 'rodut' => $rodut, 'kasvattajat' => $kasvattajat, 'emat' => $emat, 'isat' => $isat, 'omistajat' => $omistajat));
    }

    public static function paivitys($rekisterinumero) { //koiran editointi 
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
        $virheet = $koir->errors();

        if (count($virheet) > 0) { //tiedoissa virheitä
            $rodut = Rotu::haekaikki();
            $kasvattajat = Kasvattaja::haekaikki();
            $omistajat = Omistaja::haekaikki();
            $emat = Koira::haeEmotIsat('N', $rekisterinumero);
            $isat = Koira::haeEmotIsat('U', $rekisterinumero);
            View::make('koira/koira_muokkaus.html', array('virheet' => $virheet, 'koira' => $koira, 'rodut' => $rodut, 'kasvattajat' => $kasvattajat, 'emat' => $emat, 'isat' => $isat, 'omistajat' => $omistajat));
        } else { //ok
            $koir->paivita();
            $suhteet = Omistajasuhde::haeSuhteet($rekisterinumero);
            $suku = Sukulaissuhde::haeVanhemmat($rekisterinumero);

            if ($suhteet) {
                Omistajasuhde::poista($rekisterinumero); //poistetaan vanhat suhteet
            }
            if ($suku) {
                Sukulaissuhde::poista($rekisterinumero); //poistetaan vanhat suhteet
            }

            KoiraController::muutSuhteet($koir->rekisterinumero, $params); //luo uudet suhteet
            Redirect::to('/koira/' . $koir->rekisterinumero, array('viesti' => 'Koiran tietoja on muokattu onnistuneesti!'));
        }
    }

    public static function muutSuhteet($rekisterinumero, $params) {

        $omistajat = $params['omistajat'];

        foreach ($omistajat as $omist) { //päivitettäessä voi olla monta omistajaa
            Omistajasuhde::tallenna($omist, $rekisterinumero);
        }

        Sukulaissuhde::tallenna($params['ema'], $rekisterinumero, 'ema');
        Sukulaissuhde::tallenna($params['isa'], $rekisterinumero, 'isa');
    }

    public static function poisto($rekisterinumero) { //koiran poisto
        $koira = new Koira(array('rekisterinumero' => $rekisterinumero));

        $suhteet = Omistajasuhde::haeSuhteet($rekisterinumero);
        $suku = Sukulaissuhde::haeVanhemmat($rekisterinumero);
        $tulokset = KoeNayttelyTulos::haeTulokset($rekisterinumero);

        if ($suhteet) {
            Omistajasuhde::poista($rekisterinumero);  //poistetaan muut suhteet ensin
        }
        if ($suku) {
            Sukulaissuhde::poista($rekisterinumero);
        }
        if ($tulokset) {
            KoeNayttelyTulos::poista($rekisterinumero);
        }


        $koira->poista(); //ja sitten koira

        Redirect::to('/koira', array('viesti' => 'Koira on poistettu onnistuneesti!'));
    }

    public static function hyvaksy($rekisterinumero) { //koiran hyväksyntä
        $tila = 'valmis';
        Koira::hyvaksyHylkaa($rekisterinumero, $tila);
        Redirect::to('/koira', array('viesti' => 'Koira on hyväksytty.'));
    }

    public static function hylkaa($rekisterinumero) { //koiran hylkäys
        $tila = 'hylatty';
        Koira::hyvaksyHylkaa($rekisterinumero, $tila);
        Redirect::to('/koira', array('viesti' => 'Koira on hylätty.'));
    }

}
