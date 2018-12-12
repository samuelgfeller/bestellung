<?php
  class Db {
      private static $instance = NULL;

      public static function instantiate() {
          if (empty(self::$instance)) {
              self::$instance = new mysqli(LocalProd::con_host, LocalProd::con_username, LocalProd::con_password, LocalProd::db_name);

              if (self::$instance->connect_error) {
                  die('Connect Error (' . self::$instance->connect_errno . ') '
                      . self::$instance->connect_error);
              }
              self::$instance->set_charset("utf8");
          }
          return self::$instance;
      }
      public static function checkConnection($result,$query){
          if (!$result) {
              echo'Error: ' . $result."<br>".self::$instance->error.'<br>'.$query.'<br>';

          }
      }
  }
