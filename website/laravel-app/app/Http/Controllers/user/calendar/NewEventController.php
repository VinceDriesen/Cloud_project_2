<?php

namespace App\Http\Controllers\user\calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AgendaManager;


class NewEventController extends Controller
{
    use AgendaManager;

    public function index(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $agenda_id = $request->query('agenda_id');
        return view('user.calendar.newEvent', [
            'start' => $start,
            'end' => $end,
            'agenda_id' => $agenda_id,
        ]);
    }

    public function saveEvent(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $start = $request->input('startDateTime');
        $end = $request->input('endDateTime');
        $agenda_id = $request->input('agenda_id');

        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        $duration = ($endTimestamp - $startTimestamp) / 60;

        $data = new Request();

        $data->replace([
            'type' => 'add',
            'agendaId' => $agenda_id,
            'title' => $title,
            'description' => $description,
            'start' => $start,
            'duration' => $duration,
        ]);


        $this->ajax($data);

        return redirect()->route('calendar');
    }
}
