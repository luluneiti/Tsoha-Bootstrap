<?php

class Kayttaja extends BaseModel { //tätä luokkaa käytetään vain kirjautumiseen ja lupakyselyihin

    public $tunnus, $kayttajatunnus, $salasana, $rooli, $nimi, $osoite, $luontipv, $alkupv, $loppupv;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validoitavat = array('validoiNimi', 'validoiKayttajatunnus1', 'validoiKayttajatunnus2', 'validoiSalasana', 'validoiOsoite');
    }

    public function validoiNimi() {

        return BaseModel::tarkistaMjononPituus('nimi', 'Nimi', 5);
    }

    public function validoiOsoite() {

        return BaseModel::tarkistaMjononPituus('osoite', 'Osoite', 10);
    }

    public function validoiKayttajatunnus1() {

        return BaseModel::tarkistaMjononPituus('kayttajatunnus', 'Kayttajatunnus', 6);
    }

    public function validoiKayttajatunnus2() {

        return BaseModel::tarkistaSapo('kayttajatunnus', 'Kayttajatunnus'); //säpo
    }

    public function validoiSalasana() {

        return BaseModel::tarkistaMjononPituus('salasana', 'Salasana', 8);
    }

    public static function tarkistaOikeudet($kayttajatunnus, $salasana) {

        $tanaan = date('Y-m-d');
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajatunnus = :kayttajatunnus AND salasana= :salasana AND loppupv is null OR loppupv > :tanaan LIMIT 1');
        $kysely->execute(array('kayttajatunnus' => $kayttajatunnus, 'salasana' => $salasana, 'tanaan' => $tanaan));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'tunnus' => $rivi['tunnus'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'rooli' => $rivi['rooli'],
                'nimi' => $rivi['nimi']
            ));


            return $kayttaja;
        } else {

            return null;
        }
    }

    public static function haeKTunnuksella($kayttajatunnus) { //kayttajan haku kayttajatunnuksella
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajatunnus = :kayttajatunnus');
        $kysely->execute(array('kayttajatunnus' => $kayttajatunnus));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'tunnus' => $rivi['tunnus'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'rooli' => $rivi['rooli'],
                'nimi' => $rivi['nimi'],
                'loppupv' => $rivi['loppupv']
            ));

            return $kayttaja;
        }

        return null;
    }

    public static function haeTunnuksella($tunnus) { //kayttajan haku tunnuksella
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE tunnus = :tunnus');
        $kysely->execute(array('tunnus' => $tunnus));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'tunnus' => $rivi['tunnus'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'rooli' => $rivi['rooli'],
                'osoite' => $rivi['osoite'],
                'nimi' => $rivi['nimi'],
                'loppupv' => $rivi['loppupv']
            ));

            return $kayttaja;
        }

        return null;
    }

    public function tallenna() { //päivämäärät tulee controller-luokalta
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, osoite, luontipv, alkupv, kayttajatunnus, salasana, rooli) VALUES (:nimi, :osoite, :luontipv, :alkupv, :kayttajatunnus, :salasana, :rooli) RETURNING tunnus');
        $query->execute(array('nimi' => $this->nimi, 'osoite' => $this->osoite, 'luontipv' => $this->luontipv, 'alkupv' => $this->alkupv, 'kayttajatunnus' => $this->kayttajatunnus, 'salasana' => $this->salasana, 'rooli' => $this->rooli));
        $row = $query->fetch();

        $this->tunnus = $row['tunnus'];
    }

    public function paivita() {

        $kysely = DB::connection()->prepare('update kayttaja set nimi=:nimi, osoite=:osoite, salasana=:salasana where tunnus=:tunnus RETURNING tunnus');
        $kysely->execute(array('nimi' => $this->nimi, 'osoite' => $this->osoite, 'salasana' => $this->salasana, 'tunnus' => $this->tunnus));
        $rivi = $kysely->fetch();

        $this->tunnus = $rivi['tunnus'];
    }

    public function poista() {

        $kysely = DB::connection()->prepare('update kayttaja set loppupv=:loppupv where tunnus=:tunnus RETURNING tunnus');
        $kysely->execute(array('tunnus' => $this->tunnus, 'loppupv' => $this->loppupv));
        $rivi = $kysely->fetch();

        $this->tunnus = $rivi['tunnus'];
    }

}
