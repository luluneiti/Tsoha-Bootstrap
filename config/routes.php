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
    KayttajaController::kirjauduUlos();
});

$routes->get('/hae', function() {
    KoiraController::listaaKaikki();
});


$routes->get('/', function() {
    KoiraController::listaaOmat();
});

$routes->get('/koira', function() {
    KoiraController::listaaOmat();
});

$routes->get('/koeNayttely', function() {
    KoeNayttelyController::listaaOmat();
});

$routes->get('/koeNayttelyT', function() {
    KoeNayttelyTulosController::listaa();
});

$routes->post('/koira', function() {
    KoiraController::lisaa();
});

$routes->get('/koira/uusi', function() {
    KoiraController::naytaLisaa();
});


$routes->post('/kayttaja', function() {
    KayttajaController::lisaa();
});

$routes->get('/kayttaja/uusi', function() {
    KayttajaController::naytaLisaa();
});



$routes->post('/koeNayttely', function() {
    KoeNayttelyController::lisaa();
});

$routes->get('/koeNayttely/uusi', function() {
    KoeNayttelyController::naytaLisaa();
});


$routes->get('/koeNayttelyT/:tapahtumatunnus/lisaa', function($tapahtumatunnus) {
    KoeNayttelyTulosController::naytaLisaa($tapahtumatunnus);
});


$routes->post('/koeNayttelyT/:tapahtumatunnus/lisaa', function($tapahtumatunnus) {
    KoeNayttelyTulosController::lisaa($tapahtumatunnus);
});


$routes->get('/kayttaja/:tunnus/muokkaa', function($tunnus) {
    KayttajaController::naytaMuuta($tunnus);
});

$routes->post('/kayttaja/:tunnus/muokkaa', function($tunnus) {
    KayttajaController::paivitys($tunnus);
});

$routes->post('/kayttaja/:tunnus/poista', function($tunnus) {
    KayttajaController::poisto($tunnus);
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

$routes->get('/kayttaja/:tunnus', function($tunnus) {
    KayttajaController::esittely($tunnus);
});




//ei toteutettu


$routes->get('/koenayttelymuokkaus', function() {
    HelloWorldController::koeNayttely_muokkaus();
});


$routes->get('/koenayttelytulosmuokkaus', function() {
    HelloWorldController::koeNayttelyTulos_muokkaus();
});
