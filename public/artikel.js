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
    }).done(function (output) {
// console.log(output);
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

/**
 * allow or disable stückbestellung
 * @param artikel_id
 * @param ba_id
 */
function checkUncheckPiece(artikel_id,ba_id) {
    var checked = $('#pieceBox' + ba_id).is(":checked");
    if(checked){
        checked = 1;
    }else{
        checked = 0;
    }
    $.ajax({
        url: 'bestellArtikel/checkPiece',
        type: 'post',
        data: {
            'artikel_id': artikel_id,
            'value':checked
        }
    }).done(function (output){
        if (output === 'false') {
            $('#pieceBox' + ba_id).prop('checked','');
            if(confirm('Das Stückgewicht muss zuerst bei dem Artikel festgelegt werden. Wollen Sie auf die Seite der Artikel weitergeleitet werden?')){
                window.open('https://fleisch.masesselin.ch/artikel','_blank');
            }
        }
        }).fail(function (output){
        alert('Fehler');
        console.log(output);
    });
}

