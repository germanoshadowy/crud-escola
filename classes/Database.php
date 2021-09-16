<?php

class Database {

    private static $db;

    private function __construct() {
        $host = "localhost";
        $name = "escola";
        $usuario = "root";
        $senha = "";
        $driver = "mysql";

        $sistema = "Aula Info";
        $email_sistema = "cimol@gmail.com";

        try {
            self::$db = new PDO("$driver:host=$host;dbname=$name", $usuario, $senha);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            die("connection Error: " . $e->getMessage());
        }
    }
    public static function conexao() {
        if (!self::$db) {
            new Database();
        }
        return self::$db;
    }
}