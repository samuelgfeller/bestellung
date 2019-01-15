<?php require_once __DIR__ . '/../base.html.php'; ?>
<div class="homeContainer">
    <h1 class="title">Feedback</h1>
    <div class="feedbackContainer">
        <form action="feedback/success" method="post" id="feedbackForm">
            <label for="feedbackTextarea">Konnten Sie die Applikation gut verstehen? Was hätte man besser machen können?
                Fehlt irgend etwas? Was sollte anders gemacht werden? Müsste man die Anleitung anpassen? <b>Was sind Ihre Gedanken?</b></label>
            <textarea name="feedback" id="feedbackTextarea" cols="60" rows="13" autofocus></textarea>
            <input type="submit" value="Absenden">
            <button class="ownBtn" onclick="window.location='success'" type="button">Ignorieren</button>
        </form>
    </div>
</div>