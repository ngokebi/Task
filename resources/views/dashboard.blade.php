<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Task <b></b>
        </h2>
    </x-slot>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <div class="py-12">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">

                        <div class="card-header"> Your Tasks </div>
                        <div class="card-body">
                            <!-- Reports -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div id='calendar'></div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                                                    <label for="title"
                                                                        class="form-label">Title</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="title" name="title">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="mb-3">
                                                                    <label for="description"
                                                                        class="form-label">Description</label>
                                                                    <textarea class="form-control" id="description" disabled name="description" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="mb-3">
                                                                    <label for="start" class="form-label">Start
                                                                        Date</label>
                                                                    <input disabled type="date" class="form-control"
                                                                        name="start" id="start">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="mb-3">
                                                                    <label for="end" class="form-label">End
                                                                        Date</label>
                                                                    <input disabled type="date" class="form-control"
                                                                        name="end" id="end">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                            data-bs-dismiss="modal">Close</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var SITEURL = "{{ url('/') }}";
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
                        url: SITEURL + "/dashboard",
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
                displayEventTime: true,
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
                }
            });
        });
    </script>
</x-app-layout>
