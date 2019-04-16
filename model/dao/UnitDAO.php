<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateArray.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/DataManagement.php';

class UnitDAO {

    public static function find($id) {
        $query = 'SELECT * FROM unit WHERE deleted_at is null and id =?;';
        $dataArr = DataManagement::selectAndFetchSingleData($query, [$id]);
        return PopulateObject::populateUnit($dataArr);
    }
}