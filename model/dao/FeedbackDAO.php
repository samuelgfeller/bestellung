<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateArray.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/Helper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/DataManagement.php';
class FeedbackDAO
{
	public static function add($feedback,$client_id) {
		// Cannot use the function insert here, because the date has to be set with now()
		$query = 'INSERT INTO feedback (feedback,client_id,time) VALUES (?,?,now());';
		$conn = DataManagement::run($query, [$feedback,$client_id]);
		return $conn->lastInsertId();
	}
}