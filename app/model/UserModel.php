<?php
require_once PATH."class/User.php";
require_once "Database.php";


class UserModel extends Database
{
    private  $table = "user";
    private  $task = "task";


    public function __construct(){
    }

    public function fetchAllUsers(){
        $req = $this->getBdd()->prepare("SELECT id, name, email FROM $this->table");
        $req->execute();

        return  $req;
    }

    public function create( string $name, string $email){

        $req= " INSERT INTO $this->table (name, email )
                        VALUES  (:name,:email)";
        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);

        if ($stmt->execute()){
            return true;
        }
        return false ;
    }

    public function fetchUser(int $id){
        $req = "SELECT id, name, email FROM $this->table WHERE id = ?";

        $stmt = $this->getBdd()->prepare( $req);
        $stmt->bindParam(1, $id);

        $stmt->execute();

        return $stmt;
    }

    public function fetchTasksByUser($id){

        $req = "SELECT task.id, title, userId, description, creationDate, status
                FROM $this->table INNER JOIN $this->task ON $this->task.userId = $this->table.id
                WHERE $this->table.id = ?";


        $stmt = $this->getBdd()->prepare( $req);
        $stmt->bindParam(1, $id);

        $stmt->execute();

        return $stmt;
    }


    public function deleteUserDb(int $id){
        $req = "DELETE FROM $this->table WHERE id = ?";

        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindParam(1, $id);

        if ($stmt->execute() && $stmt->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
}