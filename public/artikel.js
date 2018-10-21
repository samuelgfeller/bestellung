function searchArtikel(inputVal) {
    // var inputVal = $('#input').val();
    $.ajax({
        url: "artikel/find",
        type: "POST",
        data: {
            inputVal: inputVal
        },
        success: function (data) {
            if (data) {
                data = JSON.parse(data);
                var table = document.getElementById("artikelTable");
                $("#artikelTable tr").not(':first').remove();
                //Gets all key like Ort:, PLZ, Id
                var col = [];
                for (var i = 0; i < data.length; i++) {
                    for (var key in data[i]) {
                        if (col.indexOf(key) === -1) {
                            col.push(key);
                        }
                    }
                }
                //Set values inside table

                for (var i = 0; i < data.length; i++) {

                    var tr = table.insertRow(-1);

                    var id = null;
                    for (var j = 0; j < col.length; j++) {

                        if (col[j] == 'id') {

                        }else {
                            var tabCell = tr.insertCell(-1);

                            //get data like Basel, 4000, 5
                            tabCell.innerHTML = data[i][col[j]];
                        }
                        if (col[j] == 'id') {
                            var id = data[i][col[j]];
                        }
                    }
                    tr.setAttribute("id", "artikel" + id);
                    var td = tr.insertCell(-1);
                    td.insertAdjacentHTML(
                        'beforeend',
                        '<button class="btn upd" id="myBtn" onclick="show(\'artikel/edit/' + id + '\')">Editieren</button>'
                    );
                    td.insertAdjacentHTML(
                        'beforeend',
                        '<button class="btn del" id="myBtn" onclick="delArtikel(' + id + ')">Löschen</button>'
                    );
                }
                $('#paginationNav').css("display", "none");
                $('.noResults').css("display", "none");

            } else {
                $("#artikelTable tr").not(':first').remove();
                $('#paginationNav').css("display", "none")
                $('.noResults').css("display", "inline");

            }
        }
    });
    return false;
}

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

function checkOrUncheck(id) {
    var checked = $('#box' + id).is(":checked");
    if(checked){
        checked = 1;
    }else{
        checked = 0;
    }
    $.ajax({
        url: 'bestell_artikel/check',
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