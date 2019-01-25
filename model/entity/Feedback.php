<?php

require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

class Feedback {
	private $client_id;
	private $feedback;
	private $time;
	
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
	public function getFeedback() {
		return $this->feedback;
	}
	
	/**
	 * @param mixed $feedback
	 */
	public function setFeedback($feedback): void {
		$this->feedback = $feedback;
	}
	
	/**
	 * @return mixed
	 */
	public function getTime() {
		return $this->time;
	}
	
	/**
	 * @param mixed $time
	 */
	public function setTime($time): void {
		$this->time = $time;
	}
	
}