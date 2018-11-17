<div class="search">
    <input type="text" id="searchInput" autocomplete="off" placeholder="Artikel suchen"
           onkeyup="filter('artikelTable',1)">
</div>
<a href="https://fleisch.masesselin.ch/artikel" target="_blank">
    <button class='btn add' id='myBtn'>Add</button>
</a>
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
        ?>
        <tr id="bestell_artikel<?= $baId ?>">
            <td><?php echo $artikel->getNummer() ?></td>  <!--CHANGE JAVASCRIPT WHERE TABLE IS CREATED DYNAMICALY-->
            <td><?php echo $artikel->getName() ?></td>
            <td><?php echo $artikel->getKgPrice() ?></td>
            <td><input type="checkbox" onclick="checkUncheckAvailable(<?= $baId ?>)"
                       id="box<?= $baId ?>"<?= $artikel->getVerfuegbar() == 1 ? 'checked' : '' ?>>
                <label for="box<?= $baId ?>">&nbsp;</label></td>
            <td class="amountCheckTd">
                <div class="ck-button">
                    <label>
                        <input type="checkbox" id="pieceBox<?= $baId ?>" <?= $artikel->getStueckbestellung() == 1 ? 'checked' : '' ?>
                               onclick="checkUncheckPiece(<?= $baId ?>)"><span></span>
                    </label>
                </div>
            <td><input class="comment weightText" type="text" value="<?php echo $artikel->getGewicht(); ?>"
                       onkeyup="updWeight(<?= $baId ?>,this.value)"> kg
            </td>
        </tr>
        <?php
    } ?>
</table>
<div class="noResults">
    <p>Keine Resultate gefunden</p>
</div>

<!-- The Modal -->

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