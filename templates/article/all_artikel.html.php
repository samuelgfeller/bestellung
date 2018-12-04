<h2 style="font-weight: normal; margin-left: 20px">Artikel für den <b><?= $datum ?></b></h2>
<div class="search">
    <input type="text" id="searchInput" autocomplete="off" placeholder="Artikel suchen"
    onkeyup="filter('artikelTable',1)">
</div>
<a href="https://fleisch.masesselin.ch/artikel" target="_blank">
    <button class='ownBtn add' id='myBtn'>Add</button>
</a>
<button class="ownBtn otherDateBtn" id="changeArticleDateBtn">Datum ändern</button>

<table class="items artikel artikelTablePadding" id="artikelTable">
    <tr>
        <th>Nummer</th>
        <th>Name</th>
        <th>Kg Preis</th>
        <th>Verfügbar</th>
        <th>Stück</th>
        <!--        <th>Gewicht</th>-->
        <th>Menge</th>
    </tr>
    <?php
    foreach ($allArtikel as $artikel) {
        $baId = $artikel->getBestellArtikelId();
        $datum = $artikel->getDatum();
        ?>
        <tr id="bestell_artikel<?= $baId ?>">
            <td><?php echo $artikel->getNummer() ?></td>  <!--CHANGE JAVASCRIPT WHERE TABLE IS CREATED DYNAMICALY-->
            <td><?php echo $artikel->getName() ?></td>
            <td><?php echo $artikel->getKgPrice() ?></td>
            <td><input type="checkbox" onclick="checkUncheckAvailable(<?= $baId ?>)"
                       id="box<?= $baId ?>"<?= $artikel->getVerfuegbar() == 1 ? 'checked' : '' ?>>
                <label for="box<?= $baId ?>">&nbsp;</label></td>
            <td class="amountCheckTd">
                <div class="check-button">
                    <label>
                        <input type="checkbox" id="pieceBox<?= $baId ?>" <?= $artikel->getStueckbestellung() == 1 ? 'checked' : '' ?>
                               onclick="checkUncheckPiece(<?= $baId ?>)"><span></span>
                    </label>
                </div>
            <td><input class="comment weightText" type="text" value="<?php echo $artikel->getGewicht(); ?>"
                       placeholder="<?= $artikel->getAvgWeight() ?>" onkeyup="updWeight(<?= $baId ?>,this.value)"> kg
            </td>
        </tr>
        <?php
    } ?>
</table>
<div class="noResults">
    <p>Keine Resultate gefunden</p>
</div>
