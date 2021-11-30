<?php
class DB_connection {
   private $dbh;
   //CONFIGURACION DE CONEXION A LA BD
   function __construct() {
      $dsn = "mysql:host=localhost; dbname=aws_bd_publicidad_labodega; charset=utf8";
      $options = [
         PDO::ATTR_EMULATE_PREPARES    => false,
         PDO::ATTR_EMULATE_PREPARES    => true,
         PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
      ];
      $this->dbh = new PDO($dsn,'root','',$options);
   }

   //FUNCION PARA OBTENER MÁS DE UN RESULTADO
   function MostrarEnHTML($query) {
      try {
         $statement = $this->dbh->query($query);
         $statement->setFetchMode(PDO::FETCH_ASSOC);
         $result = $statement->fetchAll();
         $this->dbh =null;
         return $result;
      } catch (PDOException $e) {
         error_log('PDOException -  '.$e->getMessage(), 0);
         http_response_code(500);
         die($e->getMessage());
      }
   }

   //FUNCION PARA OBTENER MÁS DE UN RESULTADO
   function SelectAll($query) {
      try {
         $statement = $this->dbh->prepare($query);
         $statement->execute();
         $statement->setFetchMode(PDO::FETCH_ASSOC);
         $result = $statement->fetchAll();
         $this->dbh =null;
         return $result;
      } catch (PDOException $e) {
         error_log('PDOException -  '.$e->getMessage(), 0);
         http_response_code(500);
         die($e->getMessage());
      }
   }

   //FUNCION PARA OBTENER UN SOLO RESULTADO
   function SelectOnlyOne($query) {
      try {
         $statement = $this->dbh->prepare($query);
         $statement->execute();
         $statement->setFetchMode(PDO::FETCH_ASSOC);
         $result = $statement->fetch();
         $this->dbh =null;
         return $result;
      } catch (PDOException $e) {
         error_log('PDOException -  '.$e->getMessage(), 0);
         http_response_code(500);
         die($e->getMessage());
      }
   }

   //FUNCION PARA EJECUTAR CONSULTA
   function ExecuteQuery($query,$params) {
      try {
         $statment = $this->dbh->prepare($query);
         $statment->execute($params);
         $this->dbh = null;
      } catch (PDOException $e) {
         error_log('PDOException - '.$e->getMessage(), 0);
         http_response_code(500);
         die($e->getMessage());
         return $e->getMessage();
      }
   }

   //FUNCION PARA TRAER EL ULTIMO ID REGISTRADO
   function GetLastId() {
      $id = $this->dbh->lastInsertId();
      return $id;
   }
}
