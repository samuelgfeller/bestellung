<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';


class Order {
    private $id;
    private $client_id;
    private $created_at;
    private $appointmentId;
    private $remark;

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
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getAppointmentId()
    {
        return $this->appointmentId;
    }

    /**
     * @param mixed $appointmentId
     */
    public function setAppointmentId($appointmentId): void
    {
        $this->appointmentId = $appointmentId;
    }


}