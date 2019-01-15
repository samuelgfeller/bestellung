<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

//require_once __DIR__ . '/Termin.php';


class Bestellartikel
{
	
	private $bestell_artikel_id;
	private $artikel_id;
	private $gewicht;
	private $nummer;
	private $name;
	private $kg_price;
	private $verfuegbar; // Bool if available
	private $verfuegbarGewicht;
	private $stueckGewicht;
	private $datum;
	private $avgWeight;
	
	
	public static function allFrom($date) {
		$query = 'SELECT ba.*, ba.id bestell_artikel_id, a.*, a.id artikel_id FROM bestell_artikel ba left join
 artikel a on ba.artikel_id=a.id where ba.datum = ? and a.deleted_at is null and ba.deleted_at is null order by ba.artikel_id asc;';
		$result = DataManagement::selectAndFetchAssocMultipleData($query, [$date]);
		$dataObjArr = [];
		foreach ($result as $dataArr) {
			$dataArr['avgWeight'] = self::getAverage($dataArr['artikel_id']);
			$dataObjArr[] = PopulateObject::populateBestellArtikel($dataArr);
		}
		return $dataObjArr;
	}
	
	public static function allAvailableFrom($date) {
		$query = 'SELECT ba.*,ba.id bestell_artikel_id, a.*, a.id artikel_id FROM bestell_artikel ba
left join artikel a on ba.artikel_id=a.id
where a.deleted_at is null and ba.deleted_at is null and ba.verfuegbar = 1 and ba.datum=?;';
		$result = DataManagement::selectAndFetchAssocMultipleData($query, [$date]);
		$dataObjArr = [];
		foreach ($result as $dataArr) {
			$dataObjArr[] = PopulateObject::populateBestellArtikel($dataArr);
		}
		return $dataObjArr;
	}
	
	public static function checkAndRefresh() {
		$dates = Termin::getYearsAndDates()['dates'];
		$articleRes = DataManagement::selectAndFetchAssocMultipleData('select * from artikel;');
		foreach ($articleRes as $article) {
			foreach ($dates as $date) {
				$sqlDate = date('Y-m-d', strtotime($date));
				$selectQuery = 'select * from bestell_artikel where artikel_id =? and datum=?;';
				$ba = DataManagement::selectAndFetchSingleData($selectQuery, [$article['id'], $sqlDate]);
				
				// If an entry with the article_id and on the date exists, it has to be checked if it is deleted.
				// If not, then an new entry is made
				if (!$ba) {
					if ($article['deleted_at'] === null) {
						// Make the new inserts
						$baData = ['artikel_id' => $article['id'],
							'datum' => $sqlDate,
						];
						DataManagement::insert('bestell_artikel', $baData);
					}
				} else if ($article['deleted_at'] !== null && $ba['deleted_at'] === null) {
					$delQuery = 'UPDATE bestell_artikel SET deleted_at=now() WHERE id=?;';
					DataManagement::run($delQuery, [$ba['id']]);
				}
				// Restore an already deleted article
				if ($article['deleted_at'] === null && $ba['deleted_at'] !== null) {
					$restoreQuery = 'UPDATE bestell_artikel SET deleted_at=NULL WHERE id=?;';
					DataManagement::run($restoreQuery, [$ba['id']]);
				}
			}
		}
		
	}
	
	public static function updWeight($id, $value) {
		$restoreQuery = 'UPDATE bestell_artikel SET gewicht=? WHERE id=?';
		$value = empty($value) ? null : $value;
		DataManagement::run($restoreQuery, [$value,$id]);
	}
	
	public static function toggleAvailable($id, $value) {
		$restoreQuery = 'UPDATE bestell_artikel SET gewicht=? WHERE id=?';
		DataManagement::run($restoreQuery, [$value,$id]);
	}
	
	/**
	 * @param $article_id
	 * @return int
	 */
	public static function getDefaultWeight($article_id) {
		$query = 'SELECT a.stueck_gewicht FROM artikel a left join bestell_artikel ba on
ba.artikel_id = a.id WHERE a.deleted_at is null and ba.deleted_at is null and ba.id =?';
		return (int)DataManagement::selectAndFetchSingleData($query, [$article_id])['stueck_gewicht'];
	}
	
	public static function getAverage($artikel_id) {
		$query = 'select p.*,sum(gewicht) average,count(*),r.datum from position p left join rechnung r on r.id = p.rechnung_id
where artikel_id = ? and p.deleted_at is null and r.deleted_at is null
group by r.datum ';
		$allData = DataManagement::selectAndFetchAssocMultipleData($query, [$artikel_id]);
		
		// Array with all sums per date
		$sums = [];
		foreach ($allData as $data){
			$sums[] = $data['average'];
		}
		if ($sums) {
			$average = null;
			// Calculating the average
			if (count($sums)) {
				$sums = array_filter($sums);
				$average = array_sum($sums) / count($sums);
			}
			return round($average, 2);
		}
		return null;
	}
	
	public static function register($password) {
	
	}
	
	public static function checkPassword($entered_password) {
		$query = 'SELECT passwort FROM admin limit 1;';
		$allData = DataManagement::selectAndFetchAssocMultipleData($query);
		foreach ($allData as $data){
			if (password_verify($entered_password, $data['passwort'])) {
				$_SESSION['is_admin'] = 1;
				session_regenerate_id();
				return true;
			}
		}
		return false;
	}
	
	public static function updPassword($password) {
		if (self::checkIfPasswordExists()) {
			$query = 'UPDATE admin set passwort=?';
		} else {
			$query = 'INSERT INTO admin (passwort) VALUES (?)';
		}
		DataManagement::run($query,[$password]);
	}
	
	public static function checkIfPasswordExists() {
		$query = 'SELECT * FROM admin';
		if(DataManagement::selectAndFetchSingleData($query)){
			return true;
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
	public function getStueckGewicht() {
		return $this->stueckGewicht;
	}
	
	/**
	 * @param mixed $stueckGewicht
	 */
	public function setStueckGewicht($stueckGewicht) {
		$this->stueckGewicht = $stueckGewicht;
	}
	
}