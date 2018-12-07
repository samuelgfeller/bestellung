<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';

class Bestellung {
    private $id;
    private $kunde_id;
    private $date;

    public static function checkEmail($email) {
        $db = Db::instantiate();
        $query = 'SELECT id FROM kunde where deleted_at is null and email COLLATE UTF8_GENERAL_CI like "' . $email . '";';
        $result = $db->query($query);
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            return $result->fetch_assoc()['id'];
        }
    }

    public static function create($kunde_id, $targetDate) {
//        var_dump($targetDate);
        $db = Db::instantiate();
        $query = 'INSERT INTO bestellung (kunde_id,datum,ziel_datum) VALUES ("' . $kunde_id . '", now(),"' . $targetDate . '")';
        $result = $db->query($query);
        Db::checkConnection($result, $query);
        $last_id = $db->insert_id;
        return $last_id;
    }

    public static function find($id) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM bestellung WHERE deleted_at is null and id=' . $id);
        //if the result is empty the $result will be an object with numrows == 0 but if its an invalid statement like "nummer="
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            $bestellungArr = $result->fetch_assoc();
            $bestellung = populate::populateBestellung($bestellungArr);
            return $bestellung;
        }
    }

    public static function findByClient($client_id) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM rechnung WHERE deleted_at is null and kunde_id=' . $client_id);
        //if the result is empty the $result will be an object with numrows == 0 but if its an invalid statement like "nummer="
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            $rechnungArr = $result->fetch_assoc();
            $rechnung = populate::populateRechnung($rechnungArr);
            return $rechnung;
        }
    }

    public static function allFrom($datum) {
        $rechnungen = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM rechnung WHERE deleted_at is null and datum="' . $datum . '" order by id desc');
        while ($rechnungArr = $result->fetch_assoc()) {
            $rechnung = Populate::populateRechnung($rechnungArr);
            $rechnungen[] = $rechnung;
        }
        if (!empty($rechnungen)) {
            return $rechnungen;
        }
        return false;
    }

    public static function getSearchResult($inputVal) {
        $db = Db::instantiate();
//        echo "SELECT * FROM rechnung r INNER JOIN kunde k ON r.id = k.id WHERE k.name LIKE '%".$inputVal."%' OR k.vorname LIKE '%".$inputVal."%'";
        $result = $db->query("SELECT r.id,r.bezahlt,r.kunde_id FROM rechnung r INNER JOIN kunde k ON r.kunde_id = k.id WHERE 
        deleted_at is null and k.name LIKE '%" . $inputVal . "%' OR k.vorname LIKE '%" . $inputVal . "%'");
        /*OR nummer LIKE '%".$inputVal."%'*/
        $rechnungen = null;

        while ($rechnungArr = $result->fetch_assoc()) {
            $rechnung = Populate::populateRechnung($rechnungArr);
            $rechnungen[] = $rechnung;
        }
        if (!empty($rechnungen)) {
            return $rechnungen;
        }
        return false;
    }

    public static function getRechnungBetrag($rechnung_id) {
        $db = Db::instantiate();
        $result = $db->query('SELECT sum(preis) as total FROM `position` WHERE deleted_at is null and rechnung_id=' . $rechnung_id);
        //if the result is empty the $result will be an object with numrows == 0 but if its an invalid statement like "nummer="
        if (!$result || $result->num_rows == 0) {
            return 0;
        } else {
            $rechnungBetrag = $result->fetch_assoc();
            return $rechnungBetrag['total'];
        }
    }

    public static function del($id) {
        $db = Db::instantiate();
        $query1 = 'UPDATE `bestell_position` SET deleted_at=now() WHERE bestellung_id=' . $id;
        $query2 = 'UPDATE bestellung SET deleted_at=now() WHERE id=' . $id;
        $sql1 = $db->query($query1);
        $sql2 = $db->query($query2);
        Db::checkConnection($sql1, $query1);
        Db::checkConnection($sql2, $query2);
    }

    /**
     * set payed
     * @param $id
     * @param $value
     */
    public static function check($id, $value) {
        $db = Db::instantiate();
        $query = 'UPDATE rechnung SET bezahlt=' . $value . ' WHERE id=' . $id;
        $sql = $db->query($query);
        Db::checkConnection($sql, $query);
    }

    public static function updComment($id, $value) {
        $db = Db::instantiate();
        $query = 'UPDATE rechnung SET kommentar="' . $value . '" WHERE id=' . $id;
        $sql = $db->query($query);
        Db::checkConnection($sql, $query);
    }

    /**
     * @return array|null
     */
    public static function getDates() {
        $db = Db::instantiate();
        $result = $db->query('SELECT DISTINCT datum FROM rechnung where deleted_at is null;');
        //if the result is empty the $result will be an object with numrows == 0 but if its an invalid statement like "nummer="
        if (!$result || $result->num_rows == 0) {
            return null;
        } else {
            $rowDates = [];
            while ($row = $result->fetch_assoc()) {
                $rowDates[] = $row['datum'];
            }
            $dates = [];
            foreach (array_reverse($rowDates) as $datum) {
                $datum = strtotime($datum);
                $datum = date('d.m.Y', $datum);
                $dates[] = $datum;
            }
//            $dates = $result->fetch_assoc();
            return $dates;
        }
    }

    public static function lastDate() {
        $db = Db::instantiate();
        $result = $db->query('SELECT datum FROM rechnung where deleted_at is null order by datum desc limit 1;');
        //if the result is empty the $result will be an object with numrows == 0 but if its an invalid statement like "nummer="
        if (!$result || $result->num_rows == 0) {
            return null;
        } else {
            return date('d-m-Y', strtotime($result->fetch_assoc()['datum']));;
        }
    }

    /**
     * return true if there are multiple orders
     *
     * @param $kunde_id
     * @param $datum
     * @return bool|mixed
     */
    public static function checkMultipleOrdersAndGetOlder($kunde_id, $datum) {
        $db = Db::instantiate();
        $query = 'select id from bestellung where kunde_id=' . $kunde_id . ' and ziel_datum="' . $datum . '" and deleted_at is null;';
        $result = $db->query($query);
        if (!$result || $result->num_rows == 0) {
            return false;
        }
        $ids=[];
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id'];
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