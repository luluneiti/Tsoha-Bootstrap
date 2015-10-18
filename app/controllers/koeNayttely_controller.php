<?php

class KoeNayttelyController extends BaseController {

    public static function listaaOmat() {

        self::check_logged_in();
        if (self::get_user_logged_in()->rooli == "kkirjaaja") {
            $koenayt = KoeNayttely::haeTunnuksella(self::get_user_logged_in()->tunnus);
        }


        View::make('koeNayttely/koeNayttely_listaus.html', array('tapahtumat' => $koenayt));
    }

    public static function naytaLisaa() { //nayta lisayslomake
        $tyypit = array('Koe', 'Näyttely');


        View::make('koeNayttely/koeNayttely_luonti.html', array('tyypit' => $tyypit));
    }

    public static function lisaa() { //kayttajan lisays
        $params = $_POST;
        Kint::dump($params);

        $koenayt = array(
            'nimi' => $params['nimi'],
            'paikkakunta' => $params['paikkakunta'],
            'alkupv' => $params['alkupv'],
            'loppupv' => $params['loppupv'],
            'tyyppi' => $params['tyyppi'],
            'alityyppi' => $params['alityyppi'],
            'tunnus' => $params['tunnus']
        );

        $kn = new KoeNayttely($koenayt);

        $virheet = $kn->errors();

        if (count($virheet) > 0) {

            $tyypit = array('Koe', 'Näyttely');

            View::make('koeNayttely/koeNayttely_luonti.html', array('virheet' => $virheet, 'tapahtuma' => $koenayt, 'tyypit' => $tyypit));
        } else {
            $kn->tallenna();

            Redirect::to('/koeNayttely', array('viesti' => 'Koe/näyttely on lisätty.'));
        }
    }

}
