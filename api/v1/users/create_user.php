<?php
// required headers

// The Access-Control-Allow-Origin response header indicates whether the response can be shared with requesting code from the given origin
header("Access-Control-Allow-Origin: *");
// The Content-Type entity header is used to indicate the media type of the resource.
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
// The Access-Control-Max-Age response header indicates how long the results of a preflight request (that is the information contained in the Access-Control-Allow-Methods and Access-Control-Allow-Headers headers) can be cached.
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// init all files in bootstrap.php
include_once('../../bootstrap.php');

// get database connection
$database = new Database();
$dbh = $database->getConnection();

// instantiate user
$user = new User($dbh);

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;
 
// use the create() method here

// create the user
if(
  !empty($user->firstname) &&
  !empty($user->email) &&
  !empty($user->password) &&
  $user->create()
){

  // set response code
  http_response_code(200);

  // display message: user was created
  echo json_encode(array("message" => "User was created."));
}

// message if unable to create user
else{

  // set response code
  http_response_code(400);

  // display message: unable to create user
  echo json_encode(array("message" => "Unable to create user."));
}