<?php

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});


$routes->get('/kirjaudu', function() {
    HelloWorldController::kirjaudu();
});

$routes->get('/', function() {
    KoiraController::listaaKaikki();
});

$routes->get('/koira', function() {
    KoiraController::listaaKaikki();
});

$routes->post('/koira', function() {
    KoiraController::lisaa();
});

$routes->get('/koira/uusi', function() {
    KoiraController::naytaLisaa();
});

//$routes->post('/koira/:rekisterinumero', function() {
  //  KoiraController::tallenna2($rekisterinumero);
//});

//$routes->get('/koira/:rekisterinumero/muokkaa', function() {
  //  KoiraController::naytaMuokkaa();
//});


$routes->get('/koira/:rekisterinumero', function($rekisterinumero) {
    KoiraController::esittely($rekisterinumero);
});



$routes->get('/koirahyvaksynta', function() {
    HelloWorldController::koiraHyvaksynta_listaus();
});

$routes->get('/koirahyvaksynta/1', function() {
    HelloWorldController::koiraHyvaksynta_esittely();
});

$routes->get('/koenayttely', function() {
    HelloWorldController::koeNayttely_listaus();
});

$routes->get('/koenayttelyluonti', function() {
    HelloWorldController::koeNayttely_luonti();
});

$routes->get('/koenayttelymuokkaus', function() {
    HelloWorldController::koeNayttely_muokkaus();
});

$routes->get('/koenayttelytulosluonti', function() {
    HelloWorldController::koeNayttelyTulos_luonti();
});

$routes->get('/koenayttelytulosmuokkaus', function() {
    HelloWorldController::koeNayttelyTulos_muokkaus();
});
