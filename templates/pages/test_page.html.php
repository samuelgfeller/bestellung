<!--<div class="content">-->
<!---->
<!--<form action="mail" method="post">-->
<!--    <label>-->
<!--        Subject-->
<!--        <input type="text" name="subject">-->
<!--    </label>-->
<!--    <label for="testMailArea">Body</label><textarea name="mail" id="testMailArea" cols="30" rows="10"></textarea>-->
<!--    <input type="submit" value="absenden">-->
<!--</form>-->
<!--</div>-->



<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>A Simple Responsive HTML Email</title>
    <style type="text/css">
        body {margin: 0; padding: 0; min-width: 100%!important;}
        .content {width: 100%; max-width: 600px;}
    </style>
</head>
<body yahoo bgcolor="#f6f8f1">
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table class="content" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                        Hello!
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellbestätigungs</title>
    <!--    <style type="text/css">
            body {
                margin: 0;
                padding: 0;
                min-width: 100% !important;
            }
            .content {
                width: 100%;
                max-width: 600px;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                text-align: left;
                padding: 16px;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        </style>-->
</head>
<body style="margin: 0; padding: 0;font-family: Helvetica, sans-serif;">
<div style="width: 90%;margin:auto;">

<h1>Bestellbestätigung</h1>
Vielen Dank für Ihre Bestellung. <br>
<!--    <img style="width: 100%;" src="../../public/images/logovache.jpg" alt="">-->
Sie haben folgende Artikel bestellt: <br>
<br>
<table style=" border-collapse: collapse;
        width: 100%;" align="center">
<?php $i = 1;
$totalPrice = 0;
$totalWeight = 0;
foreach ($positionDaten as $position) { ?>
    <tr style=""><td style="padding: 10px;">
        <b><?= $position['anzahl_paeckchen'] ?></b> Päckchen <b><?= $position['artikel_name'] ?></b>
    <?= $position['gewicht'] < 15 ? ' mit <b>'.$position['gewicht'] . '</b> Stücke pro Päckchen à <b>' . $position['stueck_gewicht'] . 'g.</b> (pro Stück)' : ' à <b>'.$position['gewicht'] . 'g.</b>' ?>
    <?= !empty($position['kommentar']) ? ' | '.$position['kommentar'].'<br>' : '<br>' ?>
        </td></tr>
<?php } ?>
</table>
</div>


<!--
<table style=" border-collapse: collapse;
        width: 100%;">
    <tr> style="text-align: left; padding: 8px;">
        <th>Artikel</th>
        <th>Anzahl Päckchen</th>
        <th>Gewicht pro Päckchen / Anzahl Stücke</th>
        <th>Sonderheiten</th>
    </tr>
    <?php /*$i = 1;
    $totalPrice = 0;
    $totalWeight = 0;
    foreach ($positionDaten as $position) {
        */?>
        <tr id="positionTr<?/*= $i */?>">
            <td style="text-align: left;padding: 16px;x"
                id="position<?/*= $i */?>Artikel"><?/*= $position['artikel_name'] */?></td>
            <td style="text-align: left;padding: 16px;" id="position<<?/*= $i */?>Amount">
                <b><?/*= $position['anzahl_paeckchen'] */?></b></td>
            <td style="text-align: left; padding: 16px;" id="position<?/*= $i */?>Gewicht">
                <b><?/*= $position['gewicht'] < 15 ? $position['gewicht'] . ' Stk. (' . $position['stueck_gewicht'] . 'g.)' : $position['gewicht'] . 'g.' */?></b>
            </td>
            <td style="text-align: left;padding: 16px;"
                id="position<?/*= $i */?>Kommentar"><?/*= $position['kommentar'] */?></td>
        </tr>
    <?php /*} */?>
</table>-->
</body>
</html>