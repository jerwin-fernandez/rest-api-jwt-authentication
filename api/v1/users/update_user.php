<?php
  // required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
  // database connection will be here
  include_once('../../bootstrap.php');

  // files for decoding jwt will be here
  include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
  include_once '../../libs/php-jwt-master/src/ExpiredException.php';
  include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
  include_once '../../libs/php-jwt-master/src/JWT.php';
  use \Firebase\JWT\JWT;

  // get database connection
  $database = new Database();
  $dbh = $database->getConnection();
  
  // instantiate user object
  $user = new User($dbh);
  
  // retrieve given jwt here
  // get posted data
  $data = json_decode(file_get_contents("php://input"));
  
  // get jwt
  $jwt=isset($data->jwt) ? $data->jwt : "";
  
  // decode jwt here
  // if jwt is not empty
  if($jwt){
  
    // if decode succeed, show user details
    try {

        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        // set user property values here
        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->id = $decoded->data->id;
        
        // update user will be here
        if($user->update()){
          // we need to re-generate jwt because user details might be different
          $token = array(
            "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat,
            "nbf" => $nbf,
            "data" => array(
                "id" => $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "email" => $user->email
            )
          );

          $jwt = JWT::encode($token, $key);

          // set response code
          http_response_code(200);

          // response in json format
          echo json_encode(
                array(
                    "message" => "User was updated.",
                    "jwt" => $jwt
                )
            );
      } else {
          // set response code
          http_response_code(401);
       
          // show error message
          echo json_encode(array("message" => "Unable to update user."));
      }
    } catch (Exception $e) {
  
      // set response code
      http_response_code(401);
  
      // show error message
      echo json_encode(array(
          "message" => "Access denied.",
          "error" => $e->getMessage()
      ));
    }

  }else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
}
