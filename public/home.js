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

    $('#changeArticleDateBtn').click(function () {
        location.replace('/artikel/dates');
    });
    $('#changeOrderDateBtn').click(function () {
        location.replace('/order/dates');
    });

    $('.calcInfoClass').each(function (index, value) {
        // Getting the id info from div "calcInfo"
        var id = $(value).data('baid');
        // Getting the bool info from div "calcInfo"
        var stueck = $(value).data('stk');
        calcWeight(id,stueck);
    });

    $(document).on('change', ".calcWeight", function () {
        // Getting the id from the changed attribute
        var id = $(this).data('baid');
        // Getting the bool info from div "calcInfo"
        var stueck = $('#calcInfo'+id).data('stk');
        calcWeight(id,stueck);
    });

    $('.availableWeight').each(function (index, value) {
        var id = $(value).attr('id');
        var value2 = parseFloat($('#' + id).text(), 10);
        // console.log(id, value2);
        if (value2 > 1) {
            $('#' + id).css({
                'color': '#00b300',
                'font-weight': 'bold',
            });

        } else if (value2 <= 1 && value2 > 0) {
            $('#' + id).css({
                'color': 'orange',
                'font-weight': 'bold',
            });
        }else {
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

function calcWeight(id,stueck) {

    // Available weight as text
    var aWeightTxt = $('#availableWeight' + id).text();
    // available weight parsed as float
    var aWeight = parseFloat(aWeightTxt, 10);
    // Amount packages
    var pAnzahl = $('#pAmount' + id).val();
    // Weight or amount pieces in package
    var singleWeight = parseInt($('#weightInput' + id).val());
    var wantedWeight = pAnzahl * singleWeight;
    var maxAmount = 15;
    var minWeight = 50;

    // console.log('singleWeigth: ' + singleWeight);
    // Check if value is between authorised values

    if (pAnzahl && singleWeight) {
        if (stueck === 1 && singleWeight <= maxAmount) {
            var stueckGewicht = 500;
            $.ajax({
                url: 'order/checkDefaultWeight',
                type: 'post',
                async: false,
                data: {'id': id}
            }).done(function (output) {
                if (output) {
                    stueckGewicht = parseInt(output);
                } else {
                    stueckGewicht = 500;
                }
            }).fail(function (output) {
                alert('Fehler bitte melden Sie sich bei Nicolas');
            });

            var totalWantedWeight = wantedWeight * stueckGewicht / 1000;
            if (aWeight - totalWantedWeight >= 0) {
                $('#outputWeight' + id).html(pAnzahl * singleWeight + ' Stk. von ' + stueckGewicht + 'g. = <b>' + totalWantedWeight * 1000 + 'g.</b>');
            } else {
                $('.modal-header h4').text('Es wurde zu viel eingegeben');
                $('.modal-body p').html('Bitte einen Betrag unter dem verfügbaren Gewicht eingeben <br><b>' +
                    +pAnzahl + '</b> Päckchen <b>&times; ' + singleWeight + ' Stücke von ' + stueckGewicht + 'g</b> gibt <b>' + totalWantedWeight + 'kg</b>. Verfügbar sind: <b>' + aWeight + 'kg.</b>');
                $('#myModal').modal('toggle');
                console.log(totalWantedWeight);
                cleanOrder(id);
            }
        }
        else if ((singleWeight >= minWeight) || singleWeight == 0) {
            if ((aWeight - (wantedWeight / 1000)).toFixed(3) >= 0) {
                $('#outputWeight' + id).html('<b>'+wantedWeight + 'g.</b>');
            } else {
                $('.modal-header h4').text('Es wurde zu viel eingegeben');
                $('.modal-body p').html('Bitte einen Betrag unter dem verfügbaren Gewicht eingeben <br><b>' +
                    +pAnzahl + ' &times; ' + singleWeight + 'g</b> gibt <b>' + wantedWeight / 1000 + 'kg</b>. Verfügbar sind: <b>' + aWeight + 'kg.</b>');
                $('#myModal').modal('toggle');
                cleanOrder(id);
            }
        } else {
            if (stueck === 1) {
                $('.modal-header h4').text('Ein ungültiger Wert wurde eingegeben');
                $('.modal-body p').html('Geben Sie bitte einen Wert zwischen <b>1 und ' + maxAmount + '</b> ein wenn Sie mit der Anzahl Stücke pro Päckchen bestellen möchten' +
                    'und sonst einen Wert welcher grösser ist als <b>' + minWeight + '</b>');
                $('#myModal').modal('toggle');
                cleanOrder(id);
            } else {
                $('.modal-header h4').text('Ein ungültiger Wert wurde eingegeben');
                $('.modal-body p').html('Für diesen Artikel ('+id+') kann nur mit dem Gewicht bestellt werden. Geben Sie dafür bitte einen Wert ein welcher grösser als <b>' +
                    minWeight + '</b> ist.');
                $('#myModal').modal('toggle');
                cleanOrder(id);
            }
        }
    }
    // console.log((aWeight - (wantedWeight / 1000)).toFixed(3) + ' - ' + (wantedWeight / 1000));

// });

}

function updComment(id, value) {
    $.ajax({
        url: 'order/comment',
        type: 'post',
        data: {
            'id': id,
            'value': value
        }
    });
}

function cleanOrder(id) {
    $('#weightInput' + id).val(0).focus();
    $('#weightInput' + id).focus();
    $('#outputWeight' + id).text('');

}