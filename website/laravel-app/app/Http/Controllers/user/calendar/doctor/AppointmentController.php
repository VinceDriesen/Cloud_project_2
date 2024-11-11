<?php

namespace App\Http\Controllers\user\calendar\doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Traits\AgendaManager;

class AppointmentController extends Controller
{
    use AgendaManager;

    public function getAppointmentsFromDoctor($doctorId)
    {
        $response = $this->getAgenda($doctorId);
        $responseData = $response->original->getData();
        $agendaId = $responseData['agendaOwner']['id'];

        try {
            $client = \Softonic\GraphQL\ClientBuilder::build('http://agenda_api:8082/query');
            $query = <<<'QUERY'
                query appointmentsFromAgenda($agendaId: ID!) {
                    appointmentsFromAgenda(agendaId: $agendaId) {
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
            QUERY;

            $variables = [
                'agendaId' => $agendaId,
            ];

            $response = $client->query($query, $variables);

            if ($response->hasErrors()) {
                throw new Exception('GraphQL Error: ' . json_encode($response->getErrors()));
            }

            $appointments = $response->getData()['appointmentsFromAgenda'];

            Log::info('GraphQL Response Data:', (array) $appointments);

        } catch (Exception $e) {
            Log::error('Error fetching appointments: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $availableDays = [];
        $availableTimes = [];

        foreach ($appointments as $appointment) {
            if($appointment['patient'] != null) {
                continue;
            }
            $date = $appointment['agendaItem']['date'];

            $day = sprintf('%04d-%02d-%02d', $date['year'], $date['month'], $date['day']);
            if (!in_array($day, $availableDays)) {
                $availableDays[] = $day;
            }

            $time = sprintf('%02d:%02d', $date['hour'], $date['minute']);
            $appointmentId = $appointment['id'];

            $availableTimes[] = [
                'time' => $time,
                'appointmentId' => $appointmentId
            ];
        }

        return response()->json([
            'availableDays' => array_unique($availableDays),
            'availableTimes' => $availableTimes
        ]);
    }

}
