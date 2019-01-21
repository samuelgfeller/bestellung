<?php
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);
require_once __DIR__ . "/Local.php";

if ($path == 'artikel/gewicht') {
    require_once 'model/entity/OrderArticle.php';
    OrderArticle::updWeight($_POST['id'], $_POST['value']);
    exit;
}

if ($path == 'bestellArtikel/checkAvailable'){
    require_once 'model/entity/OrderArticle.php';
    require_once 'model/entity/Article.php';
    if($_POST['value'] === '1'){
        if(Article::checkIfHasOrderPossibility($_POST['artikel_id'])){
            OrderArticle::toggleAvailable($_POST['id'], $_POST['value']);
        }else{
            echo 'false';
        }
    }else{
        OrderArticle::toggleAvailable($_POST['id'], $_POST['value']);
    }
    exit;
}
// @todo transform all dates into foreign keys from Termin

if ($path == 'order/check_email') {
    require_once __DIR__ . '/model/entity/Order.php';
    $clientId = false;
    if ($_POST['email'] != '') {
        $clientId = Order::checkEmail($_POST['email']);
        if ($clientId) {
            $_SESSION['client'] = $clientId;
            echo 'true';
            exit;
        }
    }
    echo 'false';
    exit;
}

if ($path == 'order/checkDefaultWeight') {
    require_once 'model/entity/OrderArticle.php';
    echo OrderArticle::getDefaultWeight($_POST['id']);
    exit;
}
if ($path == 'order/getNextDate') {
    require_once 'model/entity/Appointment.php';
    echo json_encode(Appointment::getNextDate()['text']);
    exit;
}

if ($path == 'logout') {
    if($_SESSION && isset($_SESSION['client'])){
        unset($_SESSION['client']);
        session_destroy();
    }
    header('Location: /');
    exit;
}