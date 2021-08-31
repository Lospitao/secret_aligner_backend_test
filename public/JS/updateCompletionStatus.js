
function updateCompletionStatus(todoId, updateCompletionStatusWebService) {
    $.ajax({

        url : updateCompletionStatusWebService,
        data : {
            'todoId' : todoId,
        },
        type : 'PATCH',
        dataType : 'json',
        success: function (data) {

            console.log('Submission was successful.');
        },
        error: function (data) {
            console.log('An error occurred.');
            console.log(data);
        },
    })
}

function updateCompletionStatusEvent() {

    $( "input:checkbox" ).change(function() {
        let todoId = $(this).attr("data-id");

        let updateCompletionStatusWebService = '/api/v1/todos/' + todoId ;
        updateCompletionStatus(todoId, updateCompletionStatusWebService);

    });
}

$(document).ready(function() {
    updateCompletionStatusEvent()
});
