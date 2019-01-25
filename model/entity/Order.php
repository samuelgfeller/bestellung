<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';


class Order {
    private $id;
    private $client_id;
    private $date;

    
	
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
	public function getClientId() {
		return $this->client_id;
	}
	
	/**
	 * @param mixed $client_id
	 */
	public function setClientId($client_id): void {
		$this->client_id = $client_id;
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


}