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

        if(errors.hasOwnProperty('errors')) {
            validationErrors(errors.errors);
        } else {
            notify(errors);
        }
    }).done(function (response) {
        notify(response);

        if (response.success) {
            $('#new-task-modal').modal('hide');
            table.ajax.reload();
        }
    });

    e.preventDefault();
});

$('#new-task-modal').on('show.bs.modal', function () {
    $('#new-task')[0].reset();
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

                if (json.data[x].status === 'open') {
                    buttons += '<button data-id="' + json.data[x].id + '" class="btn btn-xs btn-success complete" title="Mark as Complete"><i class="fa fa-check fa-fw"></i></button>';
                } else {
                    buttons += '<button data-id="' + json.data[x].id + '" class="btn btn-xs btn-info reopen" title="Reopen"><i class="fa fa-tasks fa-fw"></i></button>';
                }

                buttons += ' ';

                buttons += '<button data-id="' + json.data[x].id + '" class="btn btn-xs btn-primary edit" title="Edit" data-toggle="modal" data-target="#update-task-modal"><i class="fa fa-pencil fa-fw"></i></button>';

                buttons += ' ';

                buttons += '<button data-id="' + json.data[x].id + '" class="btn btn-xs btn-danger delete" title="Delete"><i class="fa fa-trash fa-fw"></i></button>';

                columns.push([
                    '<a href="#" data-id="' + json.data[x].id + '" class="view">' + json.data[x].title + '</a>',
                    json.data[x].status,
                    buttons
                ]);
            }

            json.data = columns;

            return JSON.stringify(json);
        }
    }
});

$('#all-tasks').on('click', '.view', function (e) {
    var id = $(this).data('id');

    $.get({
        url: '/tasks/' + id,
        dataType: 'json'
    }).fail(function (data) {
        notify(data.responseJSON);
    }).done(function (response) {
        $('#view-task-modal').modal('show');
        $('#view-task-modal').find('h5').text(response.data.title);
        $('#view-task-modal').find('.modal-body').text(response.data.description);
    });

    e.preventDefault();
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
        $('#title').val(response.data.title);
        $('#description').val(response.data.description);
    });
});

$('#all-tasks').on('click', '.complete', function () {
    var id = $(this).data('id');

    swal({
        title: 'Mark this task as complete?',
        icon: 'info',
        buttons: {
            cancel: "Cancel",
            confirm: {
                text: "Yes",
                closeModal: false
            }
        },
        closeOnClickOutside: false,
    })
    .then((completed) => {
        if (completed) {
            $.ajax({
                type: 'PATCH',
                url: '/tasks/status/' + id,
                data: { status: 'complete' },
                dataType: 'json'
            }).fail(function (data) {
                notify(data.responseJSON);
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

$('#all-tasks').on('click', '.reopen', function () {
    var id = $(this).data('id');

    swal({
        title: 'Reopen this task?',
        icon: 'info',
        buttons: {
            cancel: "Cancel",
            confirm: {
                text: "Yes",
                closeModal: false
            }
        },
        closeOnClickOutside: false,
    })
    .then((open) => {
        if (open) {
            $.ajax({
                type: 'PATCH',
                url: '/tasks/status/' + id,
                data: { status: 'open' },
                dataType: 'json'
            }).fail(function (data) {
                notify(data.responseJSON);
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
            }).fail(function (data) {
                notify(data.responseJSON);
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
