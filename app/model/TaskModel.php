<?php
require_once PATH."class/Task.php";
require_once "Database.php";

class TaskModel extends Database
{

    private $table = "task";

    public function __construct(){
    }

    public function create( int $userId, string $title, string $description, $status){

        $req= " INSERT INTO $this->table (userId, title, description,status )
                        VALUES  (:userId, :title, :description, :status)";
        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":status", $status);

        if ($stmt->execute()){
            return true;
        }
        return false ;
    }
    public function fetchAllTasks()
    {
        $req = $this->getBdd()->prepare("SELECT id, userId, title, description, creationDate, status FROM $this->table");
        $req->execute();

        return $req;

    }

    public function fetchTask(int $id)
    {
        $req = "SELECT id, userId, title, description, creationDate, status FROM $this->table WHERE id = ?";

        $stmt = $this->getBdd()->prepare( $req);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt;
    }

    public function deleteTaskBd(int $id){
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