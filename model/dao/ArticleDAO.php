<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/PopulateArray.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/Helper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/service/DataManagement.php';

class ArticleDAO
{
	public static function findArticleByOrderArticle($ba_id) {
		$query = 'SELECT a.* FROM order_article bp left join article a on bp.article_id = a.id WHERE bp.id=? and a.deleted_at is null;';
		$dataArr = DataManagement::selectAndFetchSingleData($query, [$ba_id]);
		return PopulateObject::populateArticle($dataArr);
		
	}
	
	public static function find($article_id) {
		$query = 'SELECT * FROM article WHERE id=? and deleted_at is null;';
		$dataArr = DataManagement::selectAndFetchSingleData($query, [$article_id]);
		return PopulateObject::populateArticle($dataArr);
	}
	
	public static function checkIfHasOrderPossibility($article_id) {
		$query = 'SELECT weight_1,weight_2,weight_3,weight_4,piece_amount_1,piece_amount_2,piece_amount_3,piece_amount_4 from article where id=?;';
		$dataArr = DataManagement::selectAndFetchSingleData($query, [$article_id]);
		foreach ($dataArr as $key => $value){
			if ($value !== null){
				return true;
			}
		}
		return false;
	}
}