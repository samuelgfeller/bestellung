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

