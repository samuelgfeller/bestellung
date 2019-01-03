<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';
require_once __DIR__ . '/../service/Helper.php';

class Bestellposition {
    private $id;
    private $bestellung_id;
    private $bestell_artikel_id;
    private $anzahl_paeckchen;
    private $gewicht;
    private $kommentar;


    /**
     * @param Bestellposition $position
     * @return mixed
     */
    public static function add(Bestellposition $position) {
        $data = ['bestellung_id' => $position->getBestellungId(),
            'bestell_artikel_id' => $position->getBestellArtikelId(),
            'anzahl_paeckchen' => $position->getAnzahlPaeckchen(),
            'gewicht' => $position->getGewicht(),
            'kommentar' => $position->getKommentar()];

        $colval = Helper::getImportColAndValues($data);
        $db = Db::instantiate();
        $columnnames = implode(',', $colval['cols']);
        $columnvalues = implode(',', $colval['values']);
        $query = 'INSERT INTO `bestell_position` (?) VALUES (?)';
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss",$columnnames,$columnvalues);
        $stmt->execute();
        $result = $stmt->get_result();
        Db::checkConnection($result, $query);
        $last_id = $db->insert_id;
        return $last_id;
    }

    public static function del($id) {
        $db = Db::instantiate();
        $query = 'UPDATE `position` SET deleted_at=now() WHERE id=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        Db::checkConnection($stmt->get_result(), $query);
    }

    public static function getTotalOrderedWeightForBa($bestellArtikelId, $nextDate) {
        $db = Db::instantiate();

        // Get the sum
        $query = 'SELECT bp.anzahl_paeckchen anz, bp.gewicht gewicht FROM bestell_position bp
        left join bestellung b on bp.bestellung_id=b.id
        left join bestell_artikel ba on ba.id = bp.bestell_artikel_id 
        where b.deleted_at is null and ba.deleted_at is null and bp.deleted_at is null 
        and ba.id = ? and b.ziel_datum = ?;';
        $stmt = $db->prepare($query);
        $stmt->bind_param("is",$bestellArtikelId,$nextDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            $bestellungen = [];
            while ($bestellung = $result->fetch_assoc()) {
                $bestellungen[] = $bestellung;
            }
            return $bestellungen;
//                return $result->fetch_assoc();
        }
    }

    public static function getIfAlreadyOrdered($client_id, $date) {
        $db = Db::instantiate();
        $query = 'SELECT bp.* FROM bestell_position bp
left join bestellung b on bp.bestellung_id = b.id 
where b.deleted_at is null and bp.deleted_at is null and b.kunde_id=? and b.ziel_datum=?;';
        $stmt = $db->prepare($query);
        $stmt->bind_param("is",$client_id,$date);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result || $result->num_rows == 0) {
            return false;
        }
        $positionObj = null;
        while ($position = $result->fetch_object()) {
            $params = ['id' => $position->id,
                'ba_id' => $position->bestell_artikel_id,
                'bId' => $position->bestellung_id,
                'pAmount' => $position->anzahl_paeckchen,
                'singleWeight' => $position->gewicht,
                'kommentar' => $position->kommentar,];
            $positionObj[] = Populate::populateBestellPosition($params);
        }
        return $positionObj;
    }

    /*
    public static function getLastDate() {
        $db = Db::instantiate();
        // Get last bill date
        $result1 = $db->query('SELECT datum FROM rechnung where deleted_at is null order by datum desc limit 1;');
        //if the result is empty the $result will be an object with numrows == 0 but if its an invalid statement like "nummer="
        if ($result1 && !$result1->num_rows == 0) {
            return strtotime($result1->fetch_assoc()['datum']);
        } else {
            return strtotime(date('Y-m-d') . " +60 days");
        }
    }
    */

    /**
     * Get selling infos for an article on a date
     * @param $articleId
     * @param $date
     * @return array|int
     */
    /*
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
    }*/

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
        $this->id = (int) $id;
    }

    /**
     * @return mixed
     */
    public function getBestellungId() {
        return $this->bestellung_id;
    }

    /**
     * @param mixed $bestellung_id
     */
    public function setBestellungId($bestellung_id) {
        $this->bestellung_id = (int) $bestellung_id;
    }

    /**
     * @return mixed
     */
    public function getBestellArtikelId() {
        return $this->bestell_artikel_id;
    }

    /**
     * @param mixed $bestell_artikel_id
     */
    public function setBestellArtikelId($bestell_artikel_id) {
        $this->bestell_artikel_id = (int) $bestell_artikel_id;
    }

    /**
     * @return mixed
     */
    public function getAnzahlPaeckchen() {
        return $this->anzahl_paeckchen;
    }

    /**
     * @param mixed $anzahl_paeckchen
     */
    public function setAnzahlPaeckchen($anzahl_paeckchen) {
        $this->anzahl_paeckchen = (int) $anzahl_paeckchen;
    }

    /**
     * @return mixed
     */
    public function getGewicht() {
        return $this->gewicht;
    }

    /**
     * @param mixed $gewicht
     */
    public function setGewicht($gewicht) {
        $this->gewicht = (int) $gewicht;
    }

    /**
     * @return mixed
     */
    public function getKommentar() {
        return $this->kommentar;
    }

    /**
     * @param mixed $kommentar
     */
    public function setKommentar($kommentar) {
        $this->kommentar = (string) $kommentar;
    }


}
