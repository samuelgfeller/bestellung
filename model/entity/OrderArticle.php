<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

//require_once __DIR__ . '/Appointment.php';


class OrderArticle
{
	
	private $order_article_id;
	private $article_id;
	private $weight;
	private $nr;
	private $name;
	private $kg_price;
	private $available; // Bool if available
	private $availableWeight;
	private $pieceWeight;
	private $date;
	private $avgWeight;
	
	
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
	public function getArticleId() {
		return $this->article_id;
	}
	
	/**
	 * @param mixed $article_id
	 */
	public function setArticleId($article_id): void {
		$this->article_id = $article_id;
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
	public function getNr() {
		return $this->nr;
	}
	
	/**
	 * @param mixed $nr
	 */
	public function setNr($nr): void {
		$this->nr = $nr;
	}
	
	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param mixed $name
	 */
	public function setName($name): void {
		$this->name = $name;
	}
	
	/**
	 * @return mixed
	 */
	public function getKgPrice() {
		return $this->kg_price;
	}
	
	/**
	 * @param mixed $kg_price
	 */
	public function setKgPrice($kg_price): void {
		$this->kg_price = $kg_price;
	}
	
	/**
	 * @return mixed
	 */
	public function getAvailable() {
		return $this->available;
	}
	
	/**
	 * @param mixed $available
	 */
	public function setAvailable($available): void {
		$this->available = $available;
	}
	
	/**
	 * @return mixed
	 */
	public function getAvailableWeight() {
		return $this->availableWeight;
	}
	
	/**
	 * @param mixed $availableWeight
	 */
	public function setAvailableWeight($availableWeight): void {
		$this->availableWeight = $availableWeight;
	}
	
	/**
	 * @return mixed
	 */
	public function getPieceWeight() {
		return $this->pieceWeight;
	}
	
	/**
	 * @param mixed $pieceWeight
	 */
	public function setPieceWeight($pieceWeight): void {
		$this->pieceWeight = $pieceWeight;
	}
	
	/**
	 * @return mixed
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * @param mixed $date
	 */
	public function setDate($date): void {
		$this->date = $date;
	}
	
	/**
	 * @return mixed
	 */
	public function getAvgWeight() {
		return $this->avgWeight;
	}
	
	/**
	 * @param mixed $avgWeight
	 */
	public function setAvgWeight($avgWeight): void {
		$this->avgWeight = $avgWeight;
	}
	
}