<?php
  define('APIROOT', dirname(dirname(__FILE__)));
  define('SITENAME', 'API-SAMPLE');
  define('URLROOT', 'http://rest-api-jwt/api');
  define('INIT', 'http://rest-api-jwt/api/bootstrap.php');

  // variables used for jwt
  $key = "example_key";
  $iss = "http://example.org";
  $aud = "http://example.com";
  $iat = 1356999524;
  $nbf = 1357000000;