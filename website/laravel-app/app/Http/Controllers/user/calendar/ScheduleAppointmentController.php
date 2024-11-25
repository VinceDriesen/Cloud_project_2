<?php

namespace App\Http\Controllers\user\calendar;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Traits\AgendaManager;


class ScheduleAppointmentController extends Controller
{
    use AgendaManager;

    public function index()
    {
        $doctors = User::whereHas('doctor')->get();
        return view('user.calendar.scheduleAppointment', [
            'doctors' => $doctors
        ] );
    }

    public function scheduleAppointment(Request $request)
    {
        $appointment_id = $request->input('appointment_id');
        $response = $this->getAgenda(Auth::user()->id);
        $responseData = $response->original->getData();
        $agenda_id = $responseData['agendaOwner']['id'];

        try {
            $client = \Softonic\GraphQL\ClientBuilder::build('http://agenda_api:8082/query');

            $createAgendaMutation = <<<'MUTATION'
                mutation updateAppointment($id: ID!, $patient: Int) {
                    updateAppointment(id: $id, patient: $patient) {
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
                'id' => $appointment_id,
                'patient' => $agenda_id
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

        return redirect()->route('calendar');
    }

    public function getOptimalAppointments(Request $request)
    {
        $number_of_appointments = $request->input('number_of_appointments');
        $response = $this->getAgenda(Auth::user()->id);
        $responseData = $response->original->getData();
        $agenda_id = $responseData['agendaOwner']['id'];

        try {
            $client = \Softonic\GraphQL\ClientBuilder::build('http://agenda_api:8082/query');

            $createAgendaMutation = <<<'QUERY'
            query bestAppointments($userID: Int!, $maxRecommendations: Int!) {
                bestAppointments(userID: $userID, maxRecommendations: $maxRecommendations) {
                    id
                    agendaItem {
                        id
                        title
                        description
                        duration
                        date {
                            id
                            day
                            month
                            year
                            hour
                            minute
                        }
                    }
                }
            }
            QUERY;

            $variables = [
                'userID' => (int)$agenda_id,
                'maxRecommendations' => (int)$number_of_appointments
            ];


            $response = $client->query($createAgendaMutation, $variables);

            if ($response->hasErrors()) {
                throw new Exception('GraphQL Error: ' . json_encode($response->getErrors()));
            }
            return response()->json(['appointments' => $response->getData()['bestAppointments']]);
        } catch (Exception $e) {
            Log::error('Error getting appointments: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
