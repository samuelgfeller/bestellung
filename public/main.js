$(document).ready(function () {
    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");
    show = function (url) {
        $.ajax({
            url: url,
            type: 'post',
            success: function (output) {
                $('.modal-body').html(output);
            }
        });

        modal.style.display = "block";
    };

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            $('.modal-body').html("<br>Loading...<br><br>");
        }
    };
    closeModal = function () {
        document.getElementById('myModal').style.display = "none";
        $('.modal-body').html("<br>Loading...<br><br>");

    };


});

/**
 *
 * @param table Table where it should search
 * @param row starting by 0! So 1 is the second row
 */
function filter(table,row) {
    var input, filter, tr, clientTd, i;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById(table);
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        clientTd = tr[i].getElementsByTagName("td")[row];
        if (clientTd) {
            if (clientTd.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
    $("tr").css({"background-color": "#FFF"});
    $("tr:visible:odd").css({"background-color": "#f2f2f2"});
}