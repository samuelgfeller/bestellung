<?php
session_start();
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);
require_once __DIR__ . "/Local.php";

if ($path == 'artikel/gewicht') {
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
    Bestellartikel::checkPiece($_POST['id'], $_POST['value']);
    exit;
}

if ($path == 'order/check_email') {
    require __DIR__ . '/model/entity/Bestellung.php';
    $clientId = false;
    if($_POST['email'] != '') {
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

if($path == 'order/checkDefaultWeight'){
    require_once 'model/entity/Bestellartikel.php';
    echo Bestellartikel::getDefaultWeight($_POST['id']);
    exit;
}