<?php


require_once __DIR__ . '/model/service/Flash.php';

require_once __DIR__ . '/templates/base.html.php'; //base.html.php url;
require_once __DIR__ . '/Local.php';

//number inside the url
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);

if ($path == '') {
    require __DIR__ . '/model/entity/Bestellposition.php';
    require __DIR__ . '/model/entity/Bestellartikel.php';
    require __DIR__ . '/model/entity/Bestellung.php';
    require __DIR__ . '/model/entity/Client.php';

    if (!empty($_SESSION['client'])) {
        $client = Client::find($_SESSION['client']);
        $bestellArtikel = Bestellartikel::allAvailable();
        $lastdate = Bestellposition::getLastDate();

        foreach ($bestellArtikel as $artikel) {
            $orderedWeightAndAmounts = Bestellposition::getTotalOrderedWeightForBa($artikel->getBestellArtikelId(), $lastdate);
            $totalOrderedWeight = 0;
//            var_dump($orderedWeightAndAmounts);
            if($orderedWeightAndAmounts) {
                foreach ($orderedWeightAndAmounts as $orderedWeightAndAmount) {

                    // Wenn das Gewicht kleiner als 15 ist heisst es, dass es Stückzahlen sind
                    if ($orderedWeightAndAmount['gewicht'] <= 15) {
                        // Die Anzahl Stücke mit dem Stückgewicht (Standardgewicht) multiplizieren (Resultate ist in Gramm)
                        $weight = $orderedWeightAndAmount['gewicht'] * Bestellartikel::getDefaultWeight($artikel->getBestellArtikelId());
                    } //Wenn höher als 15 ist es direkt ein Gewict in Gramm
                    else {
                        $weight = $orderedWeightAndAmount['gewicht'];
                    }
                    // Anzahl Pakete mit gewicht multiplizieren
                    $totalOrderedWeight += $orderedWeightAndAmount['anz'] * ($weight / 1000);
//            var_dump($orderedWeightAndAmount['anz'].' * '.$weight.' = '.$totalOrderedWeight);
                }
            }

            // Verfügbares Gewicht
            $artikel->setVerfuegbarGewicht(round($artikel->getGewicht() - ($totalOrderedWeight ?? 0),2));
            if (!empty($artikel->getStueckbestellung())){
                $artikel->setStueckgewicht(Bestellartikel::getDefaultWeight($artikel->getBestellArtikelId()));
            }else{
                $artikel->setStueckgewicht(false);
            }

        }
//        var_dump($bestellArtikel);
        require __DIR__ . '/templates/order/order.html.php';
        exit;
    }
    require __DIR__ . '/templates/home/home.html.php';
    exit;
}

if ($path == 'artikel') {
    require __DIR__ . '/model/entity/Bestellartikel.php';
    Bestellartikel::checkAndRefresh();
    $allArtikel = Bestellartikel::all();
    require __DIR__ . '/templates/article/all_artikel.html.php';
    exit;
}

if ($path == 'success') {
    require_once __DIR__ . '/model/entity/Bestellung.php';
    require_once __DIR__ . '/model/entity/Bestellposition.php';
    require_once __DIR__ . '/model/entity/Bestellartikel.php';

    $bestellungId = Bestellung::create($_SESSION['client']);
    $valuesArr = [];
    for ($i = 0, $iMax = count($_POST['pAmount']); $i < $iMax; $i++) {
        if (!empty($_POST['pAmount'][$i]) || !empty($_POST['kommentar'][$i])){
            $valuesArr[] = [
                'baId' => $_POST['baId'][$i],
                'bId' => $bestellungId,
                'pAmount' => $_POST['pAmount'][$i],
                'singleWeight' => $_POST['singleWeight'][$i],
                'kommentar' => $_POST['kommentar'][$i],
                ];
        }
    }
    foreach ($valuesArr as $values) {
        $bestellPosition = Populate::populateBestellPosition($values);
        Bestellposition::add($bestellPosition);
    }

    require_once __DIR__ . '/templates/success/success_bestellung.php';
    exit;
}