<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateArray.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/Helper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/DataManagement.php';
class ClientDAO
{
	/**
	 * @param $id id des Clientn
	 * @return Client Client-Objekt
	 */
	public static function find($id) {
		$query = 'SELECT * FROM client WHERE deleted_at is null and id =?;';
		$dataArr = DataManagement::selectAndFetchSingleData($query, [$id]);
		return PopulateObject::populateClient($dataArr);
		
	}
}