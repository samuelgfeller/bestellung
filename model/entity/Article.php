<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

class Article {

    private $id;
    private $nr;
    private $name;
    private $kg_price;
    private $piece_weight;
    private $weight_1;
    private $weight_2;
    private $weight_3;
    private $weight_4;
    private $piece_amount_1;
    private $piece_amount_2;
    private $piece_amount_3;
    private $piece_amount_4;

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
	public function getPieceWeight() {
		return $this->piece_weight;
	}

	/**
	 * @param mixed $piece_weight
	 */
	public function setPieceWeight($piece_weight): void {
		$this->piece_weight = $piece_weight;
	}

	/**
	 * @return mixed
	 */
	public function getWeight1() {
		return $this->weight_1;
	}

	/**
	 * @param mixed $weight_1
	 */
	public function setWeight1($weight_1): void {
		$this->weight_1 = $weight_1;
	}

	/**
	 * @return mixed
	 */
	public function getWeight2() {
		return $this->weight_2;
	}

	/**
	 * @param mixed $weight_2
	 */
	public function setWeight2($weight_2): void {
		$this->weight_2 = $weight_2;
	}

	/**
	 * @return mixed
	 */
	public function getWeight3() {
		return $this->weight_3;
	}

	/**
	 * @param mixed $weight_3
	 */
	public function setWeight3($weight_3): void {
		$this->weight_3 = $weight_3;
	}

	/**
	 * @return mixed
	 */
	public function getWeight4() {
		return $this->weight_4;
	}

	/**
	 * @param mixed $weight_4
	 */
	public function setWeight4($weight_4): void {
		$this->weight_4 = $weight_4;
	}

	/**
	 * @return mixed
	 */
	public function getPieceAmount1() {
		return $this->piece_amount_1;
	}

	/**
	 * @param mixed $piece_amount_1
	 */
	public function setPieceAmount1($piece_amount_1): void {
		$this->piece_amount_1 = $piece_amount_1;
	}

	/**
	 * @return mixed
	 */
	public function getPieceAmount2() {
		return $this->piece_amount_2;
	}

	/**
	 * @param mixed $piece_amount_2
	 */
	public function setPieceAmount2($piece_amount_2): void {
		$this->piece_amount_2 = $piece_amount_2;
	}

	/**
	 * @return mixed
	 */
	public function getPieceAmount3() {
		return $this->piece_amount_3;
	}

	/**
	 * @param mixed $piece_amount_3
	 */
	public function setPieceAmount3($piece_amount_3): void {
		$this->piece_amount_3 = $piece_amount_3;
	}

	/**
	 * @return mixed
	 */
	public function getPieceAmount4() {
		return $this->piece_amount_4;
	}

	/**
	 * @param mixed $piece_amount_4
	 */
	public function setPieceAmount4($piece_amount_4): void {
		$this->piece_amount_4 = $piece_amount_4;
	}


}