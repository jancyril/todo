$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function notify(response) {
    new PNotify({
        styling: 'fontawesome',
        type: response.type,
        title: response.title,
        text: response.message,
        nonblock: {
            nonblock: true,
            nonblock_opacity: 0.2
        }
    });
}

function showErrors(errors) {
    new PNotify({
        styling: 'fontawesome',
        type: 'error',
        title: 'Validation Error',
        text: errors.join("<br/>"),
        nonblock: {
            nonblock: true,
            nonblock_opacity: 0.2
        }
    });
}

function validationErrors(error) {
    var messages = [];

    var errors = Object.keys(error).map(function (key) {
        return error[key];
    });

    for (var x = 0; x < errors.length; x++) {
        if (Array.isArray(errors[x])) {
            for (var y = 0; y < errors[x].length; y++) {
                messages.push(errors[x][y]);
            }

            continue;
        }
        console.log(errors[x]);
        messages.push(errors[x]);
    }

    showErrors(messages);
}

$('#save-new-task').on('click', function (e) {
    $.ajax({
        type: 'POST',
        url: '/tasks',
        data: $('#new-task').serialize(),
        dataType: 'json'
    }).fail(function (data) {
        var errors = data.responseJSON;
        validationErrors(errors.errors);
    }).done(function (response) {
        notify(response);
    });

    e.preventDefault();
});

$('#save-changes').on('click', function (e) {
    $.ajax({
        type: 'PUT',
        url: '/tasks/' + $('#task-id').val(),
        data: $('#update-task').serialize(),
        dataType: 'json'
    }).fail(function (data) {
        var errors = data.responseJSON;
        validationErrors(errors.errors);
    }).done(function (response) {
        notify(response);

        if (response.success) {
            $('#update-task-modal').modal('hide');
            table.ajax.reload();
        }
    });

    e.preventDefault();
});


var table = $('#all-tasks').DataTable({
    "columnDefs": [
        { className: "text-center", "targets": [2] }
    ],
    columns: [
        { "name": "title", "orderable": true, "searchable": true },
        { "name": "status", "orderable": true, "searchable": true },
        { "name": "id", "orderable": false, "searchable": false }
    ],
    serverSide: true,
    ajax: {
        url: '/tasks/get',
        type: 'GET',
        dataFilter: function (data) {
            var json = $.parseJSON(data),
                columns = [];

            for (var x = 0; x < json.data.length; x++) {
                var buttons = '';

                buttons += '<button data-id="' + json.data[x].id + '" class="btn btn-xs btn-primary edit" title="Edit" data-toggle="modal" data-target="#update-task-modal"><i class="fa fa-pencil fa-fw"></i></button>';

                buttons += ' ';

                buttons += '<button data-id="' + json.data[x].id + '" class="btn btn-xs btn-danger delete" title="Delete"><i class="fa fa-trash fa-fw"></i></button>';

                columns.push([
                    json.data[x].title,
                    json.data[x].status,
                    buttons
                ]);
            }

            json.data = columns;

            return JSON.stringify(json);
        }
    }
});

$('#all-tasks').on('click', '.edit', function () {
    var id = $(this).data('id');
    $('#title').val('');
    $('#description').val('');
    $('#task-id').val(id);

    $.get({
        url: '/tasks/' + id + '/edit',
        dataType: 'json'
    }).fail(function (data) {
        notify(data.responseJSON);
    }).done(function (response) {
        $('#title').val(response.taskTitle);
        $('#description').val(response.taskDescription);
    });
});

$('#all-tasks').on('click', '.delete', function () {
    var id = $(this).data('id');

    swal({
        title: 'Delete this task?',
        text: 'You cannot undo this operation.',
        icon: 'warning',
        buttons: {
            cancel: "Cancel",
            confirm: {
                text: "Proceed",
                closeModal: false
            }
        },
        dangerMode: true,
        closeOnClickOutside: false,
    })
    .then((willDelete) => {
        if(willDelete) {
            $.ajax({
                type: 'DELETE',
                url: '/tasks/' + id,
                dataType: 'json'
            }).done(function (response) {
                swal.close();
                notify(response);

                if (response.success) {
                    table.ajax.reload();
                }
            });
        }
    });
});
