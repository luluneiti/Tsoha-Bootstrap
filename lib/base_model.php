<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validoitavat;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {

        $virheet = array();

        foreach ($this->validoitavat as $validoitava) {

            $vvirheet = $this->{$validoitava}();
	    if(count($vvirheet) > 0){
            	$virheet = array_merge($virheet, $vvirheet);
	    }
        }

        return $virheet;
    }

    public function tarkistaMjononPituus($mjono, $kentta, $pituus) {

        $virh1 = array();

        if ($this->{$mjono} == '' || $this->{$mjono} == null) {
            $virh1[] = $kentta . ' tieto ei saa olla tyhjä!'; // 
        }
        if (strlen($this->{$mjono}) < $pituus) {
            $virh1[] = $kentta . ' kentän pituuden tulee olla vähintään ' . $pituus; //
        }

        return $virh1;
    }

    public function tarkistaNumero($mjono, $kentta) {

        $virh2 = array();

        if ((bool) is_numeric($this->{$mjono}) == false) {
            $virh2[] = $kentta . ' ei ole numeerinen!';
        }

        return $virh2;
    }

    public function tarkistaPaivamaara($pvm, $kentta) {

        $virh3 = array();
      	//Kint::dump($this->{$pvm});
		if (preg_match('^(0?[1-9]|[12][0-9]|3[01])[ \/.-](0?[1-9]|1[012])[ \/.-](19|20)\d\d$^', $this->{$pvm})==false)  {
            $virh3[] = $kentta . ' ei ole validi!';
        }
       
        return $virh3;
    }

  
   public function tarkistaSapo($sapo, $kentta) {
	
	$virh4 = array();

	if (filter_var($this->{$sapo}, FILTER_VALIDATE_EMAIL)==false) {
  		 $virh4[] = $kentta . ' ei ole validi!';

	}

	return $virh4;

   }

    public function tarkistaPaivamaara2($eka, $toka, $kentta_eka, $kentta_toka) {

    $virh5 = array();

    $pvm1=date('d-m-Y', strtotime($this->{$eka}));
    $pvm2=date('d-m-Y', strtotime($this->{$toka}));

    
    if ($pvm1 > $pvm2) {
	$virh5[] = $kentta_eka . ' ei voi olla suurempi kuin ' . $kentta_toka;


    }

    return $virh5;


   }
}
