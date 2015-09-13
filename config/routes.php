<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });


  $routes->get('/kirjaudu', function() {
    HelloWorldController::kirjaudu();
  });


  $routes->get('/koira', function() {
    HelloWorldController::koira_listaus();
  });

  $routes->get('/koira/1', function() {
    HelloWorldController::koira_esittely();
  });

  $routes->get('/koiraluonti', function() {
    HelloWorldController::koira_luonti();
  });

  $routes->get('/koiramuokkaus', function() {
    HelloWorldController::koira_muokkaus();
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