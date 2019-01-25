<?php

require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

/**
 * Class Client
 */
class Client
{
	/**
	 * @var int client id
	 */
	private $id;
	private $name;
	private $first_name;
	private $address;
	private $phone;
	private $phone_mobile;
	private $email;
	private $person_amount;
	private $siedfleisch;
	private $place_id;
	private $remark;
	private $deleted_at;
	
	
	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}
	
	/**
	 * @param int $id
	 */
	public function setId(int $id): void {
		$this->id = $id;
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
	public function getFirstName() {
		return $this->first_name;
	}
	
	/**
	 * @param mixed $first_name
	 */
	public function setFirstName($first_name): void {
		$this->first_name = $first_name;
	}
	
	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->address;
	}
	
	/**
	 * @param mixed $address
	 */
	public function setAddress($address): void {
		$this->address = $address;
	}
	
	/**
	 * @return mixed
	 */
	public function getPhone() {
		return $this->phone;
	}
	
	/**
	 * @param mixed $phone
	 */
	public function setPhone($phone): void {
		$this->phone = $phone;
	}
	
	/**
	 * @return mixed
	 */
	public function getPhoneMobile() {
		return $this->phone_mobile;
	}
	
	/**
	 * @param mixed $phone_mobile
	 */
	public function setPhoneMobile($phone_mobile): void {
		$this->phone_mobile = $phone_mobile;
	}
	
	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @param mixed $email
	 */
	public function setEmail($email): void {
		$this->email = $email;
	}
	
	/**
	 * @return mixed
	 */
	public function getPersonAmount() {
		return $this->person_amount;
	}
	
	/**
	 * @param mixed $person_amount
	 */
	public function setPersonAmount($person_amount): void {
		$this->person_amount = $person_amount;
	}
	
	/**
	 * @return mixed
	 */
	public function getSiedfleisch() {
		return $this->siedfleisch;
	}
	
	/**
	 * @param mixed $siedfleisch
	 */
	public function setSiedfleisch($siedfleisch): void {
		$this->siedfleisch = $siedfleisch;
	}
	
	/**
	 * @return mixed
	 */
	public function getPlaceId() {
		return $this->place_id;
	}
	
	/**
	 * @param mixed $place_id
	 */
	public function setPlaceId($place_id): void {
		$this->place_id = $place_id;
	}
	
	/**
	 * @return mixed
	 */
	public function getRemark() {
		return $this->remark;
	}
	
	/**
	 * @param mixed $remark
	 */
	public function setRemark($remark): void {
		$this->remark = $remark;
	}
	
	/**
	 * @return mixed
	 */
	public function getDeletedAt() {
		return $this->deleted_at;
	}
	
	/**
	 * @param mixed $deleted_at
	 */
	public function setDeletedAt($deleted_at): void {
		$this->deleted_at = $deleted_at;
	}
	
}