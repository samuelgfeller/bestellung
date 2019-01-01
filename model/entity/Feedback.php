<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 07.12.2018
 * Time: 16:52
 */
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';
class Feedback {
    public static function add($feedback,$client_id) {
        $db = Db::instantiate();
        $query ='INSERT INTO feedback (feedback,kunde_id,zeit) VALUES ("'.$feedback.'",'.$client_id.',now());';
        $result = $db->query($query);
        Db::checkConnection($result,$query);
        return $db->insert_id;
    }
}