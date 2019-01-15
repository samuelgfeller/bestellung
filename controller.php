<?php
require_once __DIR__ . '/model/service/Flash.php';

//require_once __DIR__ . '/templates/base.html.php'; //base.html.php url;
require_once __DIR__ . '/Local.php';

//number inside the url
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);

if ($path == '') {
	require_once __DIR__ . '/model/entity/Bestellposition.php';
	require_once __DIR__ . '/model/entity/Bestellartikel.php';
	require_once __DIR__ . '/model/entity/Bestellung.php';
	require_once __DIR__ . '/model/entity/Client.php';
	require_once __DIR__ . '/model/entity/Termin.php';
	require_once __DIR__ . '/model/entity/Artikel.php';
	
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
				foreach ($bestellArtikel as $key => $ba) {
					// Initialising array with default values
					$artikelUndBestellPositionen[$key] = ['already_ordered' => false,
						'bestell_artikel' => false,
						'artikel' => false];
					$weightToSubstrate = 0;
					
					if ($alreadyOrdered) {
						// Finding the position with the same bestell_artikel_id
						foreach ($alreadyOrdered as $position) {
//                            var_dump($position);
							if ($position->getBestellArtikelId() == $ba->getBestellArtikelId()) {
								// Wenn das Gewicht kleiner als 15 ist heisst es, dass es Stückzahlen sind
								if ($position->getGewicht() <= 15) {
									// Die Anzahl Stücke mit dem Stückgewicht (Standardgewicht) multiplizieren (Resultate ist in Gramm)
									$alreadyOrderedWeight = $position->getGewicht() * Bestellartikel::getDefaultWeight($ba->getBestellArtikelId());
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
					$orderedWeightAndAmounts = Bestellposition::getTotalOrderedWeightForBa($ba->getBestellArtikelId(), $GETDateSQL);
					$totalOrderedWeight = 0;
					if ($orderedWeightAndAmounts) {
						// Loop over each ordered weight
						foreach ($orderedWeightAndAmounts as $orderedWeightAndAmount) {
							
							// Wenn das Gewicht kleiner als 15 ist heisst es, dass es Stückzahlen sind
							if ($orderedWeightAndAmount['gewicht'] <= 15) {
								// Die Anzahl Stücke mit dem Stückgewicht (Standardgewicht) multiplizieren (Resultate ist in Gramm)
								$weight = $orderedWeightAndAmount['gewicht'] * Bestellartikel::getDefaultWeight($ba->getBestellArtikelId());
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
					
					$availableWeight = round(($ba->getGewicht() ?? 0) - ($totalOrderedWeight ?? 0), 2);
					if ($availableWeight === (float)-0) {
						$ba->setVerfuegbarGewicht(0);
					} else {
						$ba->setVerfuegbarGewicht($availableWeight);
					}
					
					$pieceWeight = Bestellartikel::getDefaultWeight($ba->getBestellArtikelId());
					$ba->setStueckgewicht($pieceWeight);
					
					$artikelUndBestellPositionen[$key]['bestell_artikel'] = $ba;
					$artikel = Artikel::find($ba->getArtikelId());
					$avag = $ba->getVerfuegbarGewicht() * 1000; // Available weight in gramm
					$g1 = $artikel->getGewicht1();
					$g2 = $artikel->getGewicht2();
					$g3 = $artikel->getGewicht3();
					$s1 = $artikel->getStueckzahl1();
					$s2 = $artikel->getStueckzahl2();
					$s3 = $artikel->getStueckzahl3();
//                    var_dump($s3.' * '.$pieceWeight,$s3*$pieceWeight,$avag);
					$artikelUndBestellPositionen[$key]['order_possibilities'] = [!empty($g1) && $g1 <= $avag ? $g1 : null,
						!empty($g2) && $g2 <= $avag ? $g2 : null,
						!empty($g3) && $g3 <= $avag ? $g3 : null,
						!empty($s1) && $s1 * $pieceWeight <= $avag ? $s1 : null,
						!empty($s2) && $s2 * $pieceWeight <= $avag ? $s2 : null,
						!empty($s3) && $s3 * $pieceWeight <= $avag ? $s3 : null,];
//                    var_dump($artikelUndBestellPositionen[$key]['order_possibilities']);
				}
			}
//            https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
			// Sort that the article with most weight is at the top and those with 0 bottom
			if ($artikelUndBestellPositionen) {
				$aWeight = [];
				foreach ($artikelUndBestellPositionen as $key => $row) {
//                var_dump($key,$row);
					$aWeight[$key]['bestell_artikel'] = $row['bestell_artikel']->getVerfuegbarGewicht();
				}
				array_multisort($aWeight, SORT_DESC, $artikelUndBestellPositionen);
			}
			require __DIR__ . '/templates/order/order.html.php';
			exit;
		}
		$date = Termin::getNextDate()['text'];
		if ($date) {
			$url = '?datum=' . $date;
			header("Location: " . $url);
			exit;
		}
		require __DIR__ . '/templates/pages/noEntries.html.php';
		exit;
	}
	require __DIR__ . '/templates/home/home.html.php';
	exit;
}

if ($path == 'artikel') {
	require __DIR__ . '/model/entity/Bestellartikel.php';
	require __DIR__ . '/model/entity/Termin.php';
	
	if ($_POST) {
		if (isset($_POST['password'])) {
			// The post parameter is set and the password got typed in
			$is_admin = Bestellartikel::checkPassword($_POST['password']);
			if ($is_admin) {
				$_SESSION['is_admin'] = 1;
			}
		} else if (isset($_POST['newPassword'])) {
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
			$allBa = Bestellartikel::allFrom($datumSQL);
			require __DIR__ . '/templates/article/all_artikel.html.php';
			exit;
		}
		//if not it only shows the dates
		$url = 'artikel'; // is needed in dates.html.php
		require_once __DIR__ . '/templates/pages/dates.html.php';
		exit;
	}
	
	if (!Bestellartikel::checkIfPasswordExists()) {
		require_once __DIR__ . '/templates/article/update_password.html.php';
		exit;
	}
	
	// If user is not admin
	require_once __DIR__ . '/templates/article/login.html.php';
	exit;
}
if ($path == 'artikel/update/password') {
	if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) {
		require_once __DIR__ . '/templates/article/update_password.html.php';
		exit;
	}
	require_once __DIR__ . '/templates/article/login.html.php';
	exit;
}

if ($path == 'success') {
	require_once __DIR__ . '/model/entity/Bestellung.php';
	require_once __DIR__ . '/model/entity/Client.php';
	require_once __DIR__ . '/model/entity/Bestellposition.php';
	require_once __DIR__ . '/model/entity/Bestellartikel.php';
	require_once __DIR__ . '/model/entity/Artikel.php';
	require_once __DIR__ . '/model/service/Helper.php';
	require_once __DIR__ . '/model/service/Email.php';
	
	if ($_POST && isset($_POST['pAmount'])) {
		$client = Client::find($_SESSION['client']);
		$bestellungId = Bestellung::create($client->getId(), htmlspecialchars($_POST['datum']));
		$valuesArr = [];
		for ($i = 0, $iMax = count($_POST['pAmount']); $i < $iMax; $i++) {
			if (!empty($_POST['pAmount'][$i]) || !empty($_POST['kommentar'][$i])) {
				$valuesArr[] = ['bestell_artikel_id' => (int)$_POST['ba_id'][$i],
					'bestellung_id' => $bestellungId,
					'anzahl_paeckchen' => (int)$_POST['pAmount'][$i],
					'gewicht' => (int)$_POST['singleWeight'][$i],
					'kommentar' => htmlspecialchars($_POST['kommentar'][$i]),];
				
			}
		}
		
		foreach ($valuesArr as $values) {
			$bestellPosition = PopulateObject::populateBestellPosition($values);
			Bestellposition::add($bestellPosition);
		}
		
		// Delete old order
		if ($minId = Bestellung::checkMultipleOrdersAndGetOlder($_SESSION['client'], $_POST['datum'])) {
			$minId ? Bestellung::del($minId) : Bestellung::del($_POST['bestellung_id']);
		}
		
		// Send confirmation email
		$positionDaten = [];
		foreach ($valuesArr as $values) {
			$artikel = Artikel::findArtikelByBestellArtikel($values['bestell_artikel_id']);
			if (!empty($artikel)) {
				$positionDaten[] = ['artikel_name' => $artikel->getName(),
					'anzahl_paeckchen' => $values['anzahl_paeckchen'],
					'gewicht' => $values['gewicht'],
					'kommentar' => $values['kommentar'],
					'stueck_gewicht' => $artikel->getStueckGewicht(),];
			}
		}
		
		$mail = new Email();
		ob_start();
		include __DIR__ . '/templates/success/confirmation_mail.php';
		$mailBody = ob_get_clean();
		$mail->prepare('Bestellbestätigung für den ' . date('d.m.Y', strtotime($_POST['datum'])), $mailBody);
		$mail->send($client->getEmail(), 'info@masesselin.ch', $client->getVorname() . ' ' . $client->getName(), 'Masesselin');

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
	foreach ($datesYears['dates'] as $key => $date) {
		if (strtotime($date) < time()) {
			unset($datesYears['dates'][$key]);
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
	require __DIR__ . '/model/entity/Client.php';
	require __DIR__ . '/model/service/Email.php';
	if ($_POST && !empty($_POST['feedback'])) {
		$client = Client::find($_SESSION['client']);
		Feedback::add($_POST['feedback'], $client->getId());
		$mail = new Email();
		$mailBody = nl2br($_POST['feedback']);
		$fullName = $client->getVorname() . ' ' . $client->getName();
		$mail->prepare('Feedback von ' . $fullName, $mailBody);
		$mail->send('info@masesselin.ch' . '', $client->getEmail(), 'Masesselin', $fullName);
	}
	// @todo change feedback / Make own button and redirect to specific success
	require_once __DIR__ . '/templates/success/success_bestellung.php';
	exit;
}

if ($path == 'mail') {
	require __DIR__ . '/model/service/Email.php';
	require __DIR__ . '/model/service/Helper.php';

//    echo Helper::prepareHtmlMailBody('asdf');


//	if($_POST && $_POST['mail'] && $_POST['subject']){
//		echo $_POST['mail'];
//		$mail = new Email();
//		$mail->prepare($_POST['subject'],$_POST['mail']);
//		$mail->send('samuelgfeller@bluewin.ch','info@masesselin.ch','Samuel Gfeller','Masesselin');
//		exit;
//	}
	$positionDaten = [0 => ["id" => "7",
		"artikel_name" => "Apfelsaft",
		"anzahl_paeckchen" => "1",
		"gewicht" => "100000",
		"kommentar" => "",
		"stueck_gewicht" => "5000",],
		1 => ["id" => "8",
			"artikel_name" => "Äpfel",
			"anzahl_paeckchen" => "2",
			"gewicht" => "5",
			"kommentar" => "",
			"stueck_gewicht" => "1000",],
		2 => ["id" => "9",
			"artikel_name" => "Äpfel, Harasse 20kg",
			"anzahl_paeckchen" => "10",
			"gewicht" => "1000",
			"kommentar" => "",
			"stueck_gewicht" => "20000",],
		3 => ["id" => "10",
			"artikel_name" => "Faux Filet",
			"anzahl_paeckchen" => "2",
			"gewicht" => "500",
			"kommentar" => "",
			"stueck_gewicht" => "300",],
		4 => ["id" => "11",
			"artikel_name" => "Rump-Steak",
			"anzahl_paeckchen" => "2",
			"gewicht" => "100",
			"kommentar" => "",
			"stueck_gewicht" => "650",],
		5 => ["id" => "12",
			"artikel_name" => "Entrecote",
			"anzahl_paeckchen" => "2",
			"gewicht" => "1000",
			"kommentar" => "",
			"stueck_gewicht" => "180",],
		6 => ["id" => "13",
			"artikel_name" => "Steak",
			"anzahl_paeckchen" => "2",
			"gewicht" => "500",
			"kommentar" => "",
			"stueck_gewicht" => "180",],
		7 => ["id" => "14",
			"artikel_name" => "Saftplätzli",
			"anzahl_paeckchen" => "1",
			"gewicht" => "1000",
			"kommentar" => "",
			"stueck_gewicht" => "",],
		8 => ["id" => "15",
			"artikel_name" => "Roastbeef",
			"anzahl_paeckchen" => "2",
			"gewicht" => "500",
			"kommentar" => "",
			"stueck_gewicht" => "650",],
		9 => ["id" => "16",
			"artikel_name" => "Braten",
			"anzahl_paeckchen" => "5",
			"gewicht" => "200",
			"kommentar" => "",
			"stueck_gewicht" => "",],];
	
	ob_start();
	include __DIR__ . '/templates/pages/test_page.html.php';
	$testbody = ob_get_clean();
	echo $testbody;
	$mail = new Email();
	$mail->prepare('Subject', $testbody);
//    $mail->send('samuelgfeller@bluewin.ch', 'info@masesselin.ch', 'Samuel Gfeller', 'Masesselin');
	exit;
}

