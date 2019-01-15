<?php
session_start();
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);
require_once __DIR__ . "/Local.php";

if ($path == 'artikel/gewicht') {
    require_once 'model/entity/Bestellartikel.php';
    Bestellartikel::updWeight($_POST['id'], $_POST['value']);
    exit;
}

if ($path == 'bestellArtikel/checkAvailable'){
    require_once 'model/entity/Bestellartikel.php';
    require_once 'model/entity/Artikel.php';
    if($_POST['value'] === '1'){
        if(Artikel::checkIfHasOrderPossibility($_POST['artikel_id'])){
            Bestellartikel::toggleAvailable($_POST['id'], $_POST['value']);
        }else{
            echo 'false';
        }
    }else{
        Bestellartikel::toggleAvailable($_POST['id'], $_POST['value']);
    }
    exit;
}
// @todo transform all dates into foreign keys from Termin

if ($path == 'order/check_email') {
    require __DIR__ . '/model/entity/Bestellung.php';
    $clientId = false;
    if ($_POST['email'] != '') {
        $clientId = Bestellung::checkEmail($_POST['email']);
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
    require_once 'model/entity/Bestellartikel.php';
    echo Bestellartikel::getDefaultWeight($_POST['id']);
    exit;
}
if ($path == 'order/getNextDate') {
    require_once 'model/entity/Termin.php';
    echo json_encode(Termin::getNextDate()['text']);
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