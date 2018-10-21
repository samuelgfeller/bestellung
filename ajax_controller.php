<?php
session_start();
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);
require_once __DIR__ . "/Local.php";

if ($path == 'artikel/gewicht') {
    require_once 'model/entity/Bestellartikel.php';
    Bestellartikel::updWeight($_POST['id'], $_POST['value']);
    exit;
}
if($path == 'bestell_artikel/check'){
    require_once 'model/entity/Bestellartikel.php';
    Bestellartikel::check($_POST['id'], $_POST['value']);
    exit;
}
if ($path == 'order/check_email') {
    require __DIR__ . '/model/entity/Bestellung.php';
    $clientId = Bestellung::checkEmail($_POST['email']);
    if($clientId){
        $_SESSION['client'] = $clientId;
        echo 'true';
        exit;
    }
    echo 'false';
    exit;
}
if ($path == 'scan/bill') {
    // on page reload it creates a new bill; the database doesn't keep a history. Only an id of the article so if it gets changed,
    // in the position it will get updated
    require_once __DIR__ . '/model/entity/Position.php';
    require_once __DIR__ . '/model/entity/Rechnung.php';
    require_once __DIR__ . '/model/entity/Client.php';
    $client = Client::find($_POST['customerId']);

    require_once __DIR__ . '/model/entity/Ort.php';
    $ort = !empty($client->getOrtId()) ? Ort::find($client->getOrtId()) : null;
    require_once __DIR__ . '/model/entity/Artikel.php';
    //    if (Rechnung::findByClient($client->getId()) && Rechnung::findByClient($client->getId())->getDate() != date('Y-n-j') ){
    //        $rechnung = Rechnung::findByClient($client->getId());
    //        $rechnungId = $rechnung->getId();
    //    } else{
    //The only parameter is the client_id because it's the only thing we know about yet and the date is set later
    $rechnungData = ['kunde_id' => $client->getId()];
    //Bill has only client_id
    $rechnung = Populate::populateRechnung($rechnungData);
    $rechnungId = Rechnung::add($rechnung);
    // check if bill exists in databse. If yes - delete it
    if($rechnungId && isset($_POST['bill_id'])){
        if(Rechnung::checkIfExists($_POST['bill_id'])){
            Rechnung::del($_POST['bill_id']);
        }
    }
    $scans = [];
    $i = 1;
    foreach ($_POST as $barcodeNr) {
        if (!empty($barcodeNr)) {
            if ($barcodeNr != $_POST['customerId'] && (!isset($_POST['bill_id']) || $barcodeNr != $_POST['bill_id'])) {
                //get the nine numbers on the right
                $nineRight = substr($barcodeNr, strlen($barcodeNr) - 9);
                //the first 3 are the article number
                $artikelNr = floatval(substr($nineRight, 0, 3));
                //the last 6 are the centimes
                $centimes = floatval(substr($nineRight, strlen($nineRight) - 6));
                //devide and round the centimes to get CHF
                $price = round($centimes / 1000, 2);
                //get the article
                $artikel = Artikel::findByNummer($artikelNr);
                //get the kgprice
                $kgPrice = $artikel->getKgPrice();
                //calc the weight
                $weight = round($price / $kgPrice, 3);

                $scans[] = ['number' => $i, 'weight' => $weight, 'kgPrice' => $kgPrice, 'name' => $artikel->getName(), 'price' => $price,];
                $i++;
                $positionData = ['weight' => $weight, 'price' => $price, 'rechnung_id' => $rechnungId, 'artikel_id' => $artikel->getId(),];
                $postition = Populate::populatePosition($positionData);
                $positionId = $postition::add($postition);
            }
        }
    }

    require_once __DIR__ . '/templates/bill/bill.html.php';
    exit;
}