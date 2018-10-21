<?php


require_once __DIR__ . '/model/service/Flash.php';

require_once __DIR__. '/templates/base.html.php'; //base.html.php url;
require_once __DIR__ . '/Local.php';

//number inside the url
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);

if ($path == '') {
    require __DIR__ . '/model/entity/Bestellosition.php';
    require __DIR__ . '/model/entity/Bestellartikel.php';
    require __DIR__ . '/model/entity/Bestellung.php';
    require __DIR__ . '/model/entity/Client.php';

    if(!empty($_SESSION['client'])){
        $client = Client::find($_SESSION['client']);
        $bestellArtikel = Bestellartikel::allAvailable();
        $lastdate = Bestellosition::getLastDate();
        foreach ($bestellArtikel as $artikel){
            $totalOrderedWeight = Bestellosition::getTotalOrderedWeightForBa($artikel->getBestellArtikelId(),$lastdate);
            $vGewicht = $totalOrderedWeight - $artikel->getGewicht();
        }

        var_dump($bestellArtikel);
        require __DIR__ . '/templates/order/order.html.php';
        exit;
    }
    require __DIR__ . '/templates/home/home.html.php';
    exit;
}

if ($path == 'artikel') {
    require __DIR__ . '/model/entity/Bestellartikel.php';
    Bestellartikel::checkAndRefresh();
    $allArtikel=Bestellartikel::all();
    require __DIR__ . '/templates/article/all_artikel.html.php';
    exit;
}
if ($path == 'email/success') {
    require __DIR__ . '/model/success/success_email.php';
    exit;
}
