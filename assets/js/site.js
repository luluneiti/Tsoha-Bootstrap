$(document).ready(function(){
  //alert('Hello World!');
});


// Kun sivu on latautunut kutsutaan ready-funktion parametrina annettua funktiota
$(document).ready(function(){
  // Valitaan kaikki form-elementit, joihin liittyy destroy-form-luokka ja lis‰t‰‰n niihin kuuntelija, joka kutsuu parametrina annettua funktiota, kun lomake l‰hetet‰‰n
  $('form.destroy-form').on('submit', function(submit){
    // Otetaan kohdelomakkeesta data-confirm-attribuutin arvo
    var confirm_message = $(this).attr('data-confirm');
    // Pyydet‰‰n k‰ytt‰j‰lt‰ vahvistusta
    if(!confirm(confirm_message)){
      // Jos k‰ytt‰j‰ ei anna vahvistusta, ei l‰hetet‰ lomaketta
      submit.preventDefault();
    }
  });
});