<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/PopulateArray.php';
require_once __DIR__ . '/../service/Helper.php';
require_once __DIR__ . '/../service/DataManagement.php';


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
        $data = PopulateArray::populateBestellpositionArray($position);
	    return DataManagement::insert('bestell_position', $data);
    }

    public static function del($id) {
	    $query = 'UPDATE `position` SET deleted_at=now() WHERE id=?';
	    DataManagement::run($query, [$id]);
    }

    public static function getTotalOrderedWeightForBa($bestellArtikelId, $nextDate) {
	    $query = 'SELECT bp.anzahl_paeckchen anz, bp.gewicht gewicht FROM bestell_position bp
        left join bestellung b on bp.bestellung_id=b.id
        left join bestell_artikel ba on ba.id = bp.bestell_artikel_id
        where b.deleted_at is null and ba.deleted_at is null and bp.deleted_at is null
        and ba.id = ? and b.ziel_datum = ?;';
	    return DataManagement::selectAndFetchAssocMultipleData($query, [$bestellArtikelId, $nextDate]);
    }

    public static function getIfAlreadyOrdered($client_id, $date) {
	    $query = 'SELECT bp.* FROM bestell_position bp
left join bestellung b on bp.bestellung_id = b.id
where b.deleted_at is null and bp.deleted_at is null and b.kunde_id=? and b.ziel_datum=?;';
	    $allData = DataManagement::selectAndFetchAssocMultipleData($query, [$client_id, $date]);
	    $allDataObj = [];
	    foreach ($allData as $allDataArr) {
		    $allDataObj[] = PopulateObject::populateBestellPosition($allDataArr);
	    }
	    return $allDataObj;
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