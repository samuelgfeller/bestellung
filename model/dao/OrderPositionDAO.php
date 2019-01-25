<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateArray.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/Helper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/DataManagement.php';

class OrderPositionDAO
{
	/**
	 * @param OrderPosition $position
	 * @return mixed
	 */
	public static function add(OrderPosition $position) {
		$data = PopulateArray::populateOrderPositionArray($position);
		return DataManagement::insert('order_position', $data);
	}
	
	public static function del($id) {
		$query = 'UPDATE `position` SET deleted_at=now() WHERE id=?';
		DataManagement::run($query, [$id]);
	}
	
	public static function getTotalOrderedWeightForBa($orderArticleId, $nextDate) {
		$query = 'SELECT bp.package_amount anz, bp.weight weight FROM order_position bp
        left join `order` b on bp.order_id=b.id
        left join order_article ba on ba.id = bp.order_article_id
        where b.deleted_at is null and ba.deleted_at is null and bp.deleted_at is null
        and ba.id = ? and b.target_date = ?;';
		return DataManagement::selectAndFetchAssocMultipleData($query, [$orderArticleId, $nextDate]);
	}
	
	public static function getIfAlreadyOrdered($client_id, $date) {
		$query = 'SELECT bp.* FROM order_position bp
left join `order` b on bp.order_id = b.id
where b.deleted_at is null and bp.deleted_at is null and b.client_id=? and b.target_date=?;';
		$allData = DataManagement::selectAndFetchAssocMultipleData($query, [$client_id, $date]);
		$allDataObj = [];
		foreach ($allData as $allDataArr) {
			$allDataObj[] = PopulateObject::populateOrderPosition($allDataArr);
		}
		return $allDataObj;
	}
}