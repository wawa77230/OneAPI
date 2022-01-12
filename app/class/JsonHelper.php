<?php

class JsonHelper
{

    public function checkIntValue( $value){
        $value = (int)$value;

//        Lorque de $value est converti en int si il n'est toujours pas un int la valeur renvoyé sera 0
        if (is_int($value) && $value > 0){
            return $value;
        }else{
            self::getMessageAndCode(400, "Requête incorrecte");
//            Stoppe l'execution du code pour controller l'erreur
            die();
        }
    }

    public function getMessageAndCode(int $code, string $message){

        http_response_code($code);
        echo json_encode(["message" => $message]);
    }

    public function getResponseAndDatas(int $code, $datas){
        http_response_code($code);
        echo json_encode($datas);
    }
}