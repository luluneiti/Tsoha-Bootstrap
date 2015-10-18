<?php

class KoeNayttelyTulosController extends BaseController {

    public static function listaa() {

        self::check_logged_in();
        if (self::get_user_logged_in()->rooli == "kkirjaaja") {
            $tulokset = KoeNayttelyTulos::haeKaikki();
        }


        View::make('koeNayttelyTulos/koeNayttelyTulos_listaus.html', array('tulokset' => $tulokset));
    }

    public static function naytaLisaa($tapahtumatunnus) { //nayta lisayslomake
        $options['haku'] = '';
        $koirat = Koira::haekaikki($options);

        $tap = KoeNayttely::haeTunnuksella2($tapahtumatunnus);
        View::make('koeNayttelyTulos/koeNayttelyTulos_luonti.html', array('tap' => $tap, 'koirat' => $koirat));
    }

    public static function lisaa($tapahtumatunnus) { //kayttajan lisays
        $params = $_POST;
        Kint::dump($params);

        $koenayttulos = array(
            'tulos' => $params['tulos'],
            'tulospv' => $params['tulospv'],
            'tuloslisatieto' => $params['tuloslisatieto'],
            'koiratunnus' => $params['koiratunnus'],
            'tapahtumatunnus' => $tapahtumatunnus
        );

        $tulos = new KoeNayttelyTulos($koenayttulos);

        $virheet = $tulos->errors();

        if (count($virheet) > 0) {

            $options['haku'] = '';
            $koirat = Koira::haekaikki($options);
            View::make('koeNayttelyTulos/koeNayttelyTulos_luonti.html', array('virheet' => $virheet, 'tulos' => $tulos, 'koirat' => $koirat));

        } else {
            $tulos->tallenna();

            Redirect::to('/koeNayttely', array('viesti' => 'Koe/näyttelytulos on lisätty.'));
        }
    }

}
