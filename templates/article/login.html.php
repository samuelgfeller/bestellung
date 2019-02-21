<?php require_once __DIR__ . '/../base.html.php'; ?>
<div class="homeContainer">
    <h1 class="title">Einloggen</h1>
    <img class="homeImg" src="images/logo_banner.jpg" alt="logo">
    <div class="alert">
        <strong>Das Passwort ist falsch</strong>
    </div>
    <div class="centeredInfo">
        <form action="artikel" method="post">
            <input type="password" name="password" placeholder="Passwort"><br><br>
            <button type="submit" class="submitBtn">Einloggen</button>
        </form>
    </div>

</div>
