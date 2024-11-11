<?php

namespace App\Http\Controllers\user\calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Softonic\GraphQL\ClientBuilder;
use Softonic\GraphQL\Response;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\AgendaManager;

class CalendarController extends Controller
{
    use AgendaManager;

    public function index()
    {
        try {
            $agenda = $this->getNewAgenda();
            $events = [];
            if (isset($agenda['agendaItems'])) {
                $events = array_map(function ($item) {
                    
                    $start = sprintf('%s-%s-%s %s:%s',
                        $item['date']['year'],
                        str_pad($item['date']['month'], 2, '0', STR_PAD_LEFT),
                        str_pad($item['date']['day'], 2, '0', STR_PAD_LEFT),
                        str_pad($item['date']['hour'], 2, '0', STR_PAD_LEFT),
                        str_pad($item['date']['minute'], 2, '0', STR_PAD_LEFT)
                    );

                    $startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $start);
                    $endDateTime = $startDateTime->addMinutes($item['duration']);

                    $end = $endDateTime->format('Y-m-d H:i');

                    return [
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'start' => $start,
                        'end' => $end,
                    ];
                }, $agenda['agendaItems']);
            }

            return view('user.calendar.calendar', [
                'agenda' => $agenda,
                'events' => $events,
            ]);

        } catch (Exception $e) {
            Log::error('Error in fetching or creating agenda: ' . $e->getMessage());
            return response()->json(['error' => 'Could not load calendar. Please try again later.'], 500);
        }
    }

    private function getNewAgenda()
    {
        $response = $this->getAgenda(Auth::user()->id);
        $responseData = $response->original;

        if ($responseData->hasErrors()) {
            $errors = $responseData->getErrors();
            if (isset($errors[0]) && $errors[0]['message'] === "the requested element is null which the schema does not allow") {
                Log::warning('Agenda bestaat niet of is leeg voor de opgegeven gebruiker.');
                $agenda = $this->createNewAgenda();
            } else {
                throw new Exception('Error fetching agenda: ' . json_encode($errors));
            }
        } else {
            Log::info('Agenda bestaat al voor de opgegeven gebruiker.');
            $agenda = $responseData->getData()['agendaOwner'];
        }
        return $agenda;
    }


    private function createNewAgenda()
    {
        $user = Auth::user();
        $client = \Softonic\GraphQL\ClientBuilder::build('http://agenda_api:8082/query');

        $role = $user->isDoctor() ? 'DOCTOR' : 'PATIENT';

        $createAgendaMutation = <<<'MUTATION'
            mutation createAgenda($input: CreateAgenda!) {
                createAgenda(input: $input) {
                    id
                    owner
                    role
                    agendaItems {
                        id
                        title
                        description
                        date {
                            day
                            month
                            year
                            hour
                            minute
                        }
                    }
                }
            }
        MUTATION;

        $variables = [
            'input' => [
                'owner' => $user->id,
                'role' => $role,
            ],
        ];

        $response = $client->query($createAgendaMutation, $variables);

        if ($response->hasErrors()) {
            throw new Exception('GraphQL Error: ' . json_encode($response->getErrors()));
        }

        $agenda = $response->getData()['createAgenda'];

        return $agenda;
    }
}
