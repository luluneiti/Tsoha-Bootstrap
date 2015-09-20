<?php

class Koira extends BaseModel {

    public $rekisterinumero, $nimi, $syntymapv, $kuolinpv, $kuolinsyy, $sukupuoli, $rekisterointipv, $eronnut, $rotu, $kasvattaja, $tila;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeKaikki() { //kaikki koirat
        $kysely = DB::connection()->prepare('SELECT a.rekisterinumero, a.nimi, a.syntymapv, a.kuolinpv, a.kuolinsyy, a.sukupuoli, 
a.rekisterointipv, a.eronnut, b.nimi as rotu, a.kasvattajatunnus, a.tila FROM Koira a inner join Rotu b  on a.rotutunnus=b.rotutunnus');
        $kysely->execute();

        $rivit = $kysely->fetchAll();

        $koirat = array();

        foreach ($rivit as $rivi) {

            $koirat[] = new Koira(array(
                'rekisterinumero' => $rivi['rekisterinumero'],
                'nimi' => $rivi['nimi'],
                'syntymapv' => $rivi['syntymapv'],
                'kuolinpv' => $rivi['kuolinpv'],
                'kuolinsyy' => $rivi['kuolinsyy'],
                'sukupuoli' => $rivi['sukupuoli'],
                'rekisterointipv' => $rivi['rekisterointipv'],
                'eronnut' => $rivi['eronnut'],
                'rotu' => $rivi['rotu'],
                'kasvattaja' => $rivi['kasvattajatunnus'],
                'tila' => $rivi['tila']
            ));
        }

        return $koirat;
    }


  

    public static function haeTunnuksella($rekisterinumero) { //koiran haku tunnuksella
        $kysely = DB::connection()->prepare('SELECT a.rekisterinumero, a.nimi, a.syntymapv, a.kuolinpv, a.kuolinsyy, a.sukupuoli, a.rekisterointipv, a.eronnut, b.nimi as rotu, c.nimi as kasvattaja, a.tila FROM Koira a inner join Rotu b on a.rotutunnus=b.rotutunnus inner join Kasvattaja c on a.kasvattajatunnus=c.kasvattajatunnus WHERE rekisterinumero = :rekisterinumero LIMIT 1');
        $kysely->execute(array('rekisterinumero' => $rekisterinumero));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $koira = new Koira(array(
                'rekisterinumero' => $rivi['rekisterinumero'],
                'nimi' => $rivi['nimi'],
                'syntymapv' => $rivi['syntymapv'],
                'kuolinpv' => $rivi['kuolinpv'],
                'kuolinsyy' => $rivi['kuolinsyy'],
                'sukupuoli' => $rivi['sukupuoli'],
                'rekisterointipv' => $rivi['rekisterointipv'],
                'eronnut' => $rivi['eronnut'],
                'rotu' => $rivi['rotu'],
                'kasvattaja' => $rivi['kasvattaja'],
                'tila' => $rivi['tila']
            ));

            return $koira;
        }

        return null;
    }

    public function tallenna() {

        $query = DB::connection()->prepare('INSERT INTO Koira (nimi, rotutunnus, syntymapv, sukupuoli, kasvattajatunnus, rekisterointipv, tila) VALUES (:nimi, :rotu, :syntymapv, :sukupuoli, :kasvattajatunnus, :rekisterointipv, :tila) RETURNING rekisterinumero');

        $query->execute(array('rotu' => $this->rotu, 'kasvattajatunnus' => $this->kasvattaja, 'nimi' => $this->nimi, 'syntymapv' => $this->syntymapv, 'sukupuoli' => $this->sukupuoli, 'rekisterointipv' => $this->rekisterointipv, 'tila' => $this->tila ));

        $row = $query->fetch();

        //Kint::trace();
        //Kint::dump($row);

        $this->rekisterinumero = $row['rekisterinumero'];
    }

      public static function haeSukupuolet() { //kaikki sukupuolen arvot
     
      
        $spuolet = array('N', 'U');

        return $spuolet;
    }    

    public static function haeOmistajanTunnuksella($omistajatunnus) { //kaikki omistajan koirat --> omistajasuhteeseen!
        $kysely = DB::connection()->prepare('SELECT * FROM Koira a, Omistajasuhde b INNER JOIN Omistajasuhde ON koiratunnus=rekisterinumero WHERE omistajatunnus= :omistajatunnus LIMIT 1');
        $kysely->execute();

        $rivit = $kysely->fetchAll();

        $koirat = array();

        foreach ($rivit as $rivi) {

            $koirat[] = new Koira(array(
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

        return $koirat;
    }

    public static function haeNimella($nimi) { //kaikki koirat, joiden koko nimi tai nimen osa täsmää parametriin
        $kysely = DB::connection()->prepare('SELECT * FROM Koira WHERE nimi like ' % $nimi % ' LIMIT 1');
        $kysely->execute();

        $rivit = $kysely->fetchAll();

        $koirat = array();

        foreach ($rivit as $rivi) {

            $koirat[] = new Koira(array(
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
	return $koirat;
        }
        return null;
    }

}
