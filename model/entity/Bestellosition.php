<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';

class Bestellosition {
    private $id;
    private $weight;
    private $price;
    private $rechnung_id;
    private $artikel_id;


    /**
     * @param Bestellosition $position
     * @return mixed
     */
    public static function add(Bestellosition $position) {
        $db = Db::instantiate();
        $result = $db->query('INSERT INTO `position` (gewicht,preis,rechnung_id,artikel_id) VALUES ("' . $position->getWeight() . '", "' . $position->getPrice() . '", ' . $position->getRechnungId() . ', ' . $position->getArtikelId() . ')');
        Db::checkConnection($result);
        $last_id = $db->insert_id;
        return $last_id;
    }

    public static function del($id) {
        $db = Db::instantiate();
        $sql = $db->query('UPDATE `position` SET deleted_at=now() WHERE id=' . $id);
        Db::checkConnection($sql);
    }

    public static function getTotalOrderedWeightForBa($bestellArtikelId,$lastdate) {
        $db = Db::instantiate();

        // Get the sum
        $result = $db->query('SELECT sum(gewicht) FROM bestell_position ba 
left join bestellung b on ba.bestellung_id=b.id 
where b.deleted_at is null and ba.deleted_at is null and 
ba.id = '.$bestellArtikelId.' and b.date > "'.date('Y-m-d',$lastdate).'";');

        var_dump('SELECT sum(gewicht) FROM bestell_position ba 
left join bestellung b on ba.bestellung_id=b.id 
where b.deleted_at is null and ba.deleted_at is null and 
ba.id = '.$bestellArtikelId.' and b.date > "'.date('Y-m-d',$lastdate).'";');
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            $artikel = $result->fetch_assoc();
        }
        var_dump($artikel);
        return $artikel;
    }

    public static function getLastDate() {
        $db = Db::instantiate();
        // Get last bill date
        $result1 = $db->query('SELECT datum FROM rechnung where deleted_at is null order by datum desc limit 1;');
        //if the result is empty the $result will be an object with numrows == 0 but if its an invalid statement like "nummer="
        if ($result1 && !$result1->num_rows == 0) {
            return strtotime($result1->fetch_assoc()['datum']);
        }else{
            return strtotime(date('Y-m-d')." +60 days");
        }
    }

    /**
     * Get selling infos for an article on a date
     * @param $articleId
     * @param $date
     * @return array|int
     */
    public static function getInfosForAricle($articleId, $date) {
        $db = Db::instantiate();
        $result = $db->query('
        select 
        a.name artikel,count(*) anzahl, sum(p.gewicht) gewicht, sum(p.preis) preis 
        from position p 
        left join rechnung r on p.rechnung_id=r.id 
        left join artikel a on a.id = p.artikel_id
        where p.artikel_id = ' . $articleId . ' and r.datum = "' . $date . ' "
        and p.deleted_at is null and r.deleted_at is null');
        if (!$result || $result->num_rows == 0) {
            return 0;
        } else {
            $resultat = $result->fetch_assoc();
            return $resultat;
        }
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
    public function getWeight() {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight) {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getRechnungId() {
        return $this->rechnung_id;
    }

    /**
     * @param mixed $rechnung_id
     */
    public function setRechnungId($rechnung_id) {
        $this->rechnung_id = $rechnung_id;
    }

    /**
     * @return mixed
     */
    public function getArtikelId() {
        return $this->artikel_id;
    }

    /**
     * @param mixed $artikel_id
     */
    public function setArtikelId($artikel_id) {
        $this->artikel_id = $artikel_id;
    }


}