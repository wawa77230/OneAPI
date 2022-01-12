<?php
include_once PATH.'model/TaskModel.php';
include_once PATH.'class/JsonHelper.php';
include_once PATH.'class/Task.php';


class TaskController extends JsonHelper
{
    private $taskModel;

    public function __construct(){

        $this->taskModel = new TaskModel();
    }

    public function getTasks()
    {

        $stmt = $this->taskModel->fetchAllTasks();

        if ($stmt->rowCount() > 0){
            $arrayTasks = [];
            $arrayTasks['tasks'] = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $task = new Task((int) $id, (int)$userId, htmlspecialchars($title),htmlspecialchars($description),  date("d-m-Y",strtotime($creationDate)),htmlspecialchars($status));
                $tasks = [
                    "id" => $task->getId(),
                    "userId" => $task->getUserId(),
                    "title" =>  $task->getTitle(),
                    "description" =>  $task->getDescription(),
                    "creationDate" => $task->getCreationDate(),
                    "status" =>  $task->getStatus()
                ];

                $arrayTasks['tasks'][] = $tasks ;
            }

            $this->getResponseAndDatas(200, $arrayTasks);

        }else{

            $this->getMessageAndCode(503, "Aucun utilisateur n'a été trouvé!!");
        }
    }

    public function getTask(int $id)
    {
        $stmt = $this->taskModel->fetchTask($id);
        if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            $task = new Task($id, (int)$userId, htmlspecialchars($title),htmlspecialchars($description), date("d-m-Y",strtotime($creationDate)),htmlspecialchars($status));

            $task =[
                "id" => $task->getId(),
                "userId" => $task->getUserId(),
                "title" => $task->getTitle(),
                "description" => $task->getDescription(),
                "creationDate" => $task->getCreationDate(),
                "status" => $task->getStatus(),
            ];

            $this->getResponseAndDatas(200, $task);

        }else{
            $this->getMessageAndCode(503, "Aucune tâche n'a été trouvé.");
        }
    }

    public function addTask(int $userId, string $title, string $description, string $status)
    {
        $title = trim($title);
        $status = trim($status);

        if ($this->taskModel->create($userId, $title, $description, $status)){
            $this->getMessageAndCode(201, "L'ajout a été effectué");

        }else{
            $this->getMessageAndCode(503, "L'ajout  n'a pas été effectué");
        }
    }

    public function delete(int $id)
    {

        if ($this->taskModel->deleteTaskBd($id)){
            $this->getMessageAndCode(200, "La suppression a été effectué");

        }else{
            $this->getMessageAndCode(503, "La suppression n'a pas été effectué");
        }
    }


}