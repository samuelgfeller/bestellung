<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateArray.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/Helper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/DataManagement.php';
class OrderDAO
{
	public static function checkEmail($email) {
		$email = Helper::getLikeString($email);
		$query = 'SELECT id FROM client where deleted_at is null and email COLLATE UTF8_GENERAL_CI like ?;';
		return DataManagement::selectAndFetchSingleData($query, [$email])['id'];
	}
	
	public static function create($client_id, $targetDate) {
		// Cannot use the function insert here, because the date has to be set with now()
		$query = 'INSERT INTO `order` (client_id,date,target_date) VALUES (?, now(),?)';
		$conn = DataManagement::run($query, [$client_id,$targetDate]);
		return $conn->lastInsertId();
	}
	
	public static function find($id) {
		$query = 'SELECT * FROM `order` WHERE deleted_at is null and id=?;';
		$dataArr = DataManagement::selectAndFetchSingleData($query, [$id]);
		return PopulateObject::populateOrder($dataArr);
	}
	
	public static function del($id) {
		$query1 = 'UPDATE `order_position` SET deleted_at=now() WHERE order_id=?';
		$query2 = 'UPDATE `order` SET deleted_at=now() WHERE id=?';
		DataManagement::run($query1, [$id]);
		DataManagement::run($query2, [$id]);
	}
	
	/**
	 * return true if there are multiple orders
	 *
	 * @param $client_id
	 * @param $date
	 * @return bool|mixed
	 */
	public static function checkMultipleOrdersAndGetOlder($client_id, $date) {
		$query = 'select id from `order` where client_id=? and target_date=? and deleted_at is null;';
		$result = DataManagement::selectAndFetchAssocMultipleData($query,[$client_id,$date]);
		$ids=[];
		foreach ($result as $dataArr) {
			$ids[] = $dataArr['id'];
		}
		if (count($ids) > 1) {
			return min($ids);
		}
		return false;
	}
}