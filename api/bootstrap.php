<?php
  // show error reporting
  error_reporting(E_ALL);

  // set your default time-zone
  date_default_timezone_set('Asia/Manila');

  // load config files
  include_once('config/global.php');

  // load class/objects
  spl_autoload_register(function($className) {
    require_once 'classes/'. $className .'.php';
  });