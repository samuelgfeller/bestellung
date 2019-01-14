<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';

class Artikel {

    private $id;
    private $nummer;
    private $name;
    private $kg_price;
    private $stueck_gewicht;
    private $gewicht_1;
    private $gewicht_2;
    private $gewicht_3;
    private $stueckzahl_1;
    private $stueckzahl_2;
    private $stueckzahl_3;

    public static function findArtikelByBestellArtikel($ba_id) {
        $db = Db::instantiate();
        $query = 'SELECT a.* FROM bestell_artikel bp left join artikel a on bp.artikel_id = a.id WHERE bp.id= ' . $ba_id . ' and a.deleted_at is null;';
        $result = $db->query($query);
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            $artikelArr = $result->fetch_assoc();
            $artikel = populate::populateArtikel($artikelArr);
            return $artikel;
        }
    }

    public static function find($artikel_id) {
        $db = Db::instantiate();
        $query = 'SELECT * FROM artikel WHERE id= ' . $artikel_id . ' and deleted_at is null;';
        $result = $db->query($query);
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            $artikelArr = $result->fetch_assoc();
            $artikel = populate::populateArtikel($artikelArr);
            return $artikel;
        }
    }

    public static function checkIfHasOrderPossibility($artikel_id) {
        $db = Db::instantiate();
        $result = $db->query('SELECT gewicht_1,gewicht_2,gewicht_3,stueckzahl_1,stueckzahl_2,stueckzahl_3 
from artikel where id=' . $artikel_id);
        if (!$result || $result->num_rows == 0) {
            return false;
        }
        foreach ($artikel = $result->fetch_assoc() as $key => $value){
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


    /*    public function jsonSerialize(){
            return [
                'artikel' => [
                    'artikel' => $this->artikel,
                    'PLZ' => $this->PLZ,
                    'id' => $this->id
                ]
            ];
        }*/

}