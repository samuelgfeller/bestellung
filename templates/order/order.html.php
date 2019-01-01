<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<h2 style="font-weight: normal;"><b><?php echo $client->getVorname() . ' ' . $client->getName() ?></b><br>Bestellung für
    den <b> <?= $GETDateText ?> </b>
</h2>
<button class="ownBtn otherDateBtn" id="changeOrderDateBtn">Bestelldatum ändern</button>

<div class="search">
    <input type="text" id="searchInput" autocomplete="off" placeholder="Artikel suchen"
           onkeyup="filter('artikelTable',0)">
</div><br>
<form action="success" method="post" id="bestellForm" onkeypress="return event.keyCode != 13;">

    <!--    Set date outside the foreach loop -->
    <input type="hidden" name="datum" value="<?= $GETDateSQL ?>">
    <!--    set the order id -->
    <input type="hidden" name="bestellung_id" value="<?= $bestellung_id ?>">

    <table class="items artikel" id="bestellArtikelTable">
        <tr>
            <th>Name</th>
            <th>Kg Preis</th>
            <th>Verfügbar</th>
            <th align="right">Anzahl Päckchen</th>
            <th></th>
            <th>Gewicht pro Päckchen oder Anzahl Stücke</th>
            <th>Besonderheiten</th>
            <th>Gesamtgewicht (g)</th>

        </tr>
        <?php
        if ($artikelUndBestellPositionen && $artikelUndBestellPositionen) {
            foreach ($artikelUndBestellPositionen as $artikelUndBestellPosition) {
                // Initialise variables
                $artikel = $artikelUndBestellPosition['bestell_artikel'];
                $position = $artikelUndBestellPosition['already_ordered'];
                // Set bestell_artikel_id
                $baId = $artikel->getBestellArtikelId();
                // Set boolean if stueckbestellung is allowed
                $stk = $artikel->getStueckbestellung();

                $anzStk = null;
                $gewicht = null;
                $kommentar = null;
                if ($position) {
                    $anzStk = $position->getAnzahlPaeckchen();
                    $gewicht = $position->getGewicht();
                    $kommentar = $position->getKommentar();
                }

                ?>

                <tr id="bestell_artikel<?= $baId ?>">
                    <!--CHANGE JAVASCRIPT WHERE TABLE IS CREATED DYNAMICALY-->
                    <td><?= $artikel->getName() ?></td>
                    <td><?= $artikel->getKgPrice() ?></td>
                    <td id="availableWeight<?= $baId ?>" class="availableWeight">
                        <span><?= $artikel->getVerfuegbarGewicht(); ?></span> kg
                    </td>
                    <td class="amountNumber"><input id="pAmount<?= $baId ?>" class="comment calcWeight" type="number"
                                                    placeholder="0" min="0" value="<?= $anzStk ?>"
                                                    data-baid="<?= $baId ?>"
                                                    max="15" name="pAmount[]"></td>
                    <td id="timesTd<?= $baId ?>">&times;</td>
                    <td style="width:250px;"><input class="comment weightText calcWeight" id="weightInput<?= $baId ?>"
                                                    type="number" placeholder="0" value="<?= $gewicht ?>"
                                                    data-baid="<?= $baId ?>"
                                                    name="singleWeight[]">
                        <?= $stk == 1 ? 'g. / Stk. à ca. ' . $artikel->getStueckgewicht() . 'g.' : 'g.'; ?>
                        <!--                    Inserting the hidden info here because outide it affects the nth:child(even)-->
                        <input type="hidden" name="ba_id[]" value="<?= $baId ?>">

                    </td>
                    <td><input class="comment" type="text" maxlength="200" value="<?= $kommentar ?>" name="kommentar[]"></td>
                    <td id="outputWeight<?= $baId ?>">
                    </td>
                </tr>

                <div id="calcInfo<?= $baId ?>" class="calcInfoClass" data-baid="<?= $baId ?>"
                     data-stk="<?= $stk ?>"></div>
                <?php
            }
        } else {
            echo '<h3><b style="color:red">Es sind keine Daten vorhanden für dieses Datum.</b></h3>';
        } ?>
    </table>
    <a class="btn" href="help" style="margin-top:10px"><i class="glyphicon glyphicon-question-sign"></i> Anleitung</a>

    <input type="submit" value="Bestellen">
</form>


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
            </div>
        </div>

    </div>
</div>
