
<h2 style="font-weight: normal;">Bestellung für <b><?php echo $client->getVorname().' '. $client->getName()?></b></h2>

<table class="items artikel" id="artikelTable">
    <tr>
        <th>Name</th>
        <th>Kg Preis</th>
        <th>Verfügbar</th>
        <th>Bestellmenge</th>
    </tr>
    <?php
    foreach ($bestellArtikel as $artikel){
    ?>
    <tr id="bestell_artikel<?php echo $artikel->getBestellArtikelId() ?>">
          <!--CHANGE JAVASCRIPT WHERE TABLE IS CREATED DYNAMICALY-->
        <td><?php echo $artikel->getName() ?></td>
        <td><?php echo $artikel->getKgPrice() ?></td>
        <td></td>
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
<?php



//Filet à 81.-Fr/kg
//Rumpsteak à 64.-Fr/kg
//Entrecôte à 58.- Fr/kg
//Roastbeef à 48.-Fr/kg
//Steak à 46.-Fr/kg
//Saftplätzli, tranches minute à 45.-Fr/kg
//Emincé (Geschnetzeltes) à 42.- Fr/kg
//Braten à  39.-Fr/kg
//Cotelettes à 35.-Fr/kg
//Ragout à à 35.-Fr/kg
//Ossobucco à 34.-Fr/kg
//Hackfleisch à 24. -Fr/kg
//Siedfleisch à 26.-Fr/kg
//
//Trockenwurst à 45.-/kg Aktion 36.-/kg ab 5 Stk
//Trockenfleisch 80.-/kg
//
//1/4 Lamm (ca 3.5kg) à 33.- Fr/kg
//1/2 Lamm (ca 7kg) à 32.- Fr /kg