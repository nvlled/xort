<?php

// database credentials
//$dbcred = [
//    "urn" => "mysql:hostname=localhost;dbname=XXXX",
//    "username" => "XXXX",
//    "password" => "XXXX",
//];
// override default values on settings.php

class DB {
    const WORDFILE = "426words";

    static function connect() {
        //global $dbcred;
        $dbcred = settings("dbcred");
        $db = new PDO($dbcred["urn"], $dbcred["username"], $dbcred["password"]);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    static function drop() {
        $db = self::connect();
        $db->exec("drop table vocab");
        $db->exec("drop table urlmap");
    }

    static function init() {
        $db = self::connect();
        $db->exec(
            "create table if not exists vocab(
                word varchar(20) PRIMARY KEY NOT NULL
            );"
        );
        print_r($db->errorInfo(), true);
        $db->exec(
            "create table if not exists urlmap(
                id varchar(50) PRIMARY KEY NOT NULL,
                link text,
                dateCreated datetime,
                dateExpire datetime,
                owner text,
                visits int default 0
            );"
        );
        print_r($db->errorInfo(), true);
        $db->exec(
            "create table if not exists users(
                username varchar(50) PRIMARY KEY NOT NULL,
                password text,
                dateCreated datetime
            );"
        );
        print_r($db->errorInfo(), true);

    }

    static function initVocab() {
        $db = self::connect();
        $st = $db->prepare(file_get_contents("426words.sql"));
        $st->execute();
        print_r($db->errorInfo(), true);
    }
}

?>
