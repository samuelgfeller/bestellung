<?php

require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

class Feedback {
    public static function add($feedback,$client_id) {
        $db = Db::instantiate();
        $query ='INSERT INTO feedback (feedback,kunde_id,zeit) VALUES ("'.$feedback.'",'.$client_id.',now());';
        $result = $db->query($query);
        Db::checkConnection($result,$query);
        return $db->insert_id;
    }
}