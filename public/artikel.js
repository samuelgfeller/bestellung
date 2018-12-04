$(document).ready(function () {
    $('#changeArticleDateBtn').click(function () {
        location.replace('/artikel');
    });
});

function updWeight(id,value) {
    $.ajax({
        url: 'artikel/gewicht',
        type: 'post',
        data: {
            'id': id,
            'value': value
        }

    }).fail (function (output){
        alert('Fehler !');
    });
}

function checkUncheckAvailable(id) {
    var checked = $('#box' + id).is(":checked");
    if(checked){
        checked = 1;
    }else{
        checked = 0;
    }
    $.ajax({
        url: 'bestellArtikel/checkAvailable',
        type: 'post',
        data: {
            'id': id,
            'value':checked
        }
    }).fail(function (output){
        alert('Fehler');
        console.log(output);
    });
}

function checkUncheckPiece(id) {
    var checked = $('#pieceBox' + id).is(":checked");
    if(checked){
        checked = 1;
    }else{
        checked = 0;
    }
    $.ajax({
        url: 'bestellArtikel/checkPiece',
        type: 'post',
        data: {
            'id': id,
            'value':checked
        }
    }).done(function (output){
        if (output === 'false') {
            $('#pieceBox' + id).prop('checked','');
            if(confirm('Das Stückgewicht muss zuerst bei dem Artikel festgelegt werden. Wollen Sie auf die Seite der Artikel weitergeleitet werden?')){
                location.replace('https://fleisch.masesselin.ch/artikel');
            }
        }
        }).fail(function (output){
        alert('Fehler');
        console.log(output);
    });
}

