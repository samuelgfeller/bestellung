<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';

//require_once __DIR__ . '/Termin.php';


class Bestellartikel {

    private $bestell_artikel_id;
    private $artikel_id;
    private $gewicht;
    private $nummer;
    private $name;
    private $kg_price;
    private $verfuegbar; // Bool if available
    private $verfuegbarGewicht;
    private $stueckbestellung;
    private $stueckgewicht;
    private $datum;
    private $avgWeight;


    public static function all() {
        $artikelObj = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT ba.*, ba.id bestell_artikel_id, a.*, a.id artikel_id FROM bestell_artikel ba 
left join artikel a on ba.artikel_id=a.id where a.deleted_at is null and ba.deleted_at is null');
        while ($artikel = $result->fetch_object()) {
//            var_dump($artikel);
            $params = ['ba_id' => $artikel->bestell_artikel_id,
                'artikel_id' => $artikel->artikel_id,
                'kg_price' => $artikel->kg_price,
                'name' => $artikel->name,
                'nummer' => $artikel->nummer,
                'gewicht' => $artikel->gewicht,
                'verfuegbar' => $artikel->verfuegbar,
                'stueckbestellung' => $artikel->stueckbestellung,
                'datum' => $artikel->datum];
            $artikelObj[] = Populate::populateBestellArtikel($params);
        }
        return $artikelObj;
    }

    public static function allFrom($date) {
        $artikelObj = [];
        $db = Db::instantiate();
        $query = 'SELECT ba.*, ba.id bestell_artikel_id, a.*, a.id artikel_id FROM bestell_artikel ba left join
 artikel a on ba.artikel_id=a.id where ba.datum = "' . $date . '" and a.deleted_at is null and ba.deleted_at is null order by ba.artikel_id asc';
        $result = $db->query($query);
        while ($artikel = $result->fetch_object()) {
            $average = self::getAverage($artikel->artikel_id);
            $params = ['ba_id' => $artikel->bestell_artikel_id,
                'artikel_id' => $artikel->artikel_id,
                'kg_price' => $artikel->kg_price,
                'name' => $artikel->name,
                'nummer' => $artikel->nummer,
                'gewicht' => $artikel->gewicht,
                'verfuegbar' => $artikel->verfuegbar,
                'stueckbestellung' => $artikel->stueckbestellung,
                'datum' => $artikel->datum,
                'avgWeight' => $average];
            $artikelObj[] = Populate::populateBestellArtikel($params);
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
                $params = ['ba_id' => $artikel->bestell_artikel_id,
                    'artikel_id' => $artikel->artikel_id,
                    'kg_price' => $artikel->kg_price,
                    'name' => $artikel->name,
                    'nummer' => $artikel->nummer,
                    'gewicht' => $artikel->gewicht,
                    'verfuegbar' => $artikel->verfuegbar,
                    'stueckbestellung' => $artikel->stueckbestellung,
                    'datum' => $artikel->datum];
                $artikelObj[] = Populate::populateBestellArtikel($params);
            }
        }
        return $artikelObj;
    }

    public static function allAvailableFrom($date) {
        $artikelObj = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT ba.*,ba.id bestell_artikel_id, a.*, a.id artikel_id FROM bestell_artikel ba 
left join artikel a on ba.artikel_id=a.id 
where a.deleted_at is null and ba.deleted_at is null and ba.verfuegbar = 1 and ba.datum="' . $date . '";');
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            while ($artikel = $result->fetch_object()) {
//            var_dump($artikel);
                $params = ['ba_id' => $artikel->bestell_artikel_id,
                    'artikel_id' => $artikel->artikel_id,
                    'kg_price' => $artikel->kg_price,
                    'name' => $artikel->name,
                    'gewicht' => $artikel->gewicht,
                    'verfuegbar' => $artikel->verfuegbar,
                    'stueckbestellung' => $artikel->stueckbestellung,
                    'datum' => $artikel->datum];
                $artikelObj[] = Populate::populateBestellArtikel($params);
            }
        }
        return $artikelObj;
    }

    public static function checkAndRefresh() {
        $db = Db::instantiate();
        $dates = Termin::getYearsAndDates()['dates'];

        $articleRes = $db->query('select * from artikel;');
        if ($articleRes || $articleRes->num_rows != 0) {
            while ($article = $articleRes->fetch_assoc()) {
                foreach ($dates as $date) {
                    $sqlDate = date('Y-m-d', strtotime($date));
                    $selectQuery = 'select * from bestell_artikel where artikel_id = ' . $article['id'] . ' and datum="' . $sqlDate . '"';
                    $ba = $db->query($selectQuery);
                    // If an entry with the article_id and on the date exists, it has to be checked if it is deleted.
                    // If not, then an new entry is made
                    if ($article['deleted_at'] == null && (!$ba || $ba->num_rows === 0)) {
                        // Make the new inserts
                        $insertBaQuery = 'insert into bestell_artikel set artikel_id=' . $article['id'] . ', datum="' . $sqlDate . '"';
                        $insertBa = $db->query($insertBaQuery);
                        Db::checkConnection($insertBa, $insertBaQuery);
                    } else {
                        $baArr = $ba->fetch_assoc();

                        // Go only if there is already a ba, the article is deleted but the ba not
                        if ($baArr != null && $article['deleted_at'] != null && $baArr['deleted_at'] == null) {
                            $delQuery = 'UPDATE bestell_artikel SET deleted_at=now() WHERE id=' . $baArr['id'] . ';';
                            $delBa = $db->query($delQuery);
                            Db::checkConnection($delBa, $delQuery);
//                            var_dump('deleted',$delQuery);
                        }

                        // Restore an already deleted article
                        if ($baArr != null && $article['deleted_at'] == null && $baArr['deleted_at'] != null) {
                            $restoreQuery = 'UPDATE bestell_artikel SET deleted_at=NULL WHERE id=' . $baArr['id'] . ';';
                            $restoreBa = $db->query($restoreQuery);
                            Db::checkConnection($restoreBa, $restoreQuery);
//                            var_dump('restored',$restoreQuery);
                        }
                    }
                }
            }
        }
    }

    public static function updWeight($id, $value) {
        $db = Db::instantiate();
        // Set value to NULL if empty so that the value is not an empty string
        $value = empty($value) ? NULL : $value;
        $query = 'UPDATE bestell_artikel SET gewicht=' . (float)$value . ' WHERE id=' . $id;
        $sql = $db->query($query);
        Db::checkConnection($sql, $query);
    }

    public static function toggleAvailable($id, $value) {
        $db = Db::instantiate();
        $query = 'UPDATE bestell_artikel SET verfuegbar=' . $value . ' WHERE id=' . $id;
        $sql = $db->query($query);
        Db::checkConnection($sql, $query);
    }

    public static function checkPiece($artikel_id, $value) {
        $db = Db::instantiate();
//        $query = 'UPDATE bestell_artikel SET stueckbestellung=' . $value . ' WHERE id=' . $id;
        $query = 'UPDATE bestell_artikel SET stueckbestellung=' . $value . ' WHERE artikel_id=' . $artikel_id;
        $sql = $db->query($query);
        Db::checkConnection($sql, $query);
    }

    /**
     * @param $article_id
     * @return int
     */
    public static function getDefaultWeight($article_id) {
        $db = Db::instantiate();
        $query = 'SELECT a.stueck_gewicht FROM artikel a left join bestell_artikel ba on 
ba.artikel_id = a.id WHERE a.deleted_at is null and ba.deleted_at is null and ba.id =' . $article_id;
        $result = $db->query($query);

        return (int)$result->fetch_assoc()['stueck_gewicht'];
    }


    public static function find($id) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM bestell_artikel WHERE deleted_at is null and id =' . $id);
        $artikelArr = $result->fetch_assoc();
        $artikel = populate::populateBestellArtikel($artikelArr);
        return $artikel;
    }

    public static function findWithNr($nr) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM bestell_artikel WHERE deleted_at is null and nummer =' . $nr);
        $artikelArr = $result->fetch_assoc();
        $artikel = populate::populateBestellArtikel($artikelArr);
        return $artikel;
    }

    public static function findByName($name) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM bestell_artikel WHERE deleted_at is null and name="' . $name . '";');
        if (!$result || $result->num_rows == 0) {
            return false;
        } else {
            $artikelArr = $result->fetch_assoc();
            $artikel = populate::populateBestellArtikel($artikelArr);
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
        $query = 'UPDATE bestell_artikel SET nummer=' . $artikel->getNummer() . ', name="' . $artikel->getName() . '",kg_price="' . $artikel->getKgPrice() . '" WHERE id =' . $artikel->getId();
        $sql = $db->query($query);
        Db::checkConnection($sql, $query);
        return $artikel->getId();
    }

    /**
     * @param Bestellartikel $artikel
     * @return int
     * @internal param $PLZ
     */
    public static function add(Bestellartikel $artikel) {
        $db = Db::instantiate();
        $query = 'INSERT INTO bestell_artikel (nummer,name,kg_price) VALUES ("' . $artikel->getNummer() . '", "' . $artikel->getName() . '", "' . $artikel->getKgPrice() . '")';
        $result = $db->query($query);
        Db::checkConnection($result, $query);
        $last_id = $db->insert_id;
        return $last_id;
    }

    /**
     * @param $id
     */
    public static function del($id) {
        $db = Db::instantiate();
        $query = 'UPDATE bestell_artikel SET deleted_at=now() WHERE id=' . $id;
        $sql = $db->query($query);
        Db::checkConnection($sql, $query);
    }

    public static function getSearchResult($inputVal) {
        $db = Db::instantiate();
        $query = "SELECT * FROM bestell_artikel WHERE deleted_at is null and name LIKE '%" . $inputVal . "%' OR nummer LIKE '%" . $inputVal . "%'";
        $result = $db->query($query);
        $artikelObj = null;

        while ($artikelArr = $result->fetch_assoc()) {
            $artikel = Populate::populateBestellArtikel($artikelArr);
            $artikelObj[] = $artikel;
        }
        if (!empty($artikelObj)) {
            return $artikelObj;
        }
        return false;
    }

    public static function checkPieceWeight($artikel_id) {
        $db = Db::instantiate();
        $result = $db->query('SELECT stueck_gewicht from artikel where id=' . $artikel_id);
        if (empty($result->fetch_assoc()['stueck_gewicht']) || !$result || $result->num_rows == 0) {
            return false;
        }
        return true;
    }

    public static function getAverage($artikel_id) {
        $db = Db::instantiate();
        $query = 'select p.*,sum(gewicht) average,count(*),r.datum from position p left join rechnung r on r.id = p.rechnung_id 
where artikel_id = ' . $artikel_id . ' and p.deleted_at is null and r.deleted_at is null
group by r.datum ';
        $result = $db->query($query);
        if (!$result || $result->num_rows == 0) {
            return false;
        }
        // Array with all sums per date
        $sums = [];
        while ($res = $result->fetch_assoc()) {
            $sums[] = $res['average'];
        }
        $average = null;
        // Calculating the average
        if (count($sums)) {
            $sums = array_filter($sums);
            $average = array_sum($sums) / count($sums);
        }
        return round($average, 2);
    }

    public static function register($password) {

    }

    public static function checkPassword($entered_password) {
        $db = Db::instantiate();
        $query = 'SELECT passwort FROM admin limit 1;';

        $stmt = $db->prepare($query);

        $stmt->execute();

        $stmt->bind_result($passwort);

        $stmt->fetch();
        //@todo loop over all passwords to check if correct
        if (password_verify($entered_password, $passwort)) {
            $_SESSION['is_admin'] = 1;
            session_regenerate_id();
            return true;
        }
        return false;
    }

    public static function updPassword($password) {
        $db = Db::instantiate();
        if (self::checkIfPasswordExists()) {
            $query = 'UPDATE admin set passwort=?';
        } else {
            $query = 'INSERT INTO admin (passwort) VALUES (?)';
        }
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $password);
        $stmt->execute();
    }

    public static function checkIfPasswordExists() {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM admin');
        if (!$result || $result->num_rows == 0) {
            return false;
        }
        return true;
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
        $this->gewicht = (float)$gewicht;
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
        $this->bestell_artikel_id = (int)$bestell_artikel_id;
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

    /**
     * @return mixed
     */
    public function getVerfuegbarGewicht() {
        return $this->verfuegbarGewicht;
    }

    /**
     * @param mixed $verfuegbarGewicht
     */
    public function setVerfuegbarGewicht($verfuegbarGewicht) {
        $this->verfuegbarGewicht = $verfuegbarGewicht;
    }

    /**
     * @return mixed
     */
    public function getStueckbestellung() {
        return $this->stueckbestellung;
    }

    /**
     * @param mixed $stueckbestellung
     */
    public function setStueckbestellung($stueckbestellung) {
        $this->stueckbestellung = $stueckbestellung;
    }

    /**
     * @return mixed
     */
    public function getStueckgewicht() {
        return $this->stueckgewicht;
    }

    /**
     * @param mixed $stueckgewicht
     */
    public function setStueckgewicht($stueckgewicht) {
        $this->stueckgewicht = $stueckgewicht;
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

    /**
     * @return mixed
     */
    public function getAvgWeight() {
        return $this->avgWeight;
    }

    /**
     * @param mixed $avgWeight
     */
    public function setAvgWeight($avgWeight) {
        $this->avgWeight = $avgWeight;
    }


}