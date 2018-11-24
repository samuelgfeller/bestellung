<h2 style="font-weight: normal;">Bestellung für <b><?php echo $client->getVorname() . ' ' . $client->getName() ?></b>
</h2>
<div class="search">
    <input type="text" id="searchInput" autocomplete="off" placeholder="Artikel suchen"
           onkeyup="filter('artikelTable',0)">
</div><br>
<form action="success" method="post" id="bestellForm" onkeypress="return event.keyCode != 13;">

    <table class="items artikel" id="artikelTable">
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
        foreach ($bestellArtikel as $artikel){
        $baId = $artikel->getBestellArtikelId();

        ?>

        <tr id="bestell_artikel<?= $baId ?>">
            <!--CHANGE JAVASCRIPT WHERE TABLE IS CREATED DYNAMICALY-->
            <td><?= $artikel->getName() ?></td>
            <td><?= $artikel->getKgPrice() ?></td>
            <td id="availableWeight<?= $baId ?>" class="availableWeight"><span><?= $artikel->getVerfuegbarGewicht(); ?></span> kg</td>
            <td class="amountNumber"><input id="pAmount<?= $baId ?>" class="comment" type="number" placeholder="0" min="0"
                                            onchange="calcWeight(<?= $baId.','.$artikel->getStueckbestellung() ?>)"
                                            max="15" name="pAmount[]"></td>
            <td id="timesTd<?= $baId ?>">&times;</td>
            <td style="width:250px;"><input class="comment weightText" id="weightInput<?= $baId ?>" type="number"
                                            placeholder="0" onchange="calcWeight(<?= $baId.','.$artikel->getStueckbestellung() ?>)"
                                            name="singleWeight[]">
                <?php echo $artikel->getStueckbestellung() == 1 ? 'g. / Stk. à ca. '.$artikel->getStueckgewicht().'g.' : 'g.'; ?>
            </td>
            <td><input class="comment" type="text" value="<?= '' ?>" name="kommentar[]"></td>
            <td id="outputWeight<?= $baId ?>"></td>
            <input type="hidden" name="baId[]" value="<?= $baId ?>">
        </tr>
            <?php
            } ?>
    </table>
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
<?php