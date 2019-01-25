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