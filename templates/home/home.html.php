<?php require_once __DIR__ . '/../base.html.php'; ?>
<div class="homeContainer">
    <h1 class="title">Masesselin</h1>
    <img class="homeImg" src="images/logovache.jpg" alt="logo">
    <div class="alert" id="clientExist">
        <strong>Diese E-Mail Adresse ist noch nicht erfasst:</strong> Bitte schreiben Sie <a
                href="mailto:info@masesselin.ch">info@masesselin.ch</a> ein Mail mit Ihren Kontaktdaten
        damit wir Sie erfassen / anpassen können.
        <br>
    </div>
    <div class="centeredInfo">
            <input type="text" name="email" placeholder="Ihr E-Mail"><br><br>
            <button class="submitBtn" id="submitEmail">Zur Bestellung</button>
    </div>

</div>
