<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateArray.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/Helper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/DataManagement.php';
class OrderArticleDAO
{

	public static function allFrom($date) {
        $previousDate = AppointmentDAO::getDateBeforeDate($date);

        $query = 'SELECT ba.*, ba.id order_article_id, a.*, a.id article_id FROM order_article ba left join
 article a on ba.article_id=a.id where ba.date = ? and a.deleted_at is null and ba.deleted_at is null 
order by a.position ASC, position IS NULL;';
		$result = DataManagement::selectAndFetchAssocMultipleData($query, [$date]);
		$dataObjArr = [];
		foreach ($result as $dataArr) {
			$dataArr['avgWeight'] = self::getAverage($dataArr['article_id']);
			$dataArr['soldWeightLastDate'] = self::getSoldWeightFor($dataArr['article_id'],$previousDate);
			$dataObjArr[] = PopulateObject::populateOrderArticle($dataArr);
		}
		return $dataObjArr;
	}

	public static function allAvailableFrom($date) {
		$query = 'SELECT ba.*,ba.id order_article_id, a.*, a.id article_id FROM order_article ba
left join article a on ba.article_id=a.id
where a.deleted_at is null and ba.deleted_at is null and ba.available = 1 and ba.date=? 
order by a.position ASC, position IS NULL;';
		$result = DataManagement::selectAndFetchAssocMultipleData($query, [$date]);
		$dataObjArr = [];
		foreach ($result as $dataArr) {
			$dataObjArr[] = PopulateObject::populateOrderArticle($dataArr);
		}
		return $dataObjArr;
	}

	public static function checkAndRefresh() {
		$dates = AppointmentDAO::getYearsAndDates()['dates'];
		$articleRes = DataManagement::selectAndFetchAssocMultipleData('select * from article;');
		foreach ($articleRes as $article) {
			foreach ($dates as $date) {
				$sqlDate = date('Y-m-d', strtotime($date));
//				Intentionally also taking deleted ba if there is one to later restore it
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
				}
                // If article is not deleted but $ba is deleted then it has to be restored
                else if ($article['deleted_at'] === null && $ba['deleted_at'] !== null) {
                    // Restore an already deleted order article
                    $restoreQuery = 'UPDATE order_article SET deleted_at=NULL WHERE id=?;';
                    DataManagement::run($restoreQuery, [$ba['id']]);
                } else if ($article['deleted_at'] !== null && $ba['deleted_at'] === null) {
					$delQuery = 'UPDATE order_article SET deleted_at=now() WHERE id=?;';
					DataManagement::run($delQuery, [$ba['id']]);
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
		$restoreQuery = 'UPDATE order_article SET available=? WHERE id=?';
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
	    // Get averages per date
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

    public static function getSoldWeightFor($article_id,$date)
    {
        $query = 'select p.*,sum(weight) average,count(*),r.date from position p left join bill r on r.id = p.bill_id
where article_id = ? AND `date` = ? and p.deleted_at is null and r.deleted_at is null
group by r.date ';
        $sellingInfos = DataManagement::selectAndFetchSingleData($query, [$article_id,$date]);
        if ($sellingInfos){
            return round($sellingInfos['average'], 3);
        }
        return null;
	}

/*    public static function upd(OrderArticle $orderArticle) {
        $data = PopulateArray::populateOrderArticleArray($orderArticle);
        // Unset id and position because we dont want them to change
        unset($data['id']);
        $updData = Helper::getUpdateStringAndValues($data);
        $query = 'UPDATE article SET ' . implode(', ', $updData['updString']) . ' WHERE id = ?';
        $updData['values'][] = $orderArticle->getOrderArticleId();
        DataManagement::run($query, $updData['values']);
    }*/

    public static function importPreviousData($dateSQL) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/model/dao/AppointmentDAO.php';
        $previousDate = AppointmentDAO::getDateBeforeDate($dateSQL);
        $query = 'SELECT * FROM order_article where date=? and deleted_at is null';
        $previousData = DataManagement::selectAndFetchAssocMultipleData($query,[$previousDate]);
        foreach ($previousData as $data){
//            var_dump($data);
            $query = 'UPDATE order_article SET weight = ?, available = ? WHERE article_id = ? and `date`=?;';
            DataManagement::run($query, [$data['weight'],$data['available'],$data['article_id'],$dateSQL]);
        }
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




}