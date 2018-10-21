<?php
  class Db {
      private static $instance = NULL;

      public static function instantiate() {
          if (empty(self::$instance)) {
              self::$instance = new mysqli(Local::con_host, Local::con_username, Local::con_password, Local::db_name);

              if (self::$instance->connect_error) {
                  die('Connect Error (' . self::$instance->connect_errno . ') '
                      . self::$instance->connect_error);
              }
              self::$instance->set_charset("utf8");
          }
          return self::$instance;
      }
      public static function checkConnection($result){
          if (!$result) {
              echo'Error: ' . $result."<br>".self::$instance->error;
          }
      }
  }
