<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validoitavat;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){ 
	
      $virheet = array();

      foreach($this->validoitavat as $validoitava){
				
		$vvirheet=array($this->{$validoitava}());
		$virheet = array_merge($virheet, $vvirheet); 
		

      }

      Kint::dump($virheet);
      return $virheet;
    }

    public function tarkistaMjononPituus($mjono, $kentta, $pituus) { 

	$vvirheet = array();

	if($this->$mjono == '' || $this->$mjono == null){
		 $vvirheet[] = $kentta. ' tieto ei saa olla tyhjä!'; // 
  	}
  	if(strlen($this->$mjono) < $pituus){
    		 $vvirheet[] = $kentta. ' kentän pituuden tulee olla vähintään ' . $pituus;//
  	}

	Kint::dump($vvirheet);
	return $vvirheet;

   }

   public function tarkistaNumero($mjono, $kentta) {

	$vvirheet = array();

	if((bool)is_numeric($mjono)==false){
    		$vvirheet[] = $kentta. ' ei ole numeerinen!';
  	}  

	Kint::dump($vvirheet);
	return $vvirheet;

   }

   public function tarkistaPaivamaara($pvm, $kentta) { 

	$vvirheet = array();

  	if(!preg_match("/[0-9]{2}.[0-9]{2}.[0-9]{4}/", $this->$pvm)) {
    		$vvirheet[] = $kentta. ' ei ole validi!';
  	}  	
	//tarkista ettei ole tulevaisuudessa

	Kint::dump($vvirheet);
	return $vvirheet;

   }

 }
