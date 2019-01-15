<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

class Artikel {

    private $id;
    private $nummer;
    private $name;
    private $kg_price;
    private $stueck_gewicht;
    private $gewicht_1;
    private $gewicht_2;
    private $gewicht_3;
    private $gewicht_4;
    private $stueckzahl_1;
    private $stueckzahl_2;
    private $stueckzahl_3;
    private $stueckzahl_4;

    public static function findArtikelByBestellArtikel($ba_id) {
        $query = 'SELECT a.* FROM bestell_artikel bp left join artikel a on bp.artikel_id = a.id WHERE bp.id=? and a.deleted_at is null;';
        $dataArr = DataManagement::selectAndFetchSingleData($query, [$ba_id]);
        return PopulateObject::populateArtikel($dataArr);

    }

    public static function find($artikel_id) {
        $query = 'SELECT * FROM artikel WHERE id=? and deleted_at is null;';
        $dataArr = DataManagement::selectAndFetchSingleData($query, [$artikel_id]);
        return PopulateObject::populateArtikel($dataArr);

    }

    public static function checkIfHasOrderPossibility($artikel_id) {
        $query = 'SELECT gewicht_1,gewicht_2,gewicht_3,gewicht_4,stueckzahl_1,stueckzahl_2,stueckzahl_3,stueckzahl_4 from artikel where id=?;';
        $dataArr = DataManagement::selectAndFetchSingleData($query, [$artikel_id]);
	    foreach ($dataArr as $key => $value){
		    if ($value !== null){
			    return true;
		    }
	    }
        return false;
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getNummer() {
        return $this->nummer;
    }

    /**
     * @param null $nummer
     */
    public function setNummer($nummer) {
        $this->nummer = $nummer;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getKgPrice() {
        return $this->kg_price;
    }

    /**
     * @param mixed $kg_price
     */
    public function setKgPrice($kg_price) {
        $this->kg_price = $kg_price;
    }

    /**
     * @return mixed
     */
    public function getStueckGewicht() {
        return $this->stueck_gewicht;
    }

    /**
     * @param mixed $stueck_gewicht
     */
    public function setStueckGewicht($stueck_gewicht) {
        $this->stueck_gewicht = $stueck_gewicht;
    }

    /**
     * @return mixed
     */
    public function getGewicht1() {
        return $this->gewicht_1;
    }

    /**
     * @param mixed $gewicht_1
     */
    public function setGewicht1($gewicht_1) {
        $this->gewicht_1 = $gewicht_1;
    }

    /**
     * @return mixed
     */
    public function getGewicht2() {
        return $this->gewicht_2;
    }

    /**
     * @param mixed $gewicht_2
     */
    public function setGewicht2($gewicht_2) {
        $this->gewicht_2 = $gewicht_2;
    }

    /**
     * @return mixed
     */
    public function getGewicht3() {
        return $this->gewicht_3;
    }

    /**
     * @param mixed $gewicht_3
     */
    public function setGewicht3($gewicht_3) {
        $this->gewicht_3 = $gewicht_3;
    }

    /**
     * @return mixed
     */
    public function getStueckzahl1() {
        return $this->stueckzahl_1;
    }

    /**
     * @param mixed $stueckzahl_1
     */
    public function setStueckzahl1($stueckzahl_1) {
        $this->stueckzahl_1 = $stueckzahl_1;
    }

    /**
     * @return mixed
     */
    public function getStueckzahl2() {
        return $this->stueckzahl_2;
    }

    /**
     * @param mixed $stueckzahl_2
     */
    public function setStueckzahl2($stueckzahl_2) {
        $this->stueckzahl_2 = $stueckzahl_2;
    }

    /**
     * @return mixed
     */
    public function getStueckzahl3() {
        return $this->stueckzahl_3;
    }

    /**
     * @param mixed $stueckzahl_3
     */
    public function setStueckzahl3($stueckzahl_3) {
        $this->stueckzahl_3 = $stueckzahl_3;
    }
	
	/**
	 * @return mixed
	 */
	public function getGewicht4() {
		return $this->gewicht_4;
	}
	
	/**
	 * @param mixed $gewicht_4
	 */
	public function setGewicht4($gewicht_4) {
		$this->gewicht_4 = $gewicht_4;
	}
	
	/**
	 * @return mixed
	 */
	public function getStueckzahl4() {
		return $this->stueckzahl_4;
	}
	
	/**
	 * @param mixed $stueckzahl_4
	 */
	public function setStueckzahl4($stueckzahl_4) {
		$this->stueckzahl_4 = $stueckzahl_4;
	}



}