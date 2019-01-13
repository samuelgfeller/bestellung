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
        <th>Menge</th>
    </tr>
    <?php
    foreach ($allBa as $ba) {
        $baId = $ba->getBestellArtikelId();
        $datum = $ba->getDatum();
        $gewicht = $ba->getGewicht();
        ?>
        <tr id="bestell_artikel<?= $baId ?>">
            <td><?php echo $ba->getNummer() ?></td>  <!--CHANGE JAVASCRIPT WHERE TABLE IS CREATED DYNAMICALY-->
            <td><?php echo $ba->getName() ?></td>
            <td><?php echo $ba->getKgPrice() ?></td>
            <td><input type="checkbox" onclick="checkUncheckAvailable(<?= $baId ?>,<?= $ba->getArtikelId() ?>)"
                       id="box<?= $baId ?>" <?= $ba->getVerfuegbar() == 1 ? 'checked' : '' ?>>
                <label for="box<?= $baId ?>">&nbsp;</label>
            </td>
            <td><input class="comment weightText" type="text" value="<?= $gewicht == 0.00 ? '' : $gewicht ?>"
                       placeholder="<?= $ba->getAvgWeight() ?>" onkeyup="updWeight(<?= $baId ?>,this.value)"> kg
            </td>
        </tr>
        <?php
    } ?>
</table>
<div class="noResults">
    <p>Keine Resultate gefunden</p>
</div>
