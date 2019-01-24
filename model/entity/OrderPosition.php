<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/PopulateArray.php';
require_once __DIR__ . '/../service/Helper.php';
require_once __DIR__ . '/../service/DataManagement.php';


class OrderPosition {
    private $id;
    private $order_id;
    private $order_article_id;
    private $package_amount;
    private $weight;
    private $comment;


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
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id
	 */
	public function setId($id): void {
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	public function getOrderId() {
		return $this->order_id;
	}
	
	/**
	 * @param mixed $order_id
	 */
	public function setOrderId($order_id): void {
		$this->order_id = $order_id;
	}
	
	/**
	 * @return mixed
	 */
	public function getOrderArticleId() {
		return $this->order_article_id;
	}
	
	/**
	 * @param mixed $order_article_id
	 */
	public function setOrderArticleId($order_article_id): void {
		$this->order_article_id = $order_article_id;
	}
	
	/**
	 * @return mixed
	 */
	public function getPackageAmount() {
		return $this->package_amount;
	}
	
	/**
	 * @param mixed $package_amount
	 */
	public function setPackageAmount($package_amount): void {
		$this->package_amount = $package_amount;
	}
	
	/**
	 * @return mixed
	 */
	public function getWeight() {
		return $this->weight;
	}
	
	/**
	 * @param mixed $weight
	 */
	public function setWeight($weight): void {
		$this->weight = $weight;
	}
	
	/**
	 * @return mixed
	 */
	public function getComment() {
		return $this->comment;
	}
	
	/**
	 * @param mixed $comment
	 */
	public function setComment($comment): void {
		$this->comment = $comment;
	}

}