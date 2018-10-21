<?php
header("Location: ".Local::domain."/order");

if (isset($_POST['email'])){
    require_once __DIR__ . '/../Populate.php';
    require_once __DIR__ . '/../entity/Bestellartikel.php';

    $msg='error';
    $artikel = Populate::populateArtikel($_POST);
    if ($_POST['artikelAction']=='add'){

        Bestellartikel::add($artikel);
        Flash::setFlash('addedWeapon', 'Artikel erfolgreich hinzugefügt','success');
        exit;
    }
    if ($_POST['artikelAction']=='edit'){
        Bestellartikel::upd($artikel);
        Flash::setFlash('updWeapon', 'Artikel erfolgreich modifiziert','success');
        exit;
    }
}
