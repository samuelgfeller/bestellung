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
    require __DIR__ . '/model/entity/Termin.php';
    require __DIR__ . '/model/service/Helper.php';

    if (!empty($_SESSION['client'])) {
        if ($_GET && $_GET['datum']) {
            $client = Client::find($_SESSION['client']);
            $GETDateSQL = date('Y-m-d', strtotime($_GET['datum']));
            $GETDateText = date('d.m.Y', strtotime($_GET['datum']));

            // Get all positions of the order if the client already did one for this date
            $alreadyOrdered = Bestellposition::getIfAlreadyOrdered($client->getId(), $GETDateSQL);

            // Initialise the variable with the order id for the html
            $bestellung_id = $alreadyOrdered ? $alreadyOrdered[0]->getBestellungId() : '';

            // Get all bestell_artikel which are available (verfügbar=1)
            $bestellArtikel = Bestellartikel::allAvailableFrom($GETDateSQL);

            $artikelUndBestellPositionen = false;

            if ($bestellArtikel) {
                $artikelUndBestellPositionen = [];
                foreach ($bestellArtikel as $key => $artikel) {
                    // Initialising array with default values
                    $artikelUndBestellPositionen[$key] = ['already_ordered' => false, 'bestell_artikel' => false];
                    $weightToSubstrate = 0;

                    if ($alreadyOrdered) {
                        // Finding the position with the same bestell_artikel_id
                        foreach ($alreadyOrdered as $position) {
//                            var_dump($position);
                            if ($position->getBestellArtikelId() == $artikel->getBestellArtikelId()) {
                                // Wenn das Gewicht kleiner als 15 ist heisst es, dass es Stückzahlen sind
                                if ($position->getGewicht() <= 15) {
                                    // Die Anzahl Stücke mit dem Stückgewicht (Standardgewicht) multiplizieren (Resultate ist in Gramm)
                                    $alreadyOrderedWeight = $position->getGewicht() * Bestellartikel::getDefaultWeight($artikel->getBestellArtikelId());
                                } //Wenn höher als 15 ist es direkt ein Gewict in Gramm
                                else {
                                    $alreadyOrderedWeight = $position->getGewicht();
                                }
                                $weightToSubstrate += $position->getAnzahlPaeckchen() * ($alreadyOrderedWeight / 1000);

                                $artikelUndBestellPositionen[$key] = ['already_ordered' => $position];
                            }
                        }
                    }

                    // Get all the ordered weight and amount for all orders for a date
                    $orderedWeightAndAmounts = Bestellposition::getTotalOrderedWeightForBa($artikel->getBestellArtikelId(), $GETDateSQL);
                    $totalOrderedWeight = 0;
                    if ($orderedWeightAndAmounts) {
                        // Loop over each ordered weight
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
                        }
                    }

                    // Add the already ordered weight of this customer to the global available weight
//                    var_dump('befire: '.$totalOrderedWeight,$weightToSubstrate);
                    $totalOrderedWeight -= $weightToSubstrate;
//                    var_dump('after: '.$totalOrderedWeight);
//                    var_dump('calc: '.$artikel->getGewicht() .'-'. $totalOrderedWeight );
                    // Verfügbares Gewicht
                    $artikel->setVerfuegbarGewicht(round($artikel->getGewicht() - ($totalOrderedWeight ?? 0), 2));
                    if (!empty($artikel->getStueckbestellung())) {
                        $artikel->setStueckgewicht(Bestellartikel::getDefaultWeight($artikel->getBestellArtikelId()));
                    } else {
                        $artikel->setStueckgewicht(false);
                    }

                    $artikelUndBestellPositionen[$key]['bestell_artikel'] = $artikel;
                }
            }
//        var_dump($bestellArtikel);
            require __DIR__ . '/templates/order/order.html.php';
            exit;
        } else {
            $url = '?datum=' . Termin::getNextDate()['text'];
            header("Location: " . $url);
            exit;
        }
    }
    require __DIR__ . '/templates/home/home.html.php';
    exit;
}

if ($path == 'artikel') {
    require __DIR__ . '/model/entity/Bestellartikel.php';
    require __DIR__ . '/model/entity/Termin.php';

    $dates = Termin::getTextDates();
    Bestellartikel::checkAndRefresh();

    // If a date is in the GET request, it shows the bills for this date
    if ($_GET && $_GET['datum']) {
        $datumGET = strtotime($_GET['datum']);
        $datum = date('d.m.Y', $datumGET);
        $datumSQL = date('Y-m-d', $datumGET);
        $allArtikel = Bestellartikel::allFrom($datumSQL);
        require __DIR__ . '/templates/article/all_artikel.html.php';
        exit;
    }
    //if not it only shows the dates
    require_once __DIR__ . '/templates/article/dates.html.php';
    exit;
}

if ($path == 'success') {
    require_once __DIR__ . '/model/entity/Bestellung.php';
    require_once __DIR__ . '/model/entity/Bestellposition.php';
    require_once __DIR__ . '/model/entity/Bestellartikel.php';

    if ($_POST) {
                $bestellungId = Bestellung::create($_SESSION['client'], $_POST['datum']);
        $valuesArr = [];
        for ($i = 0, $iMax = count($_POST['pAmount']); $i < $iMax; $i++) {
            if (!empty($_POST['pAmount'][$i]) || !empty($_POST['kommentar'][$i])) {
                $valuesArr[] = ['ba_id' => $_POST['ba_id'][$i], 'bId' => $bestellungId, 'pAmount' => $_POST['pAmount'][$i], 'singleWeight' => $_POST['singleWeight'][$i], 'kommentar' => $_POST['kommentar'][$i],];
            }
        }
        foreach ($valuesArr as $values) {
            $bestellPosition = Populate::populateBestellPosition($values);
            Bestellposition::add($bestellPosition);
        }

        if (!empty($_POST['bestellung_id'])){
            Bestellung::del($_POST['bestellung_id']);
        }

        require_once __DIR__ . '/templates/success/success_bestellung.php';
        exit;
    }
}

if ($path == 'artikel/dates') {
    require __DIR__ . '/model/entity/Termin.php';
    $dates = Termin::getTextDates();
    require_once __DIR__ . '/templates/article/dates.html.php';
    exit;
}

if ($path == 'order/dates') {
    require __DIR__ . '/model/entity/Termin.php';
    $dates = Termin::getTextDates();
    foreach ($dates as $key => $date) {
        if (strtotime($date) < time()) {
            unset($dates[$key]);
        }
    }
    require_once __DIR__ . '/templates/order/dates.html.php';
    exit;
}
