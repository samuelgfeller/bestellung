<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';

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
        $db = Db::instantiate();
        $result = $db->query('INSERT INTO `bestell_position` (bestellung_id,bestell_artikel_id,anzahl_paeckchen,gewicht,kommentar) 
VALUES ("' . $position->getBestellungId() . '", "' . $position->getBestellArtikelId() . '", ' . $position->getAnzahlPaeckchen() . ', ' . $position->getGewicht() . ', "' . $position->getKommentar() . '")');
        Db::checkConnection($result);
        $last_id = $db->insert_id;
        return $last_id;
    }

    public static function del($id) {
        $db = Db::instantiate();
        $sql = $db->query('UPDATE `position` SET deleted_at=now() WHERE id=' . $id);
        Db::checkConnection($sql);
    }

    public static function getTotalOrderedWeightForBa($bestellArtikelId, $lastdate) {
        $db = Db::instantiate();

        // Get the sum
        $query = 'SELECT bp.anzahl_paeckchen anz, bp.gewicht gewicht FROM bestell_position bp
        left join bestellung b on bp.bestellung_id=b.id
        left join bestell_artikel ba on ba.id = bp.bestell_artikel_id 
        where b.deleted_at is null and ba.deleted_at is null and bp.deleted_at is null 
        and ba.id = ' . $bestellArtikelId . ' and b.datum > "' . date('Y-m-d', $lastdate) . '";';

        $result = $db->query($query);
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
    public function getBestellungId() {
        return $this->bestellung_id;
    }

    /**
     * @param mixed $bestellung_id
     */
    public function setBestellungId($bestellung_id) {
        $this->bestellung_id = $bestellung_id;
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
        $this->bestell_artikel_id = $bestell_artikel_id;
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
        $this->anzahl_paeckchen = $anzahl_paeckchen;
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
        $this->gewicht = $gewicht;
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
        $this->kommentar = $kommentar;
    }


}