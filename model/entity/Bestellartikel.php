<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';

class Bestellartikel {

    private $bestell_artikel_id;
    private $artikel_id;
    private $gewicht;
    private $nummer;
    private $name;
    private $kg_price;
    private $verfuegbar;


    public static function all() {
        $artikelObj = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT ba.*, ba.id bestell_artikel_id, a.*, a.id artikel_id FROM bestell_artikel ba left join artikel a on ba.artikel_id=a.id where a.deleted_at is null and ba.deleted_at is null');
        while ($artikel = $result->fetch_object()) {
//            var_dump($artikel);
            $params = [
                'ba_id' => $artikel->bestell_artikel_id,
                'artikel_id' => $artikel->artikel_id,
                'kg_price' => $artikel->kg_price,
                'name' => $artikel->name,
                'nummer' => $artikel->nummer,
                'gewicht' => $artikel->gewicht,
                'verfuegbar' => $artikel->verfuegbar ];
            $artikelObj[] = Populate::populateArtikel($params);
        }
        return $artikelObj;
    }

    public static function allAvailable() {
        $artikelObj = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT ba.*,ba.id bestell_artikel_id, a.*, a.id artikel_id FROM bestell_artikel ba 
left join artikel a on ba.artikel_id=a.id 
where a.deleted_at is null and ba.deleted_at is null and ba.verfuegbar = 1');
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            while ($artikel = $result->fetch_object()) {
//            var_dump($artikel);
                $params = [
                    'ba_id' => $artikel->bestell_artikel_id,
                    'artikel_id' => $artikel->artikel_id,
                    'kg_price' => $artikel->kg_price,
                    'name' => $artikel->name,
                    'nummer' => $artikel->nummer,
                    'gewicht' => $artikel->gewicht,
                    'verfuegbar' => $artikel->verfuegbar ];
                $artikelObj[] = Populate::populateArtikel($params);
            }
        }
        return $artikelObj;
    }

    public static function checkAndRefresh() {
        $db = Db::instantiate();
        $result = $db->query('SELECT a.* FROM artikel a
LEFT OUTER JOIN bestell_artikel ba ON a.id = ba.artikel_id
WHERE ba.id IS NULL and a.deleted_at is null ;');
        $last_id = false;
        while ($artikelArr = $result->fetch_assoc()) {
            $result2 = $db->query('INSERT INTO bestell_artikel set artikel_id= ' . $artikelArr['id'] . ';');
            Db::checkConnection($result2);
            $last_id = $db->insert_id;
        }
        return $last_id;
    }

    public static function updWeight($id, $value) {
        $db = Db::instantiate();
        $sql = $db->query('UPDATE bestell_artikel SET gewicht="' . $value . '" WHERE id=' . $id);
        Db::checkConnection($sql);
    }

    public static function check($id, $value) {
        $db = Db::instantiate();
        $sql = $db->query('UPDATE bestell_artikel SET verfuegbar=' . $value . ' WHERE id=' . $id);
        Db::checkConnection($sql);
    }


    public static function find($id) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM bestell_artikel WHERE deleted_at is null and id =' . $id);
        $artikelArr = $result->fetch_assoc();
        $artikel = populate::populateArtikel($artikelArr);
        return $artikel;
    }

    public static function findWithNr($nr) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM bestell_artikel WHERE deleted_at is null and nummer =' . $nr);
        $artikelArr = $result->fetch_assoc();
        $artikel = populate::populateArtikel($artikelArr);
        return $artikel;
    }

    public static function findByName($name) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM bestell_artikel WHERE deleted_at is null and name="' . $name . '";');
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            $artikelArr = $result->fetch_assoc();
            $artikel = populate::populateArtikel($artikelArr);
            return $artikel;
        }
    }

    /**
     * @param Bestellartikel $artikel
     * @return null or int if id is set
     * @internal param $PLZ
     * @internal param null $id
     */
    public static function upd(Bestellartikel $artikel) {
        $db = Db::instantiate();
        $sql = $db->query('UPDATE bestell_artikel SET nummer=' . $artikel->getNummer() . ', name="' . $artikel->getName() . '",kg_price="' . $artikel->getKgPrice() . '" WHERE id =' . $artikel->getId());
        Db::checkConnection($sql);
        return $artikel->getId();
    }

    /**
     * @param Bestellartikel $artikel
     * @return int
     * @internal param $PLZ
     */
    public static function add(Bestellartikel $artikel) {
        $db = Db::instantiate();
        $result = $db->query('INSERT INTO bestell_artikel (nummer,name,kg_price) VALUES ("' . $artikel->getNummer() . '", "' . $artikel->getName() . '", "' . $artikel->getKgPrice() . '")');
        Db::checkConnection($result);
        $last_id = $db->insert_id;
        return $last_id;
    }

    /**
     * @param $id
     */
    public static function del($id) {
        $db = Db::instantiate();
        $sql = $db->query('UPDATE bestell_artikel SET deleted_at=now() WHERE id=' . $id);
        Db::checkConnection($sql);
    }

    public static function getSearchResult($inputVal) {
        $db = Db::instantiate();
        $result = $db->query("SELECT * FROM bestell_artikel WHERE deleted_at is null and name LIKE '%" . $inputVal . "%' OR nummer LIKE '%" . $inputVal . "%'");
        $artikelObj = null;

        while ($artikelArr = $result->fetch_assoc()) {
            $artikel = Populate::populateArtikel($artikelArr);
            $artikelObj[] = $artikel;
        }
        if (!empty($artikelObj)) {
            return $artikelObj;
        }
        return false;
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
    public function getNummer() {
        return $this->nummer;
    }

    /**
     * @param mixed $nummer
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
    public function getVerfuegbar() {
        return $this->verfuegbar;
    }

    /**
     * @param mixed $verfuegbar
     */
    public function setVerfuegbar($verfuegbar) {
        $this->verfuegbar = $verfuegbar;
    }




}