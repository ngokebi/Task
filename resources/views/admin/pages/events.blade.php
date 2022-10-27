{{-- @extends('admin.pages.index')
@section('index')
    <div id="calendar"></div>

    <script>
        $(document).ready(function() {

            var SITEURL = "{{ url('/admin') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#calender').fullCalendar({
                editable: true,
                editable: true,
                events: SITEURL + "/task",
                eventLimit: true,
                displayEventTime: true,
                eventRender: function(event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                eventClick: function(event, jsEvent, view) {
                    endtime = $.fullCalendar.moment(event.end).format('h:mm');
                    starttime = $.fullCalendar.moment(event.start).format('dddd, MMMM Do YYYY, h:mm');
                    $('#modalTitle').html(event.title);
                    $('#modalWhen').text(mywhen);
                    $('#eventID').val(event.id);
                    $('#calendarModal').modal();
                },
                select: function(start_date, end_date, allDay) {
                    var title = prompt('Event Name:');
                    if (title) {
                        var start_date = $.fullCalendar.formatDate(start_date, "Y-MM-DD HH:mm:ss");
                        var end_date = $.fullCalendar.formatDate(end_date, "Y-MM-DD HH:mm:ss");
                        $.ajax({
                            url: SITEURL + "/task-ajax",
                            data: {
                                title: title,
                                description: description,
                                user_id: user_id,
                                start_date: start_date,
                                end_date: end_date,
                                type: 'create'
                            },
                            type: "POST",
                            success: function(data) {
                                displayMessage("Event created.");

                                calendar.fullCalendar('renderEvent', {
                                    id: data.id,
                                    title: title,
                                    start: start_date,
                                    end: end_date,
                                    allDay: allDay
                                }, true);
                                calendar.fullCalendar('unselect');
                            }
                        });
                    }
                },
                eventDrop: function(event, delta) {
                    var event_start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                    var event_end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");

                    $.ajax({
                        url: SITEURL + '/task-ajax',
                        data: {
                            title: event.event_name,
                            start: event_start,
                            end: event_end,
                            id: event.id,
                            type: 'edit'
                        },
                        type: "POST",
                        success: function(response) {
                            displayMessage("Event updated");
                        }
                    });
                },
                eventClick: function(event) {
                    var eventDelete = confirm("Are you sure?");
                    if (eventDelete) {
                        $.ajax({
                            type: "POST",
                            url: SITEURL + '/task-ajax',
                            data: {
                                id: event.id,
                                type: 'delete'
                            },
                            success: function(response) {
                                calendar.fullCalendar('removeEvents', event.id);
                                displayMessage("Event removed");
                            }
                        });
                    }
                }
            });
        });

        function displayMessage(message) {
            toastr.success(message, 'Event');
        }
    </script>
@endsection --}}


@extends('admin.pages.index')
@section('index')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <div class="pagetitle">
        <h1>Tasks</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Tasks</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <!-- Reports -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    New Task
                </button>
                <h5 class="card-title">Tasks <span>/Calendar</span></h5>
                <div id='calendar'></div>
                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Task</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="title" name="title">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="start" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" name="start" id="start">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="end" class="form-label">End Date</label>
                                            <input type="date" class="form-control" name="end" id="end">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="user_id" class="form-label">Task Owner</label>
                                            <select class="form-select" name="user_id" id="user_id"
                                                aria-label="Default select example">
                                                <option value="">Choose...</option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="save-event">Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
            </div>
        </div>
    </div><!-- End Reports -->

    {{-- Scripts --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var SITEURL = "{{ url('/admin') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var calendar = $('#calendar').fullCalendar({
                editable: true,
                // events: SITEURL + "/calender",
                events: function(start, end, timezone, callback) {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                    $.ajax({
                        type: 'GET',
                        url: SITEURL + "/task",
                        data: {
                            start: start,
                            end: end,
                        },
                        dataType: 'json',
                        success: function(data) {
                            var events = [];
                            $(data).each(function() {
                                events.push({
                                    id: $(this).attr('id'),
                                    title: $(this).attr('title'),
                                    description: $(this).attr(
                                        'description'),
                                    user_id: $(this).attr('user_id'),
                                    admin_id: $(this).attr('admin_id'),
                                    start: $(this).attr('start'),
                                    end: $(this).attr('end'),
                                });
                            });
                            callback(events);
                        },
                        error: function(data) {
                            alert("Ajax call error");
                            return false;
                        },
                    });
                },
                displayEventTime: false,
                editable: true,
                eventRender: function(event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    // Display the modal.
                    // You could fill in the start and end fields based on the parameters
                    $(".modal").modal("show");
                    $(".modal")
                        .find("#title")
                        .val("");
                    $(".modal")
                        .find("#description")
                        .val("");
                    $(".modal")
                        .find("#user_id")
                        .val("");
                    $(".modal")
                        .find("#start")
                        .val("");
                    $(".modal")
                        .find("#end")
                        .val("");
                    $("#save-event").on("click", function(event) {
                        var title = $("#title").val();
                        var description = $("#description").val();
                        var start = $("#start").val();
                        var end = $("#end").val();
                        var user_id = $("#user_id").val();

                        $.ajax({
                            url: SITEURL + "/task/create",
                            data: {
                                title: title,
                                description: description,
                                start: start,
                                user_id: user_id,
                                end: end,
                                type: 'add'
                            },
                            type: "POST",
                            success: function(data) {
                                Swal.fire(
                                    'New Task!',
                                    'Event successfully created!',
                                    'success'
                                )
                                calendar.fullCalendar('renderEvent', {
                                    id: data.id,
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay
                                }, true);
                                calendar.fullCalendar('unselect');
                            }
                        });
                    });
                    $("#save-event").click(function() {
                        $("#staticBackdrop").modal('hide');
                    });
                    $("input").prop("readonly", false);
                },

                eventClick: function(event, data) {

                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                    $(".modal").modal("show");
                    $(".modal")
                        .find("#title")
                        .val(event.title);
                    $(".modal")
                        .find("#description")
                        .val(event.description);
                    $(".modal")
                        .find("#start")
                        .val(start);
                    $(".modal")
                        .find("#end")
                        .val(end);
                    $(".modal")
                        .find("#user_id")
                        .val(event.user_id);
                    var id = event.id;
                    $("#save-event").on("click", function(event) {
                        var title = $("#title").val();
                        var description = $("#description").val();
                        var start = $("#start").val();
                        var end = $("#end").val()
                        var user_id = $("#user_id").val();

                        // alert(end);
                        // alert(title);
                        // alert(description);
                        // alert(start);
                        // alert(user_id);
                        // alert(id);

                        $.ajax({
                            url: SITEURL + '/task/update',
                            data: {
                                title: title,
                                description: description,
                                start: start,
                                user_id: user_id,
                                end: end,
                                id: id,
                                type: 'update'
                            },
                            type: "POST",
                            success: function(data) {
                                Swal.fire(
                                    'Updated Task!',
                                    'Event successfully updated!',
                                    'success'
                                )
                                location.reload(true);
                                calendar.fullCalendar('renderEvent', {
                                    id: data.id,
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay
                                }, true);
                                calendar.fullCalendar('unselect');
                            }
                        });
                    });
                    $("#save-event").click(function() {
                        $("#staticBackdrop").modal('hide');
                    });
                    $("input").prop("readonly", false);
                },

                eventRender: function(event, element) {
                    /*add onclick functionality here*/
                    element
                        .find(".fc-content")
                        .prepend(
                            "<span class='closeon material-icons' id='hide'><i class='ri-close-line'></i></span>"
                        );
                    element.find(".closeon").on("click", function() {

                        $(".modal").modal("hide");
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: "POST",
                                    url: SITEURL + '/task/delete',
                                    data: {
                                        id: event.id,
                                        type: 'delete'
                                    },
                                    success: function(response) {
                                        calendar.fullCalendar(
                                            'removeEvents', event.id);
                                        Swal.fire(
                                            'Deleted!',
                                            'Event successfully deleted!.',
                                            'success'
                                        )
                                    }
                                });
                            }
                        })

                    });
                }
            });
        });
    </script>
@endsection
