$(document).ready(function () {
    $('#changeArticleDateBtn').click(function () {
        location.replace('/artikel');
    });
});

function updWeight(id, value) {
    $.ajax({
        url: 'artikel/gewicht',
        type: 'post',
        data: {
            'id': id,
            'value': value
        }
    }).done(function (output) {
// console.log(output);
    }).fail(function (output) {
        alert('Fehler !');
    });
}

function checkUncheckAvailable(ba_id, article_id) {
    var checked = $('#box' + ba_id).is(":checked");
    if (checked) {
        checked = 1;
    } else {
        checked = 0;
    }
    $.ajax({
        url: 'orderArticle/checkAvailable',
        type: 'post',
        data: {
            'id': ba_id,
            'article_id': article_id,
            'value': checked
        }
    }).done(function (output) {
        if (output === 'false') {
            $('#box' + ba_id).prop('checked', false);
            if (confirm('Eine Bestellart muss zuerst bei dem Artikel festgelegt werden. Wollen Sie auf die Seite der Artikel weitergeleitet werden?')) {
                window.open('https://fleisch.masesselin.ch/artikel', '_blank');
            }
        }
    }).fail(function (output) {
        alert('Fehler');
        console.log(output);
    });
}

