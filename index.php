<?php

define("PATH",__DIR__."/app/");

header("Content-type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization, Access-Control-Allow-Headers');
header('Access-Control-Allow-Methods: GET, POST, DELETE');


if (isset($_GET['api'])){
    $url = explode("/",filter_var($_GET['api']),FILTER_SANITIZE_URL);

    require_once PATH."class/JsonHelper.php";
    $jsonHelper = new JsonHelper();
    switch ($url[0]){
        case "users":
        case "user":
            require_once PATH."controller/UserController.php";
            $userController = new UserController();

            switch ($_SERVER['REQUEST_METHOD']){

                case "GET":

                    if ($url[0] == "users"){
                        $userController->getUsers();
                    }
                    elseif ($url[0] == "user"){

                        if ($url[1]) {
//                            $url[1] est l'id en 2eme parametre de l'URI
                            $jsonHelper->checkIntValue($url[1]);
                            $userController->getUser($url[1]);
                        }
                        else{
                            $jsonHelper->getMessageAndCode(400,"Requête incorrecte");
                        }
                    }else{
                        $jsonHelper->getMessageAndCode(404,"L'adresse n'existe pas");
                    }
                    break;

                case "POST":

                    $datas = json_decode(file_get_contents("php://input"));

                    if (!empty($datas->name) && is_string($datas->name) &&
                        !empty($datas->email) && is_string($datas->email))
                    {
                        $userController->addUser($datas->name, $datas->email);
                    }
                    else{
                        $jsonHelper->getMessageAndCode(400,"Requête incorrecte");
                    }
                    break;

                case "DELETE":

                    $data = json_decode(file_get_contents("php://input"));

                    if ($url[1]){
                        //$url[1] est l'id en 2eme parametre de l'URI
                        $jsonHelper->checkIntValue($url[1]);
                        $userController->delete($url[1]);
                    }
                    else{
                        $jsonHelper->getMessageAndCode(400,"Requête incorrecte");
                    }
                    break;

                default:
                    $jsonHelper->getMessageAndCode(405,"La méthode n'est pas autorisée");

                    break;
            }
            break;
        case "tasks":
        case "task":

            require_once PATH."controller/TaskController.php";
            $taskController = new TaskController();

            switch ($_SERVER['REQUEST_METHOD']) {

                case "GET":

                    if ($url[0] == "tasks"){
                        $taskController->getTasks();
                    }
                    elseif ($url[0] == "task"){

                        if ($url[1]) {
//                            $url[1] est l'id en 2eme parametre de l'URI
                            $jsonHelper->checkIntValue($url[1]);
                            $taskController->getTask($url[1]);
                        }
                        else{
                            $jsonHelper->getMessageAndCode(400,"Requête incorrecte");
                        }
                    }else{
                        $jsonHelper->getMessageAndCode(404,"L'adresse n'existe pas");
                    }
                    break;

                case "POST":

                    $datas = json_decode(file_get_contents("php://input"));

                    if (!empty($datas->userId) && is_int($datas->userId) &&
                        !empty($datas->title) && is_string($datas->title) &&
                        !empty($datas->description) && is_string($datas->description) &&
                        !empty($datas->status) && is_string($datas->status))
                    {
                        $taskController->addTask($datas->userId, $datas->title, $datas->description, $datas->status);
                    }
                    else{
                        $jsonHelper->getMessageAndCode(400,"Requête incorrecte");
                    }
                    break;

                case "DELETE":

                    $data = json_decode(file_get_contents("php://input"));

                    if ($url[1]){
                        //$url[1] est l'id en 2eme parametre de l'URI
                        $jsonHelper->checkIntValue($url[1]);
                        $taskController->delete($url[1]);
                    }
                    else{
                        $jsonHelper->getMessageAndCode(400,"Requête incorrecte");
                    }
                    break;

                default:
                    $jsonHelper->getMessageAndCode(405,"La méthode n'est pas autorisée");
                    break;
            }
            break;
    }


}
