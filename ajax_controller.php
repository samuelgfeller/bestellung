<?php
session_start();
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);
require_once __DIR__ . "/Local.php";

if ($path == 'artikel/gewicht'){
    require_once 'model/entity/Bestellartikel.php';

    Bestellartikel::updWeight($_POST['id'], $_POST['value']);
    exit;
}
if ($path == 'bestellArtikel/checkAvailable') {
    require_once 'model/entity/Bestellartikel.php';
    Bestellartikel::checkAvailable($_POST['id'], $_POST['value']);
    exit;
}
if ($path == 'bestellArtikel/checkPiece') {
    require_once 'model/entity/Bestellartikel.php';

    if(Bestellartikel::checkPieceWeight($_POST['artikel_id'])){
        Bestellartikel::checkPiece($_POST['artikel_id'], $_POST['value']);
    }else{
        echo 'false';
    }
    exit;
}
// @todo transform all dates into foreign keys from Termin

if ($path == 'order/check_email'){
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

