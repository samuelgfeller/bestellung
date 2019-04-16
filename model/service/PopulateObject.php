<?php

require_once __DIR__ . '/../entity/Article.php';
require_once __DIR__ . '/../entity/OrderPosition.php';
require_once __DIR__ . '/../entity/OrderArticle.php';
require_once __DIR__ . '/../entity/Order.php';
require_once __DIR__ . '/../entity/Client.php';
require_once __DIR__ . '/../entity/Appointment.php';
require_once __DIR__ . '/../entity/Feedback.php';
require_once __DIR__ . '/../entity/Unit.php';
require_once __DIR__ . '/../service/Helper.php';

class PopulateObject {

    /**
     * @param $data
     * @return Client
     */
    public static function populateClient($data): \Client {
        $client = new Client();
        $client->setFirstName( Helper::ckVal($data['first_name'] ?? null));
        $client->setName(Helper::ckVal($data['name'] ?? null));
        $client->setAddress(Helper::ckVal($data['address'] ?? null));
        $client->setPlaceId(Helper::ckVal($data['place_id'] ?? null));
        $client->setPhone(Helper::ckVal($data['phone'] ?? null));
        $client->setPhoneMobile(Helper::ckVal($data['phone_mobile'] ?? null));
        $client->setEmail(Helper::ckVal($data['email'] ?? null));
        $client->setPersonAmount(Helper::ckVal($data['person_amount'] ?? null));
        $client->setSiedfleisch(Helper::ckVal($data['siedfleisch'] ?? null));
        $client->setRemark(Helper::ckVal($data['remark'] ?? null));
        $client->setId(Helper::ckVal($data['id'] ?? null));
        $client->setDeletedAt(Helper::ckVal($data['deleted_at'] ?? null));
        return $client;
    }


    /**
     * @param $data
     * @return OrderArticle
     */
    public static function populateOrderArticle($data): \OrderArticle {
        $article = new OrderArticle();
        $article->setOrderArticleId(Helper::ckVal($data['order_article_id'] ?? null));
        $article->setArticleId(Helper::ckVal($data['article_id'] ?? null));
        $article->setNr(Helper::ckVal($data['nr'] ?? null));
        $article->setName(Helper::ckVal($data['name'] ?? null));
        $article->setKgPrice(Helper::ckVal($data['kg_price'] ?? null));
        $article->setWeight(Helper::ckVal($data['weight'] ?? null));
        $article->setAvailable(Helper::ckVal($data['available'] ?? null));
        $article->setDate(Helper::ckVal($data['date'] ?? null));
        $article->setAvgWeight(Helper::ckVal($data['avgWeight'] ?? null));
        return $article;
    }

    /**
     * @param $data
     * @return OrderPosition
     */
    public static function populateOrderPosition($data): \OrderPosition {
        $position = new OrderPosition();
        $position->setId(Helper::ckVal($data['id'] ?? null));
        $position->setOrderId(Helper::ckVal($data['order_id'] ?? null));
        $position->setOrderArticleId(Helper::ckVal($data['order_article_id'] ?? null));
        $position->setPackageAmount(Helper::ckVal($data['package_amount'] ?? null));
        $position->setWeight(Helper::ckVal($data['weight'] ?? null));
        $position->setComment(Helper::ckVal($data['comment'] ?? null));
        return $position;
    }

    public static function populateOrder($data): \Order {
        $order = new Order();
        $order->setId(Helper::ckVal($data['id'] ?? null));
        $order->setClientId(Helper::ckVal($data['date'] ?? null));
        $order->setDate(Helper::ckVal($data['client_id'] ?? null));
        $order->setRemark(Helper::ckVal($data['remark'] ?? null));
        $order->setTargetDate(Helper::ckVal($data['target_date'] ?? null));
        return $order;
    }

    public static function populateAppointment($data): \Appointment {
        $appointment = new Appointment();
        $appointment->setId(Helper::ckVal($data['id'] ?? null));
        $appointment->setDate(Helper::ckVal($data['date'] ?? null));
        return $appointment;
    }

    /**
     * @param $data
     * @return Article
     */
    public static function populateArticle($data): \Article {
        $article = new Article();
        $article->setId(Helper::ckVal($data['id'] ?? null));
        $article->setNr(Helper::ckVal($data['nr'] ?? null));
        $article->setName(Helper::ckVal($data['name'] ?? null));
        $article->setKgPrice(Helper::ckVal($data['kg_price'] ?? null));
        $article->setPieceWeight(Helper::ckVal($data['piece_weight'] ?? null));
        $article->setWeight1(Helper::ckVal($data['weight_1'] ?? null));
        $article->setWeight2(Helper::ckVal($data['weight_2'] ?? null));
        $article->setWeight3(Helper::ckVal($data['weight_3'] ?? null));
        $article->setWeight4(Helper::ckVal($data['weight_4'] ?? null));
        $article->setPieceAmount1(Helper::ckVal($data['piece_amount_1'] ?? null));
        $article->setPieceAmount2(Helper::ckVal($data['piece_amount_2'] ?? null));
        $article->setPieceAmount3(Helper::ckVal($data['piece_amount_3'] ?? null));
        $article->setPieceAmount4(Helper::ckVal($data['piece_amount_4'] ?? null));
        $article->setUnitId(Helper::ckVal($data['unit_id'] ?? null));
        return $article;
    }

    public static function populateUnit($data) {
        if ($data) {
            $unit = new Unit();
            $unit->setId(Helper::ckVal($data['id']));
            $unit->setName(Helper::ckVal($data['name'] ?? null));
            $unit->setShortName(Helper::ckVal($data['short_name'] ?? null));
            $unit->setEqual1000Gram(Helper::ckVal($data['equal_1000_gram'] ?? null));
            $unit->setIsDefault(Helper::ckVal($data['is_default'] ?? null));
            return $unit;
        }
        return false;
    }

}