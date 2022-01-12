<?php
include_once PATH.'model/UserModel.php';
include_once PATH.'class/JsonHelper.php';
include_once PATH.'class/User.php';
include_once PATH.'class/Task.php';


class UserController extends JsonHelper
{
    private $userModel;

    public function __construct(){

        $this->userModel = new UserModel();
    }

    public function getUsers(){

        $stmt = $this->userModel->fetchAllUsers();

        if ($stmt->rowCount() > 0){
            $arrayUsers = [];
            $arrayUsers['users'] = [];


            foreach ($row = $stmt->fetchAll(PDO::FETCH_ASSOC) as $i => $r){

                extract($row[$i]);

                $user = new User((int)$id, htmlspecialchars($name), htmlspecialchars($email));
                $users = [
                    "id" => $user->getId(),
                    "name" => $user->getName(),
                    "email" => $user->getEmail()
                ];
                $arrayUsers['users'][] = $users ;

                //Récupération des taches par user
                $stmtTasks = $this->userModel->fetchTasksByUser($user->getId());

                if ($stmtTasks->rowCount() > 0){

                    $tasks = $stmtTasks->fetchAll(PDO::FETCH_ASSOC);

                    $arrayTasks = [];

                    foreach ($tasks as $t){

                            $task = new Task((int)$t['id'], (int)$t['userId'],
                                htmlspecialchars($t['title']), htmlspecialchars($t['description']),
                                htmlspecialchars($t['creationDate']), htmlspecialchars($t['status']) );

                            $task =[
                                "id" => $task->getId(),
                                "userId" => $task->getUserId(),
                                "title" => $task->getTitle(),
                                "description" => $task->getDescription(),
                                "creationDate" => $task->getCreationDate(),
                                "status" => $task->getStatus(),
                            ];
                            $arrayTasks []= $task;

                    }
                    $arrayUsers['users'][$i]['tasks'] = $arrayTasks;
                }
            }
            $this->getResponseAndDatas(200, $arrayUsers);

        }else{

            $this->getMessageAndCode(503, "Aucun utilisateur n'a été trouvé!!");
        }

    }

    public function getUser(int $id){

        $stmt = $this->userModel->fetchUser($id);
        //Récupère les tâches de l'utilisateur
        $stmtTasks = $this->userModel->fetchTasksByUser($id);
        if ($stmt->rowCount() > 0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $u = new User($row['id'], htmlspecialchars($row['name']), htmlspecialchars($row['email']));

            $user['user'] = [
                "id" => $u->getId(),
                "name" => $u->getName(),
                "email" => $u->getEmail()
            ];


            // Si des correspondances sont trouvées entre user et task, un nouveau tableau est ajouté
            if ($stmtTasks->rowCount() > 0){
                $rows = $stmtTasks->fetchAll(PDO::FETCH_ASSOC);

                $arrayTasks = [];

                foreach ($rows as $r){

                    $task = new Task((int)$r['id'], (int)$r['userId'],
                        htmlspecialchars($r['title']), htmlspecialchars($r['description']),
                        date("d-m-Y",strtotime($r['creationDate'])), htmlspecialchars($r['status']) );

                    $user['user']['tasks'] []= [
                      "id" => $task->getId(),
                      "title" =>  $task->getTitle(),
                      "description" =>  $task->getDescription(),
                      "creationDate" =>  $task->getCreationDate(),
                      "status" =>  $task->getStatus()
                    ];
                    $arrayTasks []= $task;
                }
            }
            $this->getResponseAndDatas(200, $user);

        }else{

            $this->getMessageAndCode(503, "Aucun utilisateur n'a été trouvé.");

        }
    }

    public function addUser(string $name, string $email){

        $name = trim($name);
        $email = trim($email);

            if ($this->userModel->create($name, $email)){
                $this->getMessageAndCode(201, "L'ajout a été effectué");

            }else{
                $this->getMessageAndCode(503, "L'ajout  n'a pas été effectué");
            }
    }

    public function delete(int $id){

            if ($this->userModel->deleteUserDb($id)){
                $this->getMessageAndCode(200, "La suppression a été effectué");

            }else{
                $this->getMessageAndCode(503, "La suppression n'a pas été effectué");
            }
    }

}