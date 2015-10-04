<?php

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});


$routes->get('/kirjaudu', function() {
    KayttajaController::naytaKirjaudu();
});

$routes->post('/kirjaudu', function() {
    KayttajaController::kirjaudu();
});

$routes->post('/logout', function() {
    KayttajaController::logout();
});


$routes->get('/', function() {
    KoiraController::listaaOmat();
});

$routes->get('/koira', function() {
    KoiraController::listaaOmat();
});

$routes->post('/koira', function() {
    KoiraController::lisaa();
});

$routes->get('/koira/uusi', function() {
    KoiraController::naytaLisaa();
});


$routes->get('/koira/:rekisterinumero/muokkaa', function($rekisterinumero) {
    KoiraController::naytaMuuta($rekisterinumero);
});

$routes->post('/koira/:rekisterinumero/muokkaa', function($rekisterinumero) {
    KoiraController::paivitys($rekisterinumero);
});

$routes->post('/koira/:rekisterinumero/hyvaksy', function($rekisterinumero) {
    KoiraController::hyvaksy($rekisterinumero);
});

$routes->post('/koira/:rekisterinumero/hylkaa', function($rekisterinumero) {
    KoiraController::hylkaa($rekisterinumero);
});

$routes->post('/koira/:rekisterinumero/poista', function($rekisterinumero) {
    KoiraController::poisto($rekisterinumero);
});

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
