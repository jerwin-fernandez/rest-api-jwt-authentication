<?php
  define('APIROOT', dirname(dirname(__FILE__)));
  define('SITENAME', 'API-SAMPLE');
  define('URLROOT', 'http://rest-api-jwt/api');
  define('INIT', 'http://rest-api-jwt/api/bootstrap.php');

  // variables used for jwt
  $key = "";
  $iss = "";
  $aud = "";
  $iat = 0;
  $nbf = 0;
