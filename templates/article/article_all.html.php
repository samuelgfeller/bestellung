<?php require_once __DIR__ . '/../base.html.php'; ?>
<h2 style="font-weight: normal; margin-left: 20px">Artikel für den <b><?= $date ?></b></h2>
<div class="search">
    <input type="text" id="searchInput" autocomplete="off" placeholder="Artikel suchen"
    onkeyup="filter('articleTable',1)">
</div>
<!--<a href="https://fleisch.masesselin.ch/artikel" target="_blank">-->
    <button class='ownBtn add' id='myBtn' onclick="window.open('https://fleisch.masesselin.ch/artikel')">Add</button>
<!--</a>-->
<button class="ownBtn otherDateBtn" id="changeArticleDateBtn">Datum ändern</button>
<button class="ownBtn" id="importBtn">Importieren</button>
<table class="items article articleTablePadding" id="articleTable" data-datesql="<?= $dateSQL ?>">
    <tr>
        <th>Nummer</th>
        <th>Name</th>
        <th>Kg / l Preis</th>
        <th>Verfügbar</th>
        <th>Menge</th>
        <th>Durchschnitt</th>
        <th>Verkauft letzter Termin</th>
    </tr>
    <?php
    foreach ($allBa as $ba) {
        $baId = $ba->getOrderArticleId();
        $date = $ba->getDate();
        $weight = $ba->getWeight();
        ?>
        <tr id="order_article<?= $baId ?>">
            <td><?php echo $ba->getNr() ?></td>  <!--CHANGE JAVASCRIPT WHERE TABLE IS CREATED DYNAMICALY-->
            <td><?php echo $ba->getName() ?></td>
            <td><?php echo $ba->getKgPrice() ?></td>
            <td><input type="checkbox" onclick="checkUncheckAvailable(<?= $baId ?>,<?= $ba->getArticleId() ?>)"
                       id="box<?= $baId ?>" <?= $ba->getAvailable() == 1 ? 'checked' : '' ?>>
                <label for="box<?= $baId ?>">&nbsp;</label>
            </td>
            <td><input class="comment weightText" type="text" value="<?= $weight == 0.00 ? '' : $weight ?>"
                       placeholder="<?= $ba->getAvgWeight() ?>" onkeyup="updWeight(<?= $baId ?>,this.value)"> kg
            </td>
            <td>
                <?= $ba->getAvgWeight() ?>
            </td>
            <td>
                <?= $ba->getSoldWeightLastDate() ?>
            </td>
        </tr>
        <?php
    } ?>
</table>
<div class="noResults">
    <p>Keine Resultate gefunden</p>
</div>
