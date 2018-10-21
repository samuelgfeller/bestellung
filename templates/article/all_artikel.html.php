<div class="search">
    <input type="text" autocomplete="off" placeholder="Artikel suchen" onkeyup="searchArtikel(this.value)">
</div>
<button class='btn add' id='myBtn' onclick='window.location.replace("fleisch.masesselin.ch/artikel")'>Add</button>
<table class="items artikel" id="artikelTable">
    <tr>
        <th>Nummer</th>
        <th>Name</th>
        <th>Kg Preis</th>
        <th>Verfügbar</th>
        <th>Menge</th>
    </tr>
    <?php
    foreach ($allArtikel as $artikel){
    ?>
    <tr id="bestell_artikel<?php echo $artikel->getBestellArtikelId() ?>">
        <td><?php echo $artikel->getNummer() ?></td>  <!--CHANGE JAVASCRIPT WHERE TABLE IS CREATED DYNAMICALY-->
        <td><?php echo $artikel->getName() ?></td>
        <td><?php echo $artikel->getKgPrice() ?></td>
        <td><input type="checkbox" onclick="checkOrUncheck(<?php echo $artikel->getBestellArtikelId() ?>)" id="box<?= $artikel->getBestellArtikelId() ?>"<?= $artikel->getVerfuegbar() ==1 ? 'checked' : '' ?>>
            <label for="box<?= $artikel->getBestellArtikelId() ?>">&nbsp;</label></td>

        <td><input class="comment weightText" type="text" value="<?php echo $artikel->getGewicht(); ?>" onkeyup="updWeight(<?php echo $artikel->getBestellArtikelId() ?>,this.value)"> kg</td>
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