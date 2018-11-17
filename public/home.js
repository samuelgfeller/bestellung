$(document).ready(function () {
    $('#submitEmail').click(function () {
        var email = $("input[name=email]").val();
        $.ajax({
            url: 'order/check_email',
            type: 'post',
            data: {
                'email': email
            }
        }).done(function (output) {
            if (output === 'true') {
                location.replace('/');
            } else {
                $('.alert').show();
            }
        }).fail(function (output) {
            alert('Fehler');
            console.log(output);
        });
    });

    $('.availableWeight').each(function (index, value) {
        var id = $(value).attr('id');
        var value2 = parseFloat($('#' + id).text(), 10);
        console.log(id, value2);
        if (value2 > 0) {
            $('#' + id).css({
                'color': '#00b300',
                'font-weight': 'bold',
            });

        } else {
            var idNum = id.match(/\d+/);
            $('#pAmount' + idNum + ',#weightInput' + idNum).prop('readonly', true).css({
                'color': 'grey',
                'background': 'lightgrey'
            });
            $('#' + id).css('color', 'red');
        }
    });

    // $('#weightInput').on('change', function() {

});

function calcWeight(id, stueck) {
    var aWeightTxt = $('#availableWeight' + id).text();
    var aWeight = parseFloat(aWeightTxt, 10);
    var singleWeight = parseInt($('#weightInput' + id).val());
    var pAnzahl =  $('#pAmount' + id).val();
    var wantedWeight = pAnzahl * singleWeight;
    console.log('singleWeigth: ' + singleWeight);
    // Check if value is between authorised values

    if (stueck === 1 && singleWeight <= 15) {
        var stueckGewicht = 500;
        $.ajax({
            url: 'order/checkDefaultWeight',
            type: 'post',
            async:false,
            data: {'id':id}
        }).done(function (output){
            stueckGewicht = parseInt(output);
        }).fail(function (output) {
            alert('Fehler bitte melden Sie sich bei Nicolas');
        });

        var totalWantedWeight = wantedWeight * stueckGewicht / 1000;
        if (aWeight - totalWantedWeight >= 0){
            $('#outputWeight' + id).html(pAnzahl * singleWeight +' Stk. von '+ stueckGewicht + 'g. = <b>'+totalWantedWeight*1000+'g.</b>');
        }else {
            alert('Bitte einen Betrag der unter dem verfügbaren Gewicht ist eingeben. Stückgewicht ist: '+stueckGewicht);
            console.log(totalWantedWeight);
            cleanOrder(id);
        }
    }
    // Between 50 and 1000 (weight) or below 15 (piece)
    else if ((singleWeight >= 50 && singleWeight <= 1000) || singleWeight==0) {
        if ((aWeight - (wantedWeight / 1000)).toFixed(3) >= 0) {
            $('#outputWeight' + id).text(wantedWeight + 'g.');
        } else {
            alert('Bitte einen Betrag unter dem verfügbaren Gewicht eingeben');
            cleanOrder(id);
        }
    } else{
        if (stueck === 1){
            alert('Bitte einen Wert zwischen 1 und 15 für die Anzahl Stücke oder zwischen 50 und 1000 für das Gewicht eingeben.');
            cleanOrder(id);
        }else{
            alert('Bitte einen Wert zwischen 50 und 1000 für das Gewicht eingeben.');
            cleanOrder(id);
        }
    }
    console.log((aWeight - (wantedWeight / 1000)).toFixed(3) + ' - ' + (wantedWeight / 1000));

// });

}

function updComment(id,value) {
    $.ajax({
        url: 'order/comment',
        type: 'post',
        data: {
            'id': id,
            'value': value
        }
    });
}

function cleanOrder(id){
    $('#weightInput' + id).val(0).focus();
    $('#weightInput' + id).focus();
    $('#outputWeight' + id).text('');

}