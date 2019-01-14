<?php

require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

class Termin {
    private $id;
    private $datum;

    public static function all() {
	    $query = 'SELECT * FROM termin WHERE deleted_at is null order by id desc';
	    $result = DataManagement::selectAndFetchAssocMultipleData($query);
	    $dataObjArr = [];
	    foreach ($result as $dataArr) {
		    $dataObjArr[] = PopulateObject::populateTermin($dataArr);
	    }
	    return $dataObjArr;
    }

    public static function getYearsAndDates() {
	    $years = [];
	    $dates = [];
	    $query = 'SELECT datum FROM termin WHERE deleted_at is null order by datum desc';
	    $allData = DataManagement::selectAndFetchAssocMultipleData($query);
	    foreach ($allData as $dateArr) {
		    $years[] = date('Y', strtotime($dateArr['datum']));
		    $dates[] = date('d.m.Y', strtotime($dateArr['datum']));
	    }
	    return [
		    'years' => $years,
		    'dates' => $dates,
	    ];
    }

    /**
     * @return array
     */
    public static function getNextDate(){
        $dateArr = self::getYearsAndDates()['dates'];
        $today = time();
	    $intervals = [];
	    foreach ($dateArr as $key => $date) {
            $interval = $today - strtotime($date);
//            var_dump($interval,$date);
            if ($interval < 0){
                $intervals[$key] = abs($interval);
            }else{
                unset($dateArr[$key]);
            }
        }
        asort($intervals);
//        var_dump($intervals,$dateArr);
        $closest = key($intervals);
//        var_dump($closest);
        return [
	        'sql' => $closest !== null ? date('Y-m-d', strtotime($dateArr[$closest])) : null,
	        'text' => $closest !== null ? $dateArr[$closest] : null,
            ];
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
    public function getDatum() {
        return $this->datum;
    }

    /**
     * @param mixed $datum
     */
    public function setDatum($datum) {
        $this->datum = $datum;
    }


}