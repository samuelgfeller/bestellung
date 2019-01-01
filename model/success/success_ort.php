<?php
header("Location: ".Local::domain."/orte");

if (isset($_POST['newOrt'])){
    require_once __DIR__ . '/../Populate.php';
    require_once __DIR__ . '/../entity/Ort.php';

    $msg='error';
    $ort = Populate::populateOrt($_POST);
/*    if ($_POST['newOrt']=='Ort hinzufügen'){
        $dbOrt=Ort::findByPLZ($ort->getPLZ());
        if ($dbOrt){
            Flash::setFlash('alreadyExists', 'Ort existiert schon','error');
            exit();
        }
        Ort::add($ort);
        Flash::setFlash('addedOrt', 'Ort erfolgreich hinzugefügt','success');
        exit;
    }*/
    if ($_POST['newOrt']=='Änderungen speichern'){
        $exists=false;
        $dbOrt=Ort::findByPlzAndOrt($ort->getPLZ(),$ort->getOrt());
        if (!$dbOrt || $dbOrt->getId()==$ort->getId()){
            $ort=Ort::upd($ort);
            Flash::setFlash('editedOrt', 'Ort erfolgreich modifiziert.','success');
            exit;
        }else{
            Flash::setFlash('alreadyExists', 'Ort existiert schon','error');
            exit;
        }
    }
}
