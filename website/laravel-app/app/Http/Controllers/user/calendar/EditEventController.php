<?php

namespace App\Http\Controllers\user\calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AgendaManager;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

class EditEventController extends Controller
{
    use AgendaManager;

    public function index(Request $request)
    {
        $event_id = $request->query('eventId');

        if (!$event_id) {
            return redirect()->route('calendar');
        }

        $event_info = $this->getEvent($event_id);

        if ($event_info) {
            $startDateTime = \Carbon\Carbon::create(
                $event_info['date']['year'],
                $event_info['date']['month'],
                $event_info['date']['day'],
                $event_info['date']['hour'],
                $event_info['date']['minute'],
                0
            )->format('Y-m-d\TH:i');

            $event_info['starttimestamp'] = $startDateTime;
            $event_info['endtimestamp'] = $startDateTime;

            $endDateTime = \Carbon\Carbon::parse($startDateTime)->addMinutes($event_info['duration'])->format('Y-m-d\TH:i');
            $event_info['endtimestamp'] = $endDateTime;
        }
        return view('user.calendar.editEvent', [
            'event' => $event_info,
        ]);
    }



    public function editEvent(Request $request)
    {
        $event_id = $request->input('eventId');
        $title = $request->input('title');
        $description = $request->input('description');
        $start = $request->input('startDateTime');
        $end = $request->input('endDateTime');

        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        $duration = ($endTimestamp - $startTimestamp) / 60;

        $data = new Request();

        $data->replace([
            'type' => 'update',
            'eventId' => $event_id,
            'title' => $title,
            'description' => $description,
            'start' => $start,
            'duration' => $duration,
        ]);


        $this->ajax($data);

        return redirect()->route('calendar');
    }

    public function getEvent(Int $event_id)
    {
        $query = <<<'QUERY'
            query agendaItem($id: ID!) {
                agendaItem(id: $id) {
                    id
                    title
                    description
                    duration
                    date {
                        day
                        month
                        year
                        hour
                        minute
                    }
                    participants {
                        id
                    }
                }
            }
        QUERY;

        $variables = [
            'id' => $event_id,
        ];

        $client = \Softonic\GraphQL\ClientBuilder::build('http://agenda_api:8082/query');

        try {
            $response = $client->query($query, $variables);
            return $response->getData()['agendaItem'];
        } catch (Exception $e) {
            Log::error('Error in fetching or creating event: ' . $e->getMessage());
            return response()->json(['error' => 'Could not load calendar. Please try again later.'], 500);
        }
    }

}
