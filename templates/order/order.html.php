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
            <th>Sonderheiten</th>
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
            <td class="amountNumber"><input id="pAmount<?= $baId ?>" class="comment" type="number" value="0" min="0"
                                            onchange="calcWeight(<?= $baId.','.$artikel->getStueckbestellung() ?>)"
                                            max="15" name="pAmount[]"></td>
            <td id="timesTd<?= $baId ?>">&times;</td>
            <td style="width:200px;"><input class="comment weightText" id="weightInput<?= $baId ?>" type="number"
                                            value="0" onchange="calcWeight(<?= $baId.','.$artikel->getStueckbestellung() ?>)"
                                            name="singleWeight[]">
                <?php echo $artikel->getStueckbestellung() == 1 ? 'Stk. (ca. '.$artikel->getStueckgewicht().'g) / g.' : 'g.'; ?>
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

<!-- The Modal @todo set price  -->

<div id="myModal" class="modal artikel-modal">
    <div class="modal-content artikel-modal-content">
        <div class="modal-header">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Artikel anpassen/hinzufügen</h2>
        </div>
        <div class="modal-body artikel-modal-body">
            Loading...
        </div>
    </div>
</div>
<?php