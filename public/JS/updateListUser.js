
function updateListUser(list_id, new_user_email, updateListUserWebService) {

    $.ajax({

        url : updateListUserWebService,
        data : {
            'listId' : list_id,
            'newUserEmail' : new_user_email
        },
        type : 'POST',
        dataType : 'json',
        success: function (data) {
            location.reload();
            console.log('Se ha cambiado el usuario con éxito.');
        },
        error: function (data) {
            location.reload();
            console.log('Se ha producido un error al cambiar el usuario.');

        },
    })
}

function updateListUserEvent() {

    $( ".update_user" ).click(function() {
        let list_id = $(this).attr("data-id");
        let new_user_email = $('#new_user').val();
        if (!new_user_email) {
            alert('Debe especificar un nuevo correo para reasignar la lista.');
        }
        let updateListUserWebService = '/api/v1/lists/' + list_id ;
        updateListUser(list_id, new_user_email, updateListUserWebService);
    });
}

$(document).ready(function() {
    updateListUserEvent()
});
