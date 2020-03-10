<?php
  class User {
    private $dbh;
    private $table_name = "users";

    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
 

    public function __construct($db) {
      $this->dbh = $db;
    }

    public function create() {
      $query = "INSERT INTO " 
               . $this->table_name . "
               (firstname, lastname, email, password) 
               VALUES 
               (:firstname, :lastname, :email, :password)
               ";

      $stmt = $this->dbh->prepare($query);

      $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
      
      $stmt->bindValue(':firstname', $this->firstname);
      $stmt->bindValue(':lastname', $this->lastname);
      $stmt->bindValue(':email', $this->email);
      $stmt->bindValue(':password', $password_hash);

      if($stmt->execute()) {
        return true;
      }

      return false;
    }

    public function emailExists() {
      $query = "SELECT
                id,
                firstname,
                lastname,
                password
                FROM
                " . $this->table_name . "
                WHERE 
                email = :email 
                LIMIT 0, 1
                ";

      $stmt = $this->dbh->prepare($query);

      $this->email = htmlspecialchars(strip_tags($this->email));

      $stmt->bindValue(':email', $this->email);

      $stmt->execute();

      $num = $stmt->rowCount();

      if($num > 0) {
        
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        $this->id = $row->id;
        $this->firstname = $row->firstname;
        $this->lastname = $row->lastname;
        $this->password = $row->password;

        return true;
      }

      return false;
    }

    // update a user record
    public function update(){
    
      // if password needs to be updated
      $password_set=!empty($this->password) ? ", password = :password" : "";

      // if no posted password, do not update the password
      $query = "UPDATE " . $this->table_name . "
              SET
                  firstname = :firstname,
                  lastname = :lastname,
                  email = :email
                  {$password_set}
              WHERE id = :id";

      // prepare the query
      $stmt = $this->dbh->prepare($query);

      // sanitize
      $this->firstname=htmlspecialchars(strip_tags($this->firstname));
      $this->lastname=htmlspecialchars(strip_tags($this->lastname));
      $this->email=htmlspecialchars(strip_tags($this->email));

      // bind the values from the form
      $stmt->bindParam(':firstname', $this->firstname);
      $stmt->bindParam(':lastname', $this->lastname);
      $stmt->bindParam(':email', $this->email);

      // hash the password before saving to database
      if(!empty($this->password)){
          $this->password=htmlspecialchars(strip_tags($this->password));
          $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
          $stmt->bindParam(':password', $password_hash);
      }

      // unique ID of record to be edited
      $stmt->bindValue(':id', $this->id);

      // execute the query
      if($stmt->execute()){
          return true;
      }

      return false;
    }

  }