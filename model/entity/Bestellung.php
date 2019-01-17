<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';


class Bestellung {
    private $id;
    private $kunde_id;
    private $date;

    public static function checkEmail($email) {
	    $email = Helper::getLikeString($email);
	    $query = 'SELECT id FROM kunde where deleted_at is null and email COLLATE UTF8_GENERAL_CI like ?;';
	    return DataManagement::selectAndFetchSingleData($query, [$email])['id'];
    }

    public static function create($kunde_id, $targetDate) {
	    // Cannot use the function insert here, because the date has to be set with now()
	    $query = 'INSERT INTO bestellung (kunde_id,datum,ziel_datum) VALUES (?, now(),?)';
	    $conn = DataManagement::run($query, [$kunde_id,$targetDate]);
	    return $conn->lastInsertId();
    }

    public static function find($id) {
	    $query = 'SELECT * FROM bestellung WHERE deleted_at is null and id=?;';
	    $dataArr = DataManagement::selectAndFetchSingleData($query, [$id]);
	    return PopulateObject::populateBestellung($dataArr);
    }
    
    public static function del($id) {
        $query1 = 'UPDATE `bestell_position` SET deleted_at=now() WHERE bestellung_id=?';
        $query2 = 'UPDATE bestellung SET deleted_at=now() WHERE id=?';
	    DataManagement::run($query1, [$id]);
	    DataManagement::run($query2, [$id]);
    }
    
    /**
     * return true if there are multiple orders
     *
     * @param $kunde_id
     * @param $datum
     * @return bool|mixed
     */
    public static function checkMultipleOrdersAndGetOlder($kunde_id, $datum) {
	    $query = 'select id from bestellung where kunde_id=? and ziel_datum=? and deleted_at is null;';
	    $result = DataManagement::selectAndFetchAssocMultipleData($query,[$kunde_id,$datum]);
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
        return $this->kunde_id;
    }

    /**
     * @param mixed $kunde_id
     */
    public function setKundeId($kunde_id) {
        $this->kunde_id = $kunde_id;
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