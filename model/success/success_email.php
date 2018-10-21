<?php
header("Location: ".Local::domain);

if (isset($_POST['email'])){
    require_once __DIR__ . '/../Populate.php';
    require_once __DIR__ . '/../entity/Bestellartikel.php';

    var_dump($_POST['email']);
    $_SESSION['email'] = $_POST['email'];
}
