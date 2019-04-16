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

    $('#orderForm').on('submit', function (e) {
        if ($('.warningMsg').length > 0) {
            e.preventDefault();
            $('.modal-header h4').text('Es gibt noch Fehler');
            $('.modal-body p').html('<b>Bitte überprüfen Sie ob es keine rote Meldungen gibt und beachten Sie diese.</b> <br><br> ' +
                '<i>Wenn Sie x Anzahl Päckchen von einem Stück möchten, geben Sie bitte <b>1</b> bei der Anzahl Stücke ein. <br>' +
                'Es kann durchaus vorkommen, dass eine Stückzahl nicht so logisch ist z.B. bei einer Harasse Äpfel muss man 1 Päckchen und ' +
                '1 Stück pro Päckchen auswählen. Es wird eine Multiplikation durchgeführt und wenn Sie daher 1 Stück von etwas möchten muss bei beiden Felder ' +
                '1 stehen.</i>');
            $('#myModal').modal('toggle');
        } else {
            // $('#orderForm').submit();
        }
    });

    $('#changeArticleDateBtn').click(function () {
        location.replace('/artikel/dates');
    });
    $('#changeOrderDateBtn').click(function () {
        location.replace('/order/dates');
    });

    $('.calcInfoClass').each(function (index, div) {
        findCheckedAndCalc(div);
    });

    $(document).on('change', ".weightCheckbox", function () {
        let baid = $(this).data('baid');
        let pAmount = $('#pAmount' + baid);
        // Getting the id from the changed attribute
        let value = $(this).val();
        // Input which stores value
        let storageInput = $('#singleWeight' + baid);
        if ($(this).is(':checked')) {
            uncheckAll(baid);
            // Check only the wanted one
            $(this).prop('checked', true);
            // Set the pAmount to 1 if it is empty. This is im  portant because if it is 0 the checkbox gets unchecked in calcWeight
            if (pAmount.val() === 0 || pAmount.val() === '') {
                pAmount.val(1)
            }
            storageInput.val(value);
            // Getting the bool info from div "calcInfo"
            calcWeight(baid, value);
        } else {
            storageInput.val('');
            pAmount.val('');
            cleanOrder(baid);
        }
    });

    $(document).on('change', ".pAmount", function () {
        let id = $(this).data('baid');
        findCheckedAndCalc($('#calcInfo' + id));
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

function findCheckedAndCalc(parent) {
    let value = false;
    $(parent).find('input[type=checkbox]').each(function (key, input) {
        if ($(input).is(":checked")) {
            value = $(input).val();
        }
    });
    // Getting the id info from div "calcInfo"
    let baId = $(parent).data('baid');
    let storageInput = $('#singleWeight' + baId);

    if (value) {
        console.log(value);
        storageInput.val(value);
        calcWeight(baId, value);
    } else {
        storageInput.val('');
    }
}

function uncheckAll(id) {
    // Loop over all input type checkbox
    $('#calcInfo' + id).find('input[type=checkbox]').each(function (key, input) {
        // Uncheck all buttons
        $(input).prop('checked', false);
    });
}

function calcWeight(id, singleWeight) {

    // Available weight as text
    var aWeightTxt = $('#availableWeight' + id).text();
    // available weight parsed as float
    var aWeight = parseFloat(aWeightTxt, 10);
    // Amount packages
    var pAnzahl = $('#pAmount' + id).val();
    // Weight or amount pieces in package
    // var singleWeight = parseInt($('#weightInput' + id).val());
    var wantedWeight = pAnzahl * singleWeight;
    var maxAmount = 15;
    let unit = {short_name: 'g', equal_1000_gram: 1000};
    $.ajax({ url:"unit/find",type: 'post', async: false, data: { id: $('#order_article'+id).data('unitid') }})
        .done(function( data ) {
            unit = JSON.parse(data);
        });
    var minWeight = 50;

    // console.log('singleWeigth: ' + singleWeight);
    // Check if value is between authorised values

    if (pAnzahl && (pAnzahl !== 0 || pAnzahl === '') && singleWeight) {
        // Es wird mit Stücke bestellt
        if (singleWeight <= maxAmount) {
            var pieceWeight = 500;
            $.ajax({
                url: 'order/checkDefaultWeight',
                type: 'post',
                async: false,
                data: {'id': id}
            }).done(function (output) {
                if (output) {
                    pieceWeight = parseInt(output);
                } else {
                    pieceWeight = 500;
                }
            }).fail(function (output) {
                alert('Fehler bitte melden Sie sich bei Nicolas');
            });
            // var totalWantedWeight = wantedWeight * pieceWeight / 1000;
            var totalWantedWeight = wantedWeight * pieceWeight / unit.equal_1000_gram;
            alert(totalWantedWeight);
            if (aWeight - totalWantedWeight >= 0) {
                $('#outputWeight' + id).html(pAnzahl * singleWeight + ' Stk. à ' + pieceWeight + unit.short_name+'. = <b>' + totalWantedWeight * unit.equal_1000_gram + unit.short_name+'.</b>');
            } else {
                $('.modal-header h4').text('Es wurde zu viel eingegeben');
                $('.modal-body p').html('Bitte einen kleineren Betrag eingeben / auswählen.<br><b>' +
                    +pAnzahl + '</b> Päckchen <b>&times; ' + singleWeight + ' Stücke à ' + pieceWeight + unit.short_name+'</b> gibt <b>' + totalWantedWeight + 'kg / l</b>. ' +
                    'Verfügbar sind: <b>' + aWeight + 'kg / l.</b><br><br>' +
                    '<i>Es wurde standardmässig 1 in der Anzahl Päckchen eingesetzt</i>');
                $('#myModal').modal('toggle');
                console.log(totalWantedWeight);
                cleanOrder(id);
                $('#pAmount' + id).val(1).focus();
                calcWeight(id, singleWeight);
            }

        } else if (singleWeight || singleWeight == 0) {
            if ((aWeight - (wantedWeight / unit.equal_1000_gram)).toFixed(3) >= 0) {
                $('#outputWeight' + id).html(pAnzahl + ' &times ' + singleWeight + ' = <b>' + wantedWeight + unit.short_name+'.</b>');

            } else {
                $('.modal-header h4').text('Es wurde zu viel eingegeben');
                $('.modal-body p').html('Bitte einen kleineren Betrag eingeben / auswählen.<br><b>' +
                    +pAnzahl + ' &times; ' + singleWeight + unit.short_name+'</b> gibt <b>' + wantedWeight / unit.equal_1000_gram + 'kg / l</b>. Verfügbar sind: <b>' + aWeight + 'kg / l.</b>'
                    + '<br><br><i>Es wurde standardmässig 1 in der Anzahl Päckchen eingesetzt</i>');
                $('#myModal').modal('toggle');
                cleanOrder(id);
                $('#pAmount' + id).val(1).focus();
                calcWeight(id, singleWeight);
            }

        } else {
            /*if (stueck === 1){
                $('.modal-header h4').text('Ein ungültiger Wert wurde eingegeben');
                $('.modal-body p').html('Geben Sie bitte einen Wert zwischen <b>1 und ' + maxAmount + '</b> ein wenn Sie mit der Anzahl Stücke pro Päckchen bestellen möchten' +
                    ' und sonst einen Wert welcher grösser ist als <b>' + minWeight + '</b>');
                $('#myModal').modal('toggle');
                cleanOrder(id);
                    $('#pAmount' + id).val(1).focus();
                calcWeight(id,singleWeight);

            } else {
                $('.modal-header h4').text('Ein ungültiger Wert wurde eingegeben');
                $('.modal-body p').html('Für diesen Artikel ('+id+') kann nur mit dem Gewicht bestellt werden. Geben Sie dafür bitte einen Wert ein welcher grösser als <b>' +
                    minWeight + '</b> ist.');
                $('#myModal').modal('toggle');
                cleanOrder(id);
                    $('#pAmount' + id).val(1).focus();
                calcWeight(id,singleWeight);

            }*/
        }
    } else {
        uncheckAll(id);
        cleanOrder(id)
    }
    console.log('pAnzahl ' + pAnzahl, 'singleWeight ' + singleWeight, 'stueck ');

    // checkFilledInputs(stueck,id);

    // console.log((aWeight - (wantedWeight / 1000)).toFixed(3) + ' - ' + (wantedWeight / 1000));

// });

}

function checkFilledInputs(stueck, id) {
    var pAnzahl = $('#pAmount' + id).val();

    var singleWeight = parseInt($('#weightInput' + id).val());


    if (pAnzahl && !singleWeight && stueck) {
        $('#outputWeight' + id).html('<b class="warningMsg" style="color: red;">Bitte Stückanzahl eingeben</b><br> + Tabulatortaste drücken');
    } else if (pAnzahl && !singleWeight && !stueck) {
        $('#outputWeight' + id).html('<b class="warningMsg" style="color: red;">Bitte Gewicht eingeben</b><br> + Tabulatortaste drücken');
    } else if (!pAnzahl && singleWeight) {
        $('#outputWeight' + id).html('<b class="warningMsg" style="color: red;">Bitte Anzahl Päckchen angeben</b><br> + Tabulatortaste drücken');
    } else if (!pAnzahl && !singleWeight) {
        $('#outputWeight' + id).html('');
    }
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
    // $('#weightInput' + id).focus();
    $('#outputWeight' + id).text('');

}