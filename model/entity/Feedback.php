<?php

require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

class Feedback {
    public static function add($feedback,$client_id) {
	    // Cannot use the function insert here, because the date has to be set with now()
	    $query = 'INSERT INTO feedback (feedback,kunde_id,zeit) VALUES (?,?,now());';
	    $conn = DataManagement::run($query, [$feedback,$client_id]);
	    return $conn->lastInsertId();
    }
}