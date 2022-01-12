<?php

abstract class Database
{

    private static $host = "localhost";
    private static $dbName = "managerone";
    private static $userName = "root";
    private static $pwd = "";

    private static $pdo;



    private static function setBdd(){
        try {

            self::$pdo = new PDO(
                    "mysql:host=".self::$host.";
                        dbname=".self::$dbName.";
                        charset=UTF8",
                        self::$userName,
                        self::$pwd
                    );

        }catch (Exception $e){
            throw new Exception("Problème serveur veuillez retenter une connexion");
        }

    }


    protected function getBdd(){
        if (self::$pdo === null){
            self::setBdd();
        }
        return self::$pdo;
    }
}