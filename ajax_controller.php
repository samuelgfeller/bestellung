<?php
session_start();
$num = filter_var($path, FILTER_SANITIZE_NUMBER_INT);
require_once __DIR__ . "/Local.php";

if ($path == 'artikel/gewicht') {
    require_once 'model/entity/Bestellartikel.php';
    Bestellartikel::updWeight($_POST['id'], $_POST['value']);
    exit;
}
//test
if ($path == 'bestellArtikel/checkAvailable') {
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
if ($path == 'bestellArtikel/checkPiece') {
    require_once 'model/entity/Bestellartikel.php';

    if (Bestellartikel::checkPieceWeight($_POST['artikel_id'])) {
        Bestellartikel::checkPiece($_POST['artikel_id'], $_POST['value']);
    } else {
        echo 'false';
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
if ($path == 'testmail') {
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
    $positionDaten = [
        0 => [
            "id" => "7",
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
    include __DIR__ . '/templates/success/confirmation_mail.php';
    $testbody = ob_get_clean();
    echo $testbody;
    $mail = new Email();
    $mail->prepare('Subject', $testbody);
//    $mail->send('samuelgfeller@bluewin.ch', 'info@masesselin.ch', 'Samuel Gfeller', 'Masesselin');
    exit;
}
