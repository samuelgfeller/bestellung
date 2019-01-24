<?php


require_once __DIR__ . '/../entity/Article.php';
require_once __DIR__ . '/../entity/OrderArticle.php';
require_once __DIR__ . '/../entity/OrderPosition.php';
require_once __DIR__ . '/../entity/Order.php';
require_once __DIR__ . '/../entity/Client.php';
require_once __DIR__ . '/../entity/Feedback.php';
require_once __DIR__ . '/../entity/Appointment.php';


class PopulateArray
{
	
	/**
	 * @param Article $article
	 * @return array
	 */
	public static function populateArticleArray(Article $article): array {
		return ['nr' => $article->getNr(),
			'name' => $article->getName(),
			'kg_price' => $article->getKgPrice(),
			'piece_weight' => $article->getPieceWeight(),
			'weight_1' => $article->getWeight1(),
			'weight_2' => $article->getWeight2(),
			'weight_3' => $article->getWeight3(),
			'weight_4' => $article->getWeight4(),
			'piece_amount_1' => $article->getPieceAmount1(),
			'piece_amount_2' => $article->getPieceAmount2(),
			'piece_amount_3' => $article->getPieceAmount3(),
			'piece_amount_4' => $article->getPieceAmount4(),];
	}
	
	public static function populateClientArray(Client $client): array {
		return ['first_name' => $client->getFirstName(),
			'name' => $client->getName(),
			'address' => $client->getAddress(),
			'place_id' => $client->getPlaceId(),
			'phone' => $client->getPhone(),
			'phone_mobile' => $client->getPhoneMobile(),
			'email' => $client->getEmail(),
			'person_amount' => $client->getPersonAmount(),
			'siedfleisch' => $client->getSiedfleisch(),
			'remark' => $client->getRemark(),
			'id' => $client->getId(),
			'deleted_at' => $client->getDeletedAt(),];
	}
	
	public static function populateAppointmentArray(Appointment $appointment): array {
		return [
			'id' => $appointment->getId(),
			'date' => $appointment->getDate(),
		];
	}
	
	public static function populateOrderArray(Order $order): array {
		return [
			'id' => $order->getId(),
			'client_id' => $order->getClientId(),
			'date' => $order->getDate(),
		];
	}
	
	public static function populateOrderPositionArray(OrderPosition $position): array {
		return [
			'id' => $position->getId(),
			'order_id' => $position->getOrderId(),
			'order_article_id' => $position->getOrderArticleId(),
			'package_amount' => $position->getPackageAmount(),
			'weight' => $position->getWeight(),
			'comment' => $position->getComment(),
		];
	}
	
	
}