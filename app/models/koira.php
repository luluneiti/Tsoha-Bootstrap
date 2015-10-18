<?php

class Koira extends BaseModel {

    public $rekisterinumero, $nimi, $syntymapv, $kuolinpv, $kuolinsyy, $sukupuoli, $rekisterointipv, $eronnut, $rotu, $kasvattaja, $tila;

    public function __construct($attributes) {

        parent::__construct($attributes);

        $this->validoitavat = array('validoiNimi', 'validoiSyntymapv', 'validoiRotu', 'validoiKasvattaja');
    }

    public function validoiNimi() {

        return BaseModel::tarkistaMjononPituus('nimi', 'Nimi', 5);
    }

    public function validoiSyntymapv() {

        return BaseModel::tarkistaPaivamaara('syntymapv', 'Syntymapaiva');
    }

    public function validoiRotu() {

        $virh = array();

        if (Rotu::haeTunnuksella($this->rotu) == null) {
            $virh[] = 'Rotu ei ole sallittu!';
        }

        return $virh;
    }

    public function validoiKasvattaja() {

        $virh = array();

        if (Kasvattaja::haeTunnuksella($this->kasvattaja) == null) {
            $virh[] = 'Kasvattaja ei ole sallittu!';
        }

        return $virh;
    }

    public function validoiTila() {

        $virh = array();
        $saltilat = array("kesken", "hylatty", "valmis");

        if (in_array($this->tila, $saltilat) == false) {
            $virh[] = 'Tila ei ole sallittu!';
        }

        return $virh;
    }

    public static function haeKaikki($options) { //hae kaikki koirat tai koirat, joiden nimi tai nimen osa täsmää option parametriin

        $kysely_mjono = 'SELECT a.rekisterinumero, a.nimi, a.syntymapv, a.kuolinpv, a.kuolinsyy, a.sukupuoli, a.rekisterointipv, a.eronnut, b.nimi as rotu, a.kasvattajatunnus, a.tila FROM Koira a, Rotu b  where a.rotutunnus=b.rotutunnus';

        if ($options['haku'] != '') {
            $kysely_mjono .= " AND a.nimi LIKE :like";
            $options['like'] = "%" . $options['haku'] . "%";

            $kysely = DB::connection()->prepare($kysely_mjono);
            $kysely->execute(array('like' => $options['like']));
        } else {
            $kysely = DB::connection()->prepare($kysely_mjono);
            $kysely->execute();
        }

        $rivit = $kysely->fetchAll();

        $koirat = array();

        foreach ($rivit as $rivi) {

            $pvm1 = date('d-m-Y', strtotime($rivi['syntymapv']));
            $pvm2 = date('d-m-Y', strtotime($rivi['rekisterointipv']));

            $koirat[] = new Koira(array(
                'rekisterinumero' => $rivi['rekisterinumero'],
                'nimi' => $rivi['nimi'],
                'syntymapv' => $pvm1,
                'kuolinpv' => $rivi['kuolinpv'],
                'kuolinsyy' => $rivi['kuolinsyy'],
                'sukupuoli' => $rivi['sukupuoli'],
                'rekisterointipv' => $pvm2,
                'eronnut' => $rivi['eronnut'],
                'rotu' => $rivi['rotu'],
                'kasvattaja' => $rivi['kasvattajatunnus'],
                'tila' => $rivi['tila']
            ));
        }

        return $koirat;
    }

    public static function haeTunnuksella($rekisterinumero) { //koiran haku tunnuksella
        $kysely = DB::connection()->prepare('SELECT a.rekisterinumero, a.nimi, a.syntymapv, a.kuolinpv, a.kuolinsyy, a.sukupuoli, a.rekisterointipv, a.eronnut,  b.nimi as rotu, c.nimi as kasvattaja, a.tila FROM Koira a inner join Rotu b on a.rotutunnus=b.rotutunnus inner join Kasvattaja c on a.kasvattajatunnus=c.kasvattajatunnus WHERE rekisterinumero = :rekisterinumero LIMIT 1');
        $kysely->execute(array('rekisterinumero' => $rekisterinumero));
        $rivi = $kysely->fetch();

        if ($rivi) {

            $pvm1 = date('d-m-Y', strtotime($rivi['syntymapv']));
            $pvm2 = date('d-m-Y', strtotime($rivi['rekisterointipv']));

            $koira = new Koira(array(
                'rekisterinumero' => $rivi['rekisterinumero'],
                'nimi' => $rivi['nimi'],
                'syntymapv' => $pvm1,
                'kuolinpv' => $rivi['kuolinpv'],
                'kuolinsyy' => $rivi['kuolinsyy'],
                'sukupuoli' => $rivi['sukupuoli'],
                'rekisterointipv' => $pvm2,
                'eronnut' => $rivi['eronnut'],
                'rotu' => $rivi['rotu'],
                'kasvattaja' => $rivi['kasvattaja'],
                'tila' => $rivi['tila']
            ));

            return $koira;
        }
	else {
        return null; }
    }

    public static function haeHyvaksyttavat() { //kesken olevat koirat
        $kysely = DB::connection()->prepare("SELECT a.rekisterinumero, a.nimi, a.syntymapv, a.kuolinpv, a.kuolinsyy, a.sukupuoli, a.rekisterointipv, a.eronnut, b.nimi as rotu, a.kasvattajatunnus, a.tila FROM Koira a, Rotu b  where a.rotutunnus=b.rotutunnus and a.tila='kesken' ");
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $koirat = array();

        foreach ($rivit as $rivi) {

            $pvm1 = date('d-m-Y', strtotime($rivi['syntymapv']));
            $pvm2 = date('d-m-Y', strtotime($rivi['rekisterointipv']));

            $koirat[] = new Koira(array(
                'rekisterinumero' => $rivi['rekisterinumero'],
                'nimi' => $rivi['nimi'],
                'syntymapv' => $pvm1,
                'kuolinpv' => $rivi['kuolinpv'],
                'kuolinsyy' => $rivi['kuolinsyy'],
                'sukupuoli' => $rivi['sukupuoli'],
                'rekisterointipv' => $pvm2,
                'eronnut' => $rivi['eronnut'],
                'rotu' => $rivi['rotu'],
                'kasvattaja' => $rivi['kasvattajatunnus'],
                'tila' => $rivi['tila']
            ));
        }

        return $koirat;
    }

    public static function haeOmistajanTunnuksella($omistajatunnus) { //kaikki tietyn omistajan koirat 
        $kysely = DB::connection()->prepare('SELECT * FROM Koira a, Omistajasuhde b WHERE a.rekisterinumero=b.koiratunnus AND omistajatunnus=:omistajatunnus');
        $kysely->execute(array('omistajatunnus' => $omistajatunnus));
        $rivit = $kysely->fetchAll();
        
        $koirat = array();

        foreach ($rivit as $rivi) {

            $pvm1 = date('d-m-Y', strtotime($rivi['syntymapv']));
            $pvm2 = date('d-m-Y', strtotime($rivi['rekisterointipv']));


            $koirat[] = new Koira(array(
                'rekisterinumero' => $rivi['rekisterinumero'],
                'nimi' => $rivi['nimi'],
                'syntymapv' => $pvm1,
                'kuolinpv' => $rivi['kuolinpv'],
                'kuolinsyy' => $rivi['kuolinsyy'],
                'sukupuoli' => $rivi['sukupuoli'],
                'rekisterointipv' => $pvm2,
                'eronnut' => $rivi['eronnut'],
                'rotu' => $rivi['rotutunnus'],
                'kasvattaja' => $rivi['kasvattajatunnus'],
                'tila' => $rivi['tila']
            ));
        }

        return $koirat;
    }

    public static function haeEmotIsat($sukupuoli, $rekisterinumero) { //kaikki potentiaaliset emot tai isät
        $kysely = DB::connection()->prepare('SELECT * from koira WHERE sukupuoli=:sukupuoli and rekisterinumero!=:rekisterinumero'); //emo tai isä ei saa olla koira itse
        $kysely->execute(array('sukupuoli' => $sukupuoli, 'rekisterinumero' => $rekisterinumero));
        $rivit = $kysely->fetchAll();

        $vanh = array();

        foreach ($rivit as $rivi) {

            $vanh[] = new Koira(array(
                'rekisterinumero' => $rivi['rekisterinumero'],
                'nimi' => $rivi['nimi'],
                'syntymapv' => $rivi['syntymapv'],
                'kuolinpv' => $rivi['kuolinpv'],
                'kuolinsyy' => $rivi['kuolinsyy'],
                'sukupuoli' => $rivi['sukupuoli'],
                'rekisterointipv' => $rivi['rekisterointipv'],
                'eronnut' => $rivi['eronnut'],
                'rotu' => $rivi['rotutunnus'],
                'kasvattaja' => $rivi['kasvattajatunnus'],
                'tila' => $rivi['tila']
            ));
        }
        return $vanh;
    }

    public function tallenna() { //rekisterointipv ja tila tulee controller-luokalta
        $query = DB::connection()->prepare('INSERT INTO Koira (nimi, rotutunnus, syntymapv, sukupuoli, kasvattajatunnus, rekisterointipv, tila) VALUES (:nimi, :rotu, :syntymapv, :sukupuoli, :kasvattajatunnus, :rekisterointipv, :tila) RETURNING rekisterinumero');
        $query->execute(array('rotu' => $this->rotu, 'kasvattajatunnus' => $this->kasvattaja, 'nimi' => $this->nimi, 'syntymapv' => $this->syntymapv, 'sukupuoli' => $this->sukupuoli, 'rekisterointipv' => $this->rekisterointipv, 'tila' => $this->tila));
        $row = $query->fetch();

        $this->rekisterinumero = $row['rekisterinumero'];
    }

    public function paivita() {

        $kysely = DB::connection()->prepare('update Koira set rotutunnus=:rotu, kasvattajatunnus=:kasvattaja, nimi=:nimi, syntymapv=:syntymapv, sukupuoli=:sukupuoli where rekisterinumero=:rekisterinumero RETURNING rekisterinumero');
        $kysely->execute(array('rekisterinumero' => $this->rekisterinumero, 'rotu' => $this->rotu, 'kasvattaja' => $this->kasvattaja, 'nimi' => $this->nimi,
            'syntymapv' => $this->syntymapv, 'sukupuoli' => $this->sukupuoli));
        $rivi = $kysely->fetch();

        $this->rekisterinumero = $rivi['rekisterinumero'];
    }

    public function hyvaksyHylkaa($rekisterinumero, $tila) { //päivittää tilaksi valmis tai hylatty

        $kysely = DB::connection()->prepare('update koira set tila=:tila where rekisterinumero=:rekisterinumero RETURNING rekisterinumero');
        $kysely->execute(array('rekisterinumero' => $rekisterinumero, 'tila' => $tila));
        $rivi = $kysely->fetch();

        $rekisterinumero = $rivi['rekisterinumero'];
    }

    public function poista() {

        $kysely = DB::connection()->prepare('delete from koira where rekisterinumero=:rekisterinumero RETURNING rekisterinumero');
        $kysely->execute(array('rekisterinumero' => $this->rekisterinumero));
        $rivi = $kysely->fetch();

        $this->rekisterinumero = $rivi['rekisterinumero'];
    }

}
