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
     
      $kvirheet = array();

      foreach($this->validoitavat as $validoitava){
		$metodin_nimi = $validoitava;
		echo $metodin_nimi;
		//$virheet=$metodin_nimi; 
		$virheet=$this->{$metodin_nimi};  
		$kvirheet = array_merge($kvirheet, $virheet);  
      }

      return $kvirheet;
    }

    public function tarkistaMjononPituus($mjono, $pituus) { //$kentta, 

	echo 'Hello World! 1';

	$virheet = array();
	  	if($mjono == '' || $mjono == null){
    		$virheet[] = 'Tieto ei saa olla tyhjä!';
  	}
  	if(strlen($this->mjono) < $pituus){
    		$virheet[] = 'Kentän pituuden tulee olla vähintään ' . $pituus;
  	}
	
  	return $virheet;

   }


   public function tarkistaPaivamaara($pvm, $kentta) { //huom: pitää tarkistaa myös että pp, kk, vv on numeroita ja sille oma metodi
	
	echo 'Hello World! 2';

	$kopio = explode('.', $pvm);
	$virheet = array();
	$virheet[]='täällä oltiin 2';

  	
	if((bool)checkdate($kopio[1], $kopio[0], $kopio[2])==false){
    		$virheet[] = $kentta +' päivämäärä ei ole validi!';
  	}  	

  	return $virheet;


   }

  }
