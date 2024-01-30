<?php


class ModHome
{
    protected DB $database;

    public function __construct()
    {
        $this->database = new DB("home");
    }


    // creating some methods that with connection between database and server can give data
    public function getData($id): bool|array
    {
        return $this->database->FindByKey("id", $id);
    }


}