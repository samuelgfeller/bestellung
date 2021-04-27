<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateArray.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/Helper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/DataManagement.php';

class AppointmentDAO {
    public static function all() {
        $query = 'SELECT * FROM appointment WHERE deleted_at is null order by id desc';
        $result = DataManagement::selectAndFetchAssocMultipleData($query);
        $dataObjArr = [];
        foreach ($result as $dataArr) {
            $dataObjArr[] = PopulateObject::populateAppointment($dataArr);
        }
        return $dataObjArr;
    }

    public static function getYearsAndDates() {
        $years = [];
        $dates = [];
        $query = 'SELECT `date` FROM appointment WHERE deleted_at is null order by date desc';
        $allData = DataManagement::selectAndFetchAssocMultipleData($query);
        foreach ($allData as $dateArr) {
            $years[] = date('Y', strtotime($dateArr['date']));
            $dates[] = date('d.m.Y', strtotime($dateArr['date']));
        }
        return ['years' => $years,
            'dates' => $dates,];
    }

    /**
     * @return array
     */
    public static function getNextDate() {
        $dateArr = self::getYearsAndDates()['dates'];
        $today = time();
        $intervals = [];
        foreach ($dateArr as $key => $date) {
            // orders are possible until 12am. strtotime($date) is the time for midnight and adding 43200 makes it 12am of that date
            $dateAt12Am = strtotime($date) + 43200;
            $interval = $today - $dateAt12Am;
//            var_dump($interval,$date);
            if ($interval <= 0) {
                $intervals[$key] = abs($interval);
            } else {
                unset($dateArr[$key]);
            }
        }
        asort($intervals);
//        var_dump($intervals,$dateArr);
        $closest = key($intervals);
//        var_dump($closest);
        return ['sql' => $closest !== null ? date('Y-m-d', strtotime($dateArr[$closest])) : null,
            'text' => $closest !== null ? $dateArr[$closest] : null,];
    }

    public static function checkIfDateHasEntries($date) {
        $query = 'SELECT oa.id from order_article oa
LEFT JOIN appointment a ON a.id = oa.appointment_id 
where a.date=? and oa.available=1
AND a.deleted_at IS NULL;';
        $dataArr = DataManagement::selectAndFetchSingleData($query, [$date]);
        return $dataArr;
    }

    public static function getDateBeforeDate($dateSQL) {
        $query = 'SELECT * FROM appointment WHERE `date` < ? and appointment.deleted_at is null order BY `date` DESC LIMIT 1;';
        $dataArr = DataManagement::selectAndFetchSingleData($query, [$dateSQL]);
        return $dataArr['date'];

    }

    public static function findDateId($date) {
        $sqlDate = date('Y-m-d', strtotime($date));
        $query = 'SELECT id FROM appointment WHERE `date` = ? AND deleted_at IS NULL;';
        $dataArr = DataManagement::selectAndFetchSingleData($query, [$sqlDate]);
        return $dataArr['id'];

    }

}
