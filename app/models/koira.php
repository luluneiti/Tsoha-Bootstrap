<?php

class Koira extends BaseModel {

    public $rekisterinumero, $nimi, $syntymapv, $kuolinpv, $kuolinsyy, $sukupuoli, $rekisterointipv, $eronnut, $rotu, $kasvattaja, $tila;

    public function __construct($attributes) {

        parent::__construct($attributes);

	$this->validoitavat = array('validoiNimi','validoiSyntymapv');
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
        $kysely = DB::connection()->prepare('SELECT a.rekisterinumero, a.nimi, a.syntymapv, a.kuolinpv, a.kuolinsyy, a.sukupuoli, a.rekisterointipv, a.eronnut,  b.nimi as rotu, c.nimi as kasvattaja, a.tila FROM Koira a inner join Rotu b on a.rotutunnus=b.rotutunnus inner join Kasvattaja c on a.kasvattajatunnus=c.kasvattajatunnus WHERE rekisterinumero = :rekisterinumero LIMIT 1');
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

   public static function haeHyvaksyttavat() { //kesken olevat koirat

        $kysely = DB::connection()->prepare("SELECT a.rekisterinumero, a.nimi, a.syntymapv, a.kuolinpv, a.kuolinsyy, a.sukupuoli, 
a.rekisterointipv, a.eronnut, b.nimi as rotu, a.kasvattajatunnus, a.tila FROM Koira a, Rotu b  where a.rotutunnus=b.rotutunnus and a.tila='kesken' ");
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


    public function tallenna() { //REKISTEROINTIPV JA TILA SUORAAN INSERTTIN

        $query = DB::connection()->prepare('INSERT INTO Koira (nimi, rotutunnus, syntymapv, sukupuoli, kasvattajatunnus, rekisterointipv, tila) 
VALUES (:nimi, :rotu, :syntymapv, :sukupuoli, :kasvattajatunnus, :rekisterointipv, :tila) RETURNING rekisterinumero');

        $query->execute(array('rotu' => $this->rotu, 'kasvattajatunnus' => $this->kasvattaja, 'nimi' => $this->nimi, 'syntymapv' => $this->syntymapv, 'sukupuoli' => $this->sukupuoli, 'rekisterointipv' => $this->rekisterointipv, 'tila' => $this->tila ));

        $row = $query->fetch();

        //Kint::trace();
        //Kint::dump($row);

        $this->rekisterinumero = $row['rekisterinumero'];
    }


   public function paivita() {

        $kysely = DB::connection()->prepare('update Koira set rotutunnus=:rotu, kasvattajatunnus=:kasvattaja, nimi=:nimi, syntymapv=:syntymapv, sukupuoli=:sukupuoli 
 where rekisterinumero=:rekisterinumero RETURNING rekisterinumero');

        $kysely->execute(array('rekisterinumero' => $this->rekisterinumero, 'rotu' => $this->rotu, 'kasvattaja' => $this->kasvattaja, 'nimi' => $this->nimi, 
'syntymapv' => $this->syntymapv, 'sukupuoli' => $this->sukupuoli));

        $rivi = $kysely->fetch();

        Kint::dump($rivi);

        $this->rekisterinumero = $rivi['rekisterinumero'];
    }

public function hyvaksyHylkaa() {

        $kysely = DB::connection()->prepare('update koira set tila=:tila where rekisterinumero=:rekisterinumero RETURNING rekisterinumero');

        $kysely->execute(array('rekisterinumero' => $this->rekisterinumero, 'tila' => $this->tila ));
        $rivi = $kysely->fetch();

        //Kint::trace();
        Kint::dump($rivi);

        $this->rekisterinumero = $rivi['rekisterinumero'];
    }

   public function poista() {

        $kysely = DB::connection()->prepare('delete from koira where rekisterinumero=:rekisterinumero RETURNING rekisterinumero');

         $kysely->execute(array('rekisterinumero' => $this->rekisterinumero));
        $rivi = $kysely->fetch();

        //Kint::trace();
        Kint::dump($rivi);

        $this->rekisterinumero = $rivi['rekisterinumero'];
    }


 

      public static function haeSukupuolet() { //kaikki sukupuolen arvot
     
      
        $spuolet = array('N', 'U'); //tuota täällä N, narttu; U, uros taulukko

        return $spuolet;
    }    

 public static function haeOmistajanTunnuksella($omistajatunnus) { //kaikki omistajan koirat 

        $kysely = DB::connection()->prepare('SELECT * FROM Koira a, Omistajasuhde b WHERE a.rekisterinumero=b.koiratunnus AND omistajatunnus=:omistajatunnus');
        $kysely->execute(array('omistajatunnus' => $omistajatunnus));

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

    public function validoiNimi() {

    	BaseModel::tarkistaMjononPituus('nimi','Nimi', 5);

    }

    public function validoiSyntymapv() {

    	BaseModel::tarkistaPaivamaara('syntymapv', 'Syntymäpäivä');

    }

    public static function haeEmotIsa($sukupuoli) { //kaikki potentiaaliset emot tai isät

        $kysely = DB::connection()->prepare('SELECT * from koira WHERE sukupuoli=:sukupuoli'); //jatkossa myös rekisterinumero<>:rekisterinumero, eli ei itse

        $kysely->execute(array('sukupuoli' => $sukupuoli)); //'rekisterinumero' => $rekisterinumero,

        $rivit = $kysely->fetchAll();

	$vanh=array();

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
   

}
