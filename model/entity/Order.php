<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';


class Order {
    private $id;
    private $client_id;
    private $date;

    public static function checkEmail($email) {
	    $email = Helper::getLikeString($email);
	    $query = 'SELECT id FROM kunde where deleted_at is null and email COLLATE UTF8_GENERAL_CI like ?;';
	    return DataManagement::selectAndFetchSingleData($query, [$email])['id'];
    }

    public static function create($client_id, $targetDate) {
	    // Cannot use the function insert here, because the date has to be set with now()
	    $query = 'INSERT INTO `order` (client_id,datum,ziel_datum) VALUES (?, now(),?)';
	    $conn = DataManagement::run($query, [$client_id,$targetDate]);
	    return $conn->lastInsertId();
    }

    public static function find($id) {
	    $query = 'SELECT * FROM `order` WHERE deleted_at is null and id=?;';
	    $dataArr = DataManagement::selectAndFetchSingleData($query, [$id]);
	    return PopulateObject::populateBestellung($dataArr);
    }
    
    public static function del($id) {
        $query1 = 'UPDATE `bestell_position` SET deleted_at=now() WHERE bestellung_id=?';
        $query2 = 'UPDATE `order` SET deleted_at=now() WHERE id=?';
	    DataManagement::run($query1, [$id]);
	    DataManagement::run($query2, [$id]);
    }
    
    /**
     * return true if there are multiple orders
     *
     * @param $client_id
     * @param $datum
     * @return bool|mixed
     */
    public static function checkMultipleOrdersAndGetOlder($client_id, $datum) {
	    $query = 'select id from `order` where client_id=? and ziel_datum=? and deleted_at is null;';
	    $result = DataManagement::selectAndFetchAssocMultipleData($query,[$client_id,$datum]);
	    $ids=[];
	    foreach ($result as $dataArr) {
		    $ids[] = $dataArr['id'];
	    }
	    if (count($ids) > 1) {
		    return min($ids);
	    }
	    return false;
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
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getKundeId() {
        return $this->client_id;
    }

    /**
     * @param mixed $client_id
     */
    public function setKundeId($client_id) {
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
    public function setDate($date) {
        $this->date = $date;
    }

}