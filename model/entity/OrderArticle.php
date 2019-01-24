<?php
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

//require_once __DIR__ . '/Appointment.php';


class OrderArticle
{
	
	private $order_article_id;
	private $article_id;
	private $weight;
	private $nr;
	private $name;
	private $kg_price;
	private $available; // Bool if available
	private $availableWeight;
	private $pieceWeight;
	private $date;
	private $avgWeight;
	
	
	public static function allFrom($date) {
		$query = 'SELECT ba.*, ba.id order_article_id, a.*, a.id article_id FROM order_article ba left join
 article a on ba.article_id=a.id where ba.date = ? and a.deleted_at is null and ba.deleted_at is null order by ba.article_id asc;';
		$result = DataManagement::selectAndFetchAssocMultipleData($query, [$date]);
		$dataObjArr = [];
		foreach ($result as $dataArr) {
			$dataArr['avgWeight'] = self::getAverage($dataArr['article_id']);
			$dataObjArr[] = PopulateObject::populateOrderArticle($dataArr);
		}
		return $dataObjArr;
	}
	
	public static function allAvailableFrom($date) {
		$query = 'SELECT ba.*,ba.id order_article_id, a.*, a.id article_id FROM order_article ba
left join article a on ba.article_id=a.id
where a.deleted_at is null and ba.deleted_at is null and ba.available = 1 and ba.date=?;';
		$result = DataManagement::selectAndFetchAssocMultipleData($query, [$date]);
		$dataObjArr = [];
		foreach ($result as $dataArr) {
			$dataObjArr[] = PopulateObject::populateOrderArticle($dataArr);
		}
		return $dataObjArr;
	}
	
	public static function checkAndRefresh() {
		$dates = Appointment::getYearsAndDates()['dates'];
		$articleRes = DataManagement::selectAndFetchAssocMultipleData('select * from article;');
		foreach ($articleRes as $article) {
			foreach ($dates as $date) {
				$sqlDate = date('Y-m-d', strtotime($date));
				$selectQuery = 'select * from order_article where article_id =? and date=?;';
				$ba = DataManagement::selectAndFetchSingleData($selectQuery, [$article['id'], $sqlDate]);
				
				// If an entry with the article_id and on the date exists, it has to be checked if it is deleted.
				// If not, then an new entry is made
				if (!$ba) {
					if ($article['deleted_at'] === null) {
						// Make the new inserts
						$baData = ['article_id' => $article['id'],
							'date' => $sqlDate,
						];
						DataManagement::insert('order_article', $baData);
					}
				} else if ($article['deleted_at'] !== null && $ba['deleted_at'] === null) {
					$delQuery = 'UPDATE order_article SET deleted_at=now() WHERE id=?;';
					DataManagement::run($delQuery, [$ba['id']]);
				}
				// Restore an already deleted article
				if ($article['deleted_at'] === null && $ba['deleted_at'] !== null) {
					$restoreQuery = 'UPDATE order_article SET deleted_at=NULL WHERE id=?;';
					DataManagement::run($restoreQuery, [$ba['id']]);
				}
			}
		}
		
	}
	
	public static function updWeight($id, $value) {
		$restoreQuery = 'UPDATE order_article SET weight=? WHERE id=?';
		$value = empty($value) ? null : $value;
		DataManagement::run($restoreQuery, [$value,$id]);
	}
	
	public static function toggleAvailable($id, $value) {
		$restoreQuery = 'UPDATE order_article SET weight=? WHERE id=?';
		DataManagement::run($restoreQuery, [$value,$id]);
	}
	
	/**
	 * @param $article_id
	 * @return int
	 */
	public static function getDefaultWeight($article_id) {
		$query = 'SELECT a.piece_weight FROM article a left join order_article ba on
ba.article_id = a.id WHERE a.deleted_at is null and ba.deleted_at is null and ba.id =?';
		return (int)DataManagement::selectAndFetchSingleData($query, [$article_id])['piece_weight'];
	}
	
	public static function getAverage($article_id) {
		$query = 'select p.*,sum(weight) average,count(*),r.date from position p left join bill r on r.id = p.bill_id
where article_id = ? and p.deleted_at is null and r.deleted_at is null
group by r.date ';
		$allData = DataManagement::selectAndFetchAssocMultipleData($query, [$article_id]);
		
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
		$query = 'SELECT password FROM admin limit 1;';
		$allData = DataManagement::selectAndFetchAssocMultipleData($query);
		foreach ($allData as $data){
			if (password_verify($entered_password, $data['password'])) {
				$_SESSION['is_admin'] = 1;
				session_regenerate_id();
				return true;
			}
		}
		return false;
	}
	
	public static function updPassword($password) {
		if (self::checkIfPasswordExists()) {
			$query = 'UPDATE admin set password=?';
		} else {
			$query = 'INSERT INTO admin (password) VALUES (?)';
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
	public function getOrderArticleId() {
		return $this->order_article_id;
	}
	
	/**
	 * @param mixed $order_article_id
	 */
	public function setOrderArticleId($order_article_id): void {
		$this->order_article_id = $order_article_id;
	}
	
	/**
	 * @return mixed
	 */
	public function getArticleId() {
		return $this->article_id;
	}
	
	/**
	 * @param mixed $article_id
	 */
	public function setArticleId($article_id): void {
		$this->article_id = $article_id;
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
	public function setWeight($weight): void {
		$this->weight = $weight;
	}
	
	/**
	 * @return mixed
	 */
	public function getNr() {
		return $this->nr;
	}
	
	/**
	 * @param mixed $nr
	 */
	public function setNr($nr): void {
		$this->nr = $nr;
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
	public function setName($name): void {
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
	public function setKgPrice($kg_price): void {
		$this->kg_price = $kg_price;
	}
	
	/**
	 * @return mixed
	 */
	public function getAvailable() {
		return $this->available;
	}
	
	/**
	 * @param mixed $available
	 */
	public function setAvailable($available): void {
		$this->available = $available;
	}
	
	/**
	 * @return mixed
	 */
	public function getAvailableWeight() {
		return $this->availableWeight;
	}
	
	/**
	 * @param mixed $availableWeight
	 */
	public function setAvailableWeight($availableWeight): void {
		$this->availableWeight = $availableWeight;
	}
	
	/**
	 * @return mixed
	 */
	public function getPieceWeight() {
		return $this->pieceWeight;
	}
	
	/**
	 * @param mixed $pieceWeight
	 */
	public function setPieceWeight($pieceWeight): void {
		$this->pieceWeight = $pieceWeight;
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
	public function setDate($date): void {
		$this->date = $date;
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
	public function setAvgWeight($avgWeight): void {
		$this->avgWeight = $avgWeight;
	}
	
}