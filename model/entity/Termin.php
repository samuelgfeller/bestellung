<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 25.11.2018
 * Time: 12:44
 */
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';


class Termin {
    private $id;
    private $datum;

    public static function all() {
        $daten = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM termin WHERE deleted_at is null order by id desc');
        while ($datumArr = $result->fetch_assoc()) {
            $termin = Populate::populateTermin($datumArr);
            $daten[] = $termin;
        }
        if (!empty($daten)) {
            return $daten;
        }
        return false;
    }

/*    public static function getTextDates() {
        $daten = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT datum FROM termin WHERE deleted_at is null order by datum desc');
        if (!$result || $result->num_rows == 0) {
            return null;
        } else {
            while ($datumArr = $result->fetch_assoc()) {
                $daten[] = date('d.m.Y', strtotime($datumArr['datum']));
            }
            if (!empty($daten)) {
                return $daten;
            }
            return false;
        }
    }*/

    public static function getYearsAndDates() {
        $years = [];
        $dates = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT datum FROM termin WHERE deleted_at is null order by datum desc');
        if (!$result || $result->num_rows == 0) {
            return null;
        }
        while ($datumArr = $result->fetch_assoc()) {
            $years[] = date('Y', strtotime($datumArr['datum']));
            $dates[] = date('d.m.Y', strtotime($datumArr['datum']));
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
        foreach ($dateArr as $key => $date) {
            $interval = $today - strtotime($date);
//            var_dump($interval,$date);
            if ($interval < 0) {
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
            'sql'=> date('Y-m-d', strtotime($dateArr[$closest])),
            'text' => $dateArr[$closest],
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