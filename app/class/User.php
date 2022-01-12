<?php

class User
{
    private $id;
    private $name;
    private $email;


    /**
     * @param $id
     * @param $name
     * @param $email
     */
    public function __construct(int $id,string $name,string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail():string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


}