<?php
require_once __DIR__ . '/model/service/Flash.php';

//base.html.php is now on top of each view
require_once __DIR__ . '/Local.php';

//number inside the url
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);

if ($path == '') {
	require_once __DIR__ . '/model/entity/OrderPosition.php';
	require_once __DIR__ . '/model/entity/OrderArticle.php';
	require_once __DIR__ . '/model/entity/Order.php';
	require_once __DIR__ . '/model/entity/Client.php';
	require_once __DIR__ . '/model/entity/Appointment.php';
	require_once __DIR__ . '/model/entity/Article.php';
	require_once __DIR__ . '/model/dao/OrderPositionDAO.php';
	require_once __DIR__ . '/model/dao/OrderArticleDAO.php';
	require_once __DIR__ . '/model/dao/OrderDAO.php';
	require_once __DIR__ . '/model/dao/ClientDAO.php';
	require_once __DIR__ . '/model/dao/AppointmentDAO.php';
	require_once __DIR__ . '/model/dao/ArticleDAO.php';
	require_once __DIR__ . '/model/dao/UnitDAO.php';

	if (!empty($_SESSION['client'])) {
		if ($_GET && $_GET['datum']) {
			$client = ClientDAO::find($_SESSION['client']);
			$GETDateSQL = date('Y-m-d', strtotime($_GET['datum']));
			$GETDateText = date('d.m.Y', strtotime($_GET['datum']));

			// Get all positions of the order if the client already did one for this date
			$alreadyOrdered = OrderPositionDAO::getIfAlreadyOrdered($client->getId(), $GETDateSQL);
			
			// Initialise the variable with the order id for the html
			$order_id = $alreadyOrdered ? $alreadyOrdered[0]->getOrderId() : '';
			$order = OrderDAO::find($order_id);

			// Get all bestell_article which are available (verfügbar=1)
			$orderArticles = OrderArticleDAO::allAvailableFrom($GETDateSQL);
			
			$articleAndOrderPositions = false;
			if ($orderArticles) {
				$articleAndOrderPositions = [];
				foreach ($orderArticles as $key => $ba) {
					// Initialising array with default values
					$articleAndOrderPositions[$key] = ['already_ordered' => false,
						'order_article' => false,
						'article' => false];
					$weightToSubstrate = 0;
					
					if ($alreadyOrdered) {
						// Finding the position with the same order_article_id
						foreach ($alreadyOrdered as $position) {
//                            var_dump($position);
							if ($position->getOrderArticleId() == $ba->getOrderArticleId()) {
								// Wenn das Gewicht kleiner als 15 ist heisst es, dass es Stückzahlen sind
								if ($position->getWeight() <= 15) {
									// Die Anzahl Stücke mit dem Stückweight (Standardweight) multiplizieren (Resultate ist in Gramm)
									$alreadyOrderedWeight = $position->getWeight() * OrderArticleDAO::getDefaultWeight($ba->getOrderArticleId());
								} //Wenn höher als 15 ist es direkt ein Gewict in Gramm
								else {
									$alreadyOrderedWeight = $position->getWeight();
								}
								$weightToSubstrate += $position->getPackageAmount() * ($alreadyOrderedWeight / 1000);
								
								$articleAndOrderPositions[$key] = ['already_ordered' => $position];
							}
						}
					}
					
					// Get all the ordered weight and amount for all orders for a date
					$orderedWeightAndAmounts = OrderPositionDAO::getTotalOrderedWeightForBa($ba->getOrderArticleId(), $GETDateSQL);
					$totalOrderedWeight = 0;
					if ($orderedWeightAndAmounts) {
						// Loop over each ordered weight
						foreach ($orderedWeightAndAmounts as $orderedWeightAndAmount) {
							
							// Wenn das Gewicht kleiner als 15 ist heisst es, dass es Stückzahlen sind
							if ($orderedWeightAndAmount['weight'] <= 15) {
								// Die Anzahl Stücke mit dem Stückgewicht (Standardgewicht) multiplizieren (Resultate ist in Gramm)
								$weight = $orderedWeightAndAmount['weight'] * OrderArticleDAO::getDefaultWeight($ba->getOrderArticleId());
							} //Wenn höher als 15 ist es direkt ein Gewict in Gramm
							else {
								$weight = $orderedWeightAndAmount['weight'];
							}
							// Anzahl Pakete mit gewicht multiplizieren
							$totalOrderedWeight += $orderedWeightAndAmount['anz'] * ($weight / 1000);
						}
					}
					// @todo implement unit text and logic
					// Add the already ordered weight of this customer to the global available weight
//                    var_dump('befire: '.$totalOrderedWeight,$weightToSubstrate);
					$totalOrderedWeight -= $weightToSubstrate;
//                    var_dump('after: '.$totalOrderedWeight);
//                    var_dump('calc: '.$article->getWeight() .'-'. $totalOrderedWeight );
					// Verfügbares Gewicht
					/*                    highlight_string("<?php\n\$data =\n" . var_export($article, true) . ";\n?>");*/
//                    var_dump($article->getWeight() ?? 0);
					
					$availableWeight = round(($ba->getWeight() ?? 0) - ($totalOrderedWeight ?? 0), 2);
					if ($availableWeight === (float)-0) {
						$ba->setAvailableWeight(0);
					} else {
						$ba->setAvailableWeight($availableWeight);
					}
					
					$pieceWeight = OrderArticleDAO::getDefaultWeight($ba->getOrderArticleId());
					$ba->setPieceWeight($pieceWeight);
					
					$articleAndOrderPositions[$key]['order_article'] = $ba;
					$article = ArticleDAO::find($ba->getArticleId());
                    $articleAndOrderPositions[$key]['order_article']->setUnit(UnitDAO::find($article->getUnitId()));
                    // Available weight in gram
					$avag = $ba->getAvailableWeight() * 1000;
					$g1 = $article->getWeight1();
					$g2 = $article->getWeight2();
					$g3 = $article->getWeight3();
					$g4 = $article->getWeight4();
					$s1 = $article->getPieceAmount1();
					$s2 = $article->getPieceAmount2();
					$s3 = $article->getPieceAmount3();
					$s4 = $article->getPieceAmount4();
//                    var_dump($s3.' * '.$pieceWeight,$s3*$pieceWeight,$avag);
					$articleAndOrderPositions[$key]['order_possibilities'] = [
					    !empty($g1) && $g1 <= $avag ? ['val' => $g1, 'type' => 'weight'] : null,
						!empty($g2) && $g2 <= $avag ? ['val' => $g2, 'type' => 'weight'] : null,
						!empty($g3) && $g3 <= $avag ? ['val' => $g3, 'type' => 'weight'] : null,
						!empty($g4) && $g4 <= $avag ? ['val' => $g4, 'type' => 'weight'] : null,
						!empty($s1) && $s1 * $pieceWeight <= $avag ? ['val' => $s1, 'type' => 'piece'] : null,
						!empty($s2) && $s2 * $pieceWeight <= $avag ? ['val' => $s2, 'type' => 'piece'] : null,
						!empty($s3) && $s3 * $pieceWeight <= $avag ? ['val' => $s3, 'type' => 'piece'] : null,
						!empty($s4) && $s4 * $pieceWeight <= $avag ? ['val' => $s4, 'type' => 'piece'] : null,];
//                    var_dump(!empty($articleAndOrderPositions[$key]['order_possibilities']) ? 'not empty' : 'empty');

                    // Set the available value to 0 if it is too low but still not 0
                    $belowMin = true;
                    // foreach over the 8 possible order possibilities
                    foreach ($articleAndOrderPositions[$key]['order_possibilities'] as $orderPossibility){
                        // check if the possibility is not empty
                        if(!empty($orderPossibility)){
                            // on the first occurrence at least one possibility is available so it is set to false
                            $belowMin= false;
                        }
                    }
                    // set the available weight to 0 if it is below the minimum possibility
                    if($belowMin) {
                        $articleAndOrderPositions[$key]['order_article']->setAvailableWeight(0);
                    }
                }
//                var_dump($articleAndOrderPositions);
            }
			// Sort that the article that those with 0 are on the bottom
            if ($articleAndOrderPositions) {
				foreach ($articleAndOrderPositions as $key => $row) {
//				    var_dump((float)$row['order_article']->getAvailableWeight());
                    if (empty($row['order_article']->getAvailableWeight())){
                        // Put element at the end
                        unset($articleAndOrderPositions[$key]);
                        $articleAndOrderPositions[] = $row;
                    }
				}
			}

			require_once __DIR__ . '/templates/order/order.html.php';
			exit;
		}
		$date = AppointmentDAO::getNextDate()['text'];
		if ($date) {
			$url = '?datum=' . $date;
			header("Location: " . $url);
			exit;
		}
		require_once __DIR__ . '/templates/pages/noEntries.html.php';
		exit;
	}
	require_once __DIR__ . '/templates/pages/home.html.php';
	exit;
}

if ($path == 'artikel') {
	require_once __DIR__ . '/model/entity/OrderArticle.php';
	require_once __DIR__ . '/model/entity/Appointment.php';
	require_once __DIR__ . '/model/dao/OrderArticleDAO.php';
	require_once __DIR__ . '/model/dao/AppointmentDAO.php';
	
	
	if ($_POST) {
		if (isset($_POST['password'])) {
			// The post parameter is set and the password got typed in
			$is_admin = OrderArticleDAO::checkPassword($_POST['password']);
			if ($is_admin) {
				$_SESSION['is_admin'] = 1;
			}
		} else if (isset($_POST['newPassword']) && !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) {
            // A new Password was typed in
            $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            OrderArticleDAO::updPassword($password);
        }
	}

	if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) {
		$datesYears = AppointmentDAO::getYearsAndDates();
		$dates = $datesYears['dates'];
		$years = $datesYears['years'];
		
		OrderArticleDAO::checkAndRefresh();
		
		// If a date is in the GET request, it shows the bills for this date
		if ($_GET && $_GET['datum']) {
			$dateGET = strtotime($_GET['datum']);
			$date = date('d.m.Y', $dateGET);
			$dateSQL = date('Y-m-d', $dateGET);

            $allBa = OrderArticleDAO::allFrom($dateSQL);
//            var_dump($allBa);
			require_once __DIR__ . '/templates/article/article_all.html.php';
			exit;
		}
		//if not it only shows the dates
		$url = 'artikel'; // is needed in dates.html.php
		require_once __DIR__ . '/templates/pages/dates.html.php';
		exit;
	}
	
	if (!OrderArticleDAO::checkIfPasswordExists()) {
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
	require_once __DIR__ . '/model/entity/Order.php';
	require_once __DIR__ . '/model/entity/Client.php';
	require_once __DIR__ . '/model/entity/OrderPosition.php';
	require_once __DIR__ . '/model/entity/OrderArticle.php';
	require_once __DIR__ . '/model/entity/Article.php';
	require_once __DIR__ . '/model/service/Helper.php';
	require_once __DIR__ . '/model/service/Email.php';
	
	require_once __DIR__ . '/model/dao/OrderDAO.php';
	require_once __DIR__ . '/model/dao/ClientDAO.php';
	require_once __DIR__ . '/model/dao/OrderPositionDAO.php';
	require_once __DIR__ . '/model/dao/OrderArticleDAO.php';
	require_once __DIR__ . '/model/dao/ArticleDAO.php';
	require_once __DIR__ . '/model/dao/UnitDAO.php';
	require_once __DIR__ . '/model/dao/AppointmentDAO.php';

	
	if ($_POST && isset($_POST['pAmount'])) {
	    if (empty($appointmentId = AppointmentDAO::findDateId(htmlspecialchars($_POST['date'])))){
            require_once __DIR__ . '/templates/pages/noEntries.html.php';
            exit;
        }
		$client = ClientDAO::find($_SESSION['client']);
        $order = new Order();
        $order->setClientId($client->getId());
        $order->setCreatedAt(date('Y-m-d H:i:s'));
        $order->setAppointmentId($appointmentId);
        $order->setRemark(htmlspecialchars($_POST['remark']));
		$orderId = OrderDAO::create($order);
		$valuesArr = [];
		for ($i = 0, $iMax = count($_POST['pAmount']); $i < $iMax; $i++) {
            $package_amount = (int)$_POST['pAmount'][$i];
            // Set 0 if no weight
            if (empty($_POST['singleWeight'][$i])){ $package_amount = 0; }
            if (!empty($package_amount) || !empty($_POST['comment'][$i])) {
				$valuesArr[] = ['order_article_id' => (int)$_POST['ba_id'][$i],
					'order_id' => $orderId,
					'package_amount' => $package_amount,
					'weight' => (int)$_POST['singleWeight'][$i],
					'comment' => htmlspecialchars($_POST['comment'][$i]),];
			}
		}
		
		foreach ($valuesArr as $values) {
			$orderPosition = PopulateObject::populateOrderPosition($values);
			OrderPositionDAO::add($orderPosition);
		}
		
		// Delete old order
		if ($minId = OrderDAO::checkMultipleOrdersAndGetOlder($_SESSION['client'], $_POST['date'])) {
		    // If $minId is set that means that there were 2 orders for the same date (var is smaller id)
            // There seems to be a useless double check but it's working and I don't want to risk breaking something rn
			$minId ? OrderDAO::del($minId) : OrderDAO::del($_POST['order_id']);
		}

		// Send confirmation email
		$positionData = [];
		foreach ($valuesArr as $values) {
			$article = ArticleDAO::findArticleByOrderArticle($values['order_article_id']);
            if ($article) {
                $positionData[] = ['article_name' => $article->getName(),
					'package_amount' => $values['package_amount'],
					'weight' => $values['weight'],
					'comment' => $values['comment'],
					'piece_weight' => $article->getPieceWeight(),
					'unit' => UnitDAO::find($article->getUnitId()),];
			}
		}
		$mail = new Email();
		ob_start();
		include __DIR__ . '/templates/success/confirmation_mail.php';
		$mailBody = ob_get_clean();
//		If there was an older order, title has to show that
        $title = $minId === false ? 'Bestellbestätigung' : 'Bestelländerung';
		$mail->prepareMessage($title . ' für den ' . date('d.m.Y', strtotime($_POST['date'])), $mailBody);
        try {
		$mail->sendEmail($client->getFirstName() . ' ' . $client->getName(),$client->getEmail(), 'Masesselin','info@masesselin.ch');
        } catch (Throwable $exception){
            echo '<h3>Die Bestellung wurde aufgenommen. Im moment kann das Bestätigungsmail jedoch nicht versendet werden.
    Der Fehler wird so bald wie möglich behoben.</h3>';
        }

        //        $mail->sendEmail('Samuel Gfeller','samuelgfeller@bluewin.ch','Masesselin','info@masesselin.ch');

        require_once __DIR__ . '/templates/success/order_success.php';
//		require_once __DIR__ . '/templates/pages/feedback.html.php';
		exit;
	}
	require_once __DIR__ . '/templates/success/order_success.php';
	exit;
}

if ($path == 'artikel/dates') {
	require_once __DIR__ . '/model/entity/Appointment.php';
	require_once __DIR__ . '/model/dao/AppointmentDAO.php';
	$datesYears = AppointmentDAO::getYearsAndDates();
	$dates = $datesYears['dates'];
	$years = $datesYears['years'];
	$url = 'artikel';
	require_once __DIR__ . '/templates/pages/dates.html.php';
	exit;
}

if ($path == 'order/dates') {
	require_once __DIR__ . '/model/entity/Appointment.php';
	require_once __DIR__ . '/model/dao/AppointmentDAO.php';
	
	$datesYears = AppointmentDAO::getYearsAndDates();
	foreach ($datesYears['dates'] as $key => $date) {
        // Remove dates in past
        // orders are possible until 12am. strtotime($date) is the time for midnight and adding 43200 makes it 12am of that date
        if (strtotime($date) + 43200 < time()) {
			unset($datesYears['dates'][$key]);
		}
//        var_dump(isset($datesYears['dates'][$key]) ? $datesYears['dates'][$key] : null);
		// Remove dates without entries
        if (!AppointmentDAO::checkIfDateHasEntries(date('Y-m-d', strtotime($date)))){
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
	require_once __DIR__ . '/model/entity/Feedback.php';
	require_once __DIR__ . '/model/entity/Client.php';
	require_once __DIR__ . '/model/dao/FeedbackDAO.php';
	require_once __DIR__ . '/model/dao/ClientDAO.php';
	require_once __DIR__ . '/model/service/Email.php';
	
	if ($_POST && !empty($_POST['feedback'])) {
		$client = ClientDAO::find($_SESSION['client']);
		FeedbackDAO::add($_POST['feedback'], $client->getId());
		$mail = new Email();
		$mailBody = nl2br($_POST['feedback']); // replacing \n with <br>
		$fullName = $client->getFirstName() . ' ' . $client->getName();
        $mail->prepareMessage('Feedback von ' . $fullName, $mailBody);
        $mail->sendEmail('Masesselin','info@masesselin.ch',$fullName,$client->getEmail());
	}
	require_once __DIR__ . '/templates/success/order_success.php';
	exit;
}

//Sandbox mail
if ($path == 'mail') {
	require_once __DIR__ . '/model/service/Email.php';
	require_once __DIR__ . '/model/service/Helper.php';

//    echo Helper::prepareHtmlMailBody('asdf');


//	if($_POST && $_POST['mail'] && $_POST['subject']){
//		echo $_POST['mail'];
//		$mail = new Email();
//		$mail->prepare($_POST['subject'],$_POST['mail']);
//		$mail->send('samuelgfeller@bluewin.ch','info@masesselin.ch','Samuel Gfeller','Masesselin');
//		exit;
//	}
	
	ob_start();
	include __DIR__ . '/templates/pages/test_page.html.php';
	$testbody = ob_get_clean();
	echo $testbody;
	$mail = new Email();
	$mail->prepare('Subject', $testbody);
//    $mail->send('samuelgfeller@bluewin.ch', 'info@masesselin.ch', 'Samuel Gfeller', 'Masesselin');
	exit;
}

