<?php

class ConnectionManager
{
    public function connect_db(): PDO
    {
        $db = new PDO("mysql:host=localhost;dbname=quizappdb", "root", "qamzu6-tuqqEq-xapqos");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // throw exceptions
        return $db;
    }
}