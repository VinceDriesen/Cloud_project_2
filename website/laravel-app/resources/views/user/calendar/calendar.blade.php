@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function () {
            var SITEURL = "{{ url('/') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Laad events vanuit de server
            var events = @json($events);

            var calendar = $('#calendar').fullCalendar({
                editable: false,
                events: events,
                displayEventTime: true,
                selectable: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay',  // Knoppen voor maand-, week- en dagweergave
                },
                defaultView: 'month',  // Standaardweergave is maand
                eventRender: function (event, element, view) {
                    event.allDay = (event.allDay === 'true');
                },
                select: function (start, end, allDay) {
                    // Format de start- en einddatum om ze in de URL te kunnen gebruiken
                    var agendaID = $('input[name="agenda_id"]').val();
                    var startFormatted = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var endFormatted = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                    // Navigeer naar de `calendar/newevent` pagina met start- en einddatum in de URL
                    window.location.href = SITEURL + "/calendar/newevent?start=" + encodeURIComponent(startFormatted) + "&end=" + encodeURIComponent(endFormatted) + "&agenda_id=" + agendaID;
                },

                eventClick: function (event) {
                    // Stel de URL voor de edit pagina in
                    var editUrl = SITEURL + '/calendar/editevent?eventId=' + event.id;

                    // Vraag de gebruiker of ze willen bewerken
                    var editMsg = confirm("Do you want to edit this event?");

                    if (editMsg) {
                        // Navigeer naar de edit pagina
                        window.location.href = editUrl;
                    }
                }
            });
        });

        function displayMessage(message) {
            toastr.success(message, 'Event');
        }
    </script>
@endpush

@section('content')
<div class="container mx-auto px-4">
    <input type="hidden" name="agenda_id" value="{{ $agenda['id'] }}" />
    <h1 class="text-2xl font-bold text-primary-text-color">Laravel Calendar</h1>
    <div id='calendar' class="shadow-lg rounded-md p-4 mt-4" style="background-color: var(--component); border-color: var(--border); color: var(--primary-text-color);">
    </div>
</div>
@endsection
