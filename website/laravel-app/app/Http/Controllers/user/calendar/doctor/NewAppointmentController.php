<?php

namespace App\Http\Controllers\user\calendar\doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Traits\AgendaManager;

class NewAppointmentController extends Controller
{
    use AgendaManager;

    public function index()
    {
        $user = Auth::user();
        return view('user.calendar.doctor.newAppointment', ['user' => $user]);
    }

    public function createNewAppointment(Request $request)
    {
        $start = $request->input('startDateTime');
        $duration = $request->input('duration');
        $title = "New appointment";
        $description = "Doctor appointment";
        $response = $this->getAgenda(Auth::user()->id);
        $responseData = $response->original->getData();
        $agenda_id = $responseData['agendaOwner']['id'];


        $data = new Request();
        $data->replace([
            'type' => 'add',
            'agendaId' => $agenda_id,
            'title' => $title,
            'description' => $description,
            'start' => $start,
            'duration' => $duration,
        ]);

        $event = $this->ajax($data);

        try {
            $client = \Softonic\GraphQL\ClientBuilder::build('http://agenda_api:8082/query');

            $createAgendaMutation = <<<'MUTATION'
                mutation createAppointment($input: CreateAppointment!) {
                    createAppointment(input: $input) {
                        id
                        agendaItem {
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
                        }
                        patient
                        doctor
                        recurring
                    }
                }
            MUTATION;

            $variables = [
                'input' => [
                    'agendaItemId' => $event->original['id'],
                    'doctor' => $agenda_id,
                    'recurring' => 'NONE', 
                ],
            ];
            Log::info('GraphQL Variables:', $variables);

            $response = $client->query($createAgendaMutation, $variables);

            Log::info('GraphQL Response:', (array) $response);

            if ($response->hasErrors()) {
                throw new Exception('GraphQL Error: ' . json_encode($response->getErrors()));
            }

            return redirect()->route('calendar');
        } catch (Exception $e) {
            Log::error('Error creating appointment: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
