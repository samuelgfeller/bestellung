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
            if (output === 'true'){
                location.replace('/');
            }else{
                $('.alert').show();
            }
        }).fail(function (output){
            alert('Fehler');
            console.log(output);
        });
    });

});