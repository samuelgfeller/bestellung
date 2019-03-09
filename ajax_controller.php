<?php
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);
require_once __DIR__ . "/Local.php";

if ($path == 'artikel/gewicht') {
    require_once 'model/entity/OrderArticle.php';
    require_once 'model/dao/OrderArticleDAO.php';
    OrderArticleDAO::updWeight($_POST['id'], $_POST['value']);
    exit;
}

if ($path == 'orderArticle/checkAvailable'){
    require_once 'model/entity/OrderArticle.php';
    require_once 'model/entity/Article.php';
    require_once 'model/dao/OrderArticleDAO.php';
    require_once 'model/dao/ArticleDAO.php';
    
    if($_POST['value'] === '1'){
        if(ArticleDAO::checkIfHasOrderPossibility($_POST['article_id'])){
            OrderArticleDAO::toggleAvailable($_POST['id'], $_POST['value']);
        }else{
            echo 'false';
        }
    }else{
        OrderArticleDAO::toggleAvailable($_POST['id'], $_POST['value']);
    }
    exit;
}
// @todo transform all dates into foreign keys from Appointment

if ($path == 'order/check_email') {
    require_once __DIR__ . '/model/entity/Order.php';
    require_once __DIR__ . '/model/dao/OrderDAO.php';
    $clientId = false;
    if ($_POST['email'] != '') {
        $clientId = OrderDAO::checkEmail($_POST['email']);
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
    require_once 'model/dao/OrderArticleDAO.php';
    echo OrderArticleDAO::getDefaultWeight($_POST['id']);
    exit;
}
if ($path == 'order/getNextDate') {
    require_once 'model/entity/Appointment.php';
    require_once 'model/dao/AppointmentDAO.php';
    echo json_encode(AppointmentDAO::getNextDate()['text']);
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

