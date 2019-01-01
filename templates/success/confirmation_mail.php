
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellbestätigungs</title>
</head>
<body style="margin: 0; padding: 0;font-family: Helvetica, sans-serif;">
<div style="width: 90%;margin:auto;">

<h1>Bestellbestätigung</h1>
Vielen Dank für Ihre Bestellung. <br>
<!--    <img style="width: 100%;" src="../../public/images/logovache.jpg" alt="">-->
Sie haben folgende Artikel bestellt: <br>
<table style=" border-collapse: collapse;
        width: 100%;" align="center">
<?php $i = 1;
$totalWeight = 0;
foreach ($positionDaten as $position) { ?>
    <tr style=""><td style="padding: 7px;">
        <b><?= (int)$position['anzahl_paeckchen'] ?></b> Päckchen <b><?= $position['artikel_name'] ?></b>
    <?php if(!empty($position['anzahl_paeckchen'])) {
        echo $position['gewicht'] < 15
            ? ' mit <b>' . $position['gewicht'] . '</b> Stücke pro Päckchen à <b>' . $position['stueck_gewicht'] . 'g.</b> (pro Stück)'
            : ' à <b>' . $position['gewicht'] . 'g.</b>';
        }
 ?>
    <?= !empty($position['kommentar']) ? ' | '.$position['kommentar'].'<br>' : '<br>' ?>
        </td></tr>
<?php } ?>
    <tr><td>
            Sie können Ihre bestellung jederzeit anpassen: <a href="https://bestellung.masesselin.ch/">https://bestellung.masesselin.ch/</a><br><br>
    </td>
        <td>
            Falls Sie diese Bestellung / Anpassung <b>nicht</b> gemacht haben, geben Sie uns bitte bescheid indem Sie auf dieses Mail antworten.
        </td>
    </tr>
</table>
</div>
</body>
</html>