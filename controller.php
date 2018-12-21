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
                    $artikelUndBestellPositionen[$key] = ['already_ordered' => false,
                        'bestell_artikel' => false];
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
                    /*                    highlight_string("<?php\n\$data =\n" . var_export($artikel, true) . ";\n?>");*/
//                    var_dump($artikel->getGewicht() ?? 0);
                    $artikel->setVerfuegbarGewicht(round(($artikel->getGewicht() ?? 0) - ($totalOrderedWeight ?? 0), 2));
                    if (!empty($artikel->getStueckbestellung())) {
                        $artikel->setStueckgewicht(Bestellartikel::getDefaultWeight($artikel->getBestellArtikelId()));
                    } else {
                        $artikel->setStueckgewicht(false);
                    }

                    $artikelUndBestellPositionen[$key]['bestell_artikel'] = $artikel;
                }
            }
//            https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
           // Sort that the article with most weight is at the top and those with 0 bottom
            $aWeight = [];
            foreach ($artikelUndBestellPositionen as $key => $row){
//                var_dump($key,$row);
                $aWeight[$key]['bestell_artikel'] = $row['bestell_artikel']->getVerfuegbarGewicht();
            }
            array_multisort($aWeight, SORT_DESC, $artikelUndBestellPositionen);
//        var_dump($artikelUndBestellPositionen);
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

    if ($_POST) {
        if(isset($_POST['password'])) {
            // The post parameter is set and the password got typed in
            $is_admin = Bestellartikel::checkPassword($_POST['password']);
            if ($is_admin) {
                $_SESSION['is_admin'] = 1;
            }
        }else if (isset($_POST['newPassword'])){
            // A new Password was typed in
            $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            Bestellartikel::updPassword($password);
        }
    }

    if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) {
        $datesYears = Termin::getYearsAndDates();
        $dates = $datesYears['dates'];
        $years = $datesYears['years'];

        Bestellartikel::checkAndRefresh();

        // If a dated is in the GET request, it shows the bills for this date
        if ($_GET && $_GET['datum']) {
            $datumGET = strtotime($_GET['datum']);
            $datum = date('d.m.Y', $datumGET);
            $datumSQL = date('Y-m-d', $datumGET);
            $allArtikel = Bestellartikel::allFrom($datumSQL);
            require __DIR__ . '/templates/article/all_artikel.html.php';
            exit;
        }
        //if not it only shows the dates
        $url = 'artikel'; // is needed in dates.html.php
        require_once __DIR__ . '/templates/pages/dates.html.php';
        exit;
    }

    if(!Bestellartikel::checkIfPasswordExists()){
        require_once __DIR__ . '/templates/article/update_password.html.php';
        exit;
    }

    // If user is not admin
    require_once __DIR__ . '/templates/article/login.html.php';
    exit;
}
if ($path == 'artikel/update/password') {
    if(!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) {
        require_once __DIR__ . '/templates/article/update_password.html.php';
        exit;
    }
    require_once __DIR__ . '/templates/article/login.html.php';
    exit;
}

if ($path == 'success') {
    require_once __DIR__ . '/model/entity/Bestellung.php';
    require_once __DIR__ . '/model/entity/Bestellposition.php';
    require_once __DIR__ . '/model/entity/Bestellartikel.php';

    if ($_POST && isset($_POST['pAmount'])) {
        $bestellungId = Bestellung::create($_SESSION['client'], $_POST['datum']);
        $valuesArr = [];
        for ($i = 0, $iMax = count($_POST['pAmount']); $i < $iMax; $i++) {
            if (!empty($_POST['pAmount'][$i]) || !empty($_POST['kommentar'][$i])) {
                $valuesArr[] = ['ba_id' => $_POST['ba_id'][$i],
                    'bId' => $bestellungId,
                    'pAmount' => $_POST['pAmount'][$i],
                    'singleWeight' => $_POST['singleWeight'][$i],
                    'kommentar' => $_POST['kommentar'][$i],];
            }
        }

        foreach ($valuesArr as $values) {
            $bestellPosition = Populate::populateBestellPosition($values);
            Bestellposition::add($bestellPosition);
        }

        if ($minId = Bestellung::checkMultipleOrdersAndGetOlder($_SESSION['client'], $_POST['datum'])) {
            $minId ? Bestellung::del($minId) : Bestellung::del($_POST['bestellung_id']);

        }
//        require_once __DIR__ . '/templates/success/success_bestellung.php';
        require_once __DIR__ . '/templates/pages/feedback.html.php';
        exit;
    }
    require_once __DIR__ . '/templates/success/success_bestellung.php';
    exit;
}

if ($path == 'artikel/dates') {
    require __DIR__ . '/model/entity/Termin.php';
    $datesYears = Termin::getYearsAndDates();
    $dates = $datesYears['dates'];
    $years = $datesYears['years'];
    $url = 'artikel';
    require_once __DIR__ . '/templates/pages/dates.html.php';
    exit;
}

if ($path == 'order/dates') {
    require __DIR__ . '/model/entity/Termin.php';
    $datesYears = Termin::getYearsAndDates();
    $dates = $datesYears['dates'];
    $years = $datesYears['years'];
    foreach ($dates as $key => $date) {
        if (strtotime($date) < time()) {
            unset($dates[$key]);
        }
    }
    $url = '';
    require_once __DIR__ . '/templates/pages/dates.html.php';
    exit;
}


if ($path == 'help') {
    require_once __DIR__ . '/templates/pages/help.html.php';
    exit;
}

if ($path == 'feedback') {
    require_once __DIR__ . '/templates/pages/feedback.html.php';
    exit;
}

if ($path == 'feedback/success') {
    require __DIR__ . '/model/entity/Feedback.php';
    if ($_POST && !empty($_POST['feedback'])) {

        Feedback::add($_POST['feedback'], $_SESSION['client']);
    }
    // @todo change feedback / Make own button and redirect to specific success
    require_once __DIR__ . '/templates/success/success_bestellung.php';
    exit;
}

