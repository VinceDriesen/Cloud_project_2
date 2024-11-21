<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Softonic\GraphQL\ClientBuilder;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait AgendaManager
{
    protected function ajax(Request $request)
    {
        Log::info('Received AJAX request to update calendar.');

        $client = ClientBuilder::build('http://agenda_api:8082/query');

        try {
            switch ($request->type) {
                case 'add':
                    $mutation = <<<'MUTATION'
                    mutation createAgendaItem($agendaId: ID!, $input: CreateAgendaItem!) {
                        createAgendaItem(agendaId: $agendaId, input: $input) {
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
                    }
                    MUTATION;


                    $variables = [
                        'agendaId' => (int)$request->agendaId,
                        'input' => [
                            'title' => $request->title,
                            'description' => $request->description,
                            'duration' => (int)$request->duration,
                            'participants' => [(int)$request->agendaId],
                            'date' => [
                                'day' => (int)date('d', strtotime($request->start)),
                                'month' => (int)date('m', strtotime($request->start)),
                                'year' => (int)date('Y', strtotime($request->start)),
                                'hour' => (int)date('H', strtotime($request->start)),
                                'minute' => (int)date('i', strtotime($request->start)),
                            ],
                        ],
                    ];

                    $response = $client->query($mutation, $variables);
                    if ($response->hasErrors()) {
                        throw new Exception('Error creating event: ' . json_encode($response->getErrors()));
                    }

                    $event = $response->getData()['createAgendaItem'];
                    return response()->json($event);

                case 'update':
                    $mutation = <<<'MUTATION'
                    mutation updateAgendaItem($id: ID!, $input: UpdateAgendaItem!) {
                        updateAgendaItem(id: $id, input: $input) {
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
                    MUTATION;

                    $variables = [
                        'id' => (int)$request->eventId,
                        'input' => [
                            'title' => $request->title,
                            'description' => $request->description,
                            'duration' => (int)$request->duration,
                            'date' => [
                                'day' => (int)date('d', strtotime($request->start)),
                                'month' => (int)date('m', strtotime($request->start)),
                                'year' => (int)date('Y', strtotime($request->start)),
                                'hour' => (int)date('H', strtotime($request->start)),
                                'minute' => (int)date('i', strtotime($request->start)),
                            ],
                        ],
                    ];

                    $response = $client->query($mutation, $variables);
                    if ($response->hasErrors()) {
                        throw new Exception('Error updating event: ' . json_encode($response->getErrors()));
                    }

                    $event = $response->getData()['updateAgendaItem'];
                    return response()->json($event);

                case 'delete':
                    $mutation = <<<'QUERY'
                        mutation deleteAgendaItem($id: ID!) {
                            deleteAgendaItem(id: $id) {
                                id
                            }
                        }
                    QUERY;

                    $variables = ['id' => $request->id];

                    $response = $client->query($mutation, $variables);
                    if ($response->hasErrors()) {
                        throw new Exception('Error deleting event: ' . json_encode($response->getErrors()));
                    }

                    return response()->json(['status' => 'Event deleted successfully']);

                default:
                    return response()->json(['error' => 'Invalid request type'], 400);
            }
        } catch (Exception $e) {
            Log::error('Error handling AJAX request: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function getAgenda($id)
    {
        $query = <<<'QUERY'
            query agendaOwner($ownerId: Int!) {
                agendaOwner(ownerId: $ownerId)
                {
                    id
                    owner
                    role
                    agendaItems {
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
            'ownerId' => $id,
        ];


        $client = \Softonic\GraphQL\ClientBuilder::build('http://agenda_api:8082/query');


        Log::info("Querying the agenda API to check if an agenda already exists for the user.");
        $response = $client->query($query, $variables);
        return response()->json($response);
    }
}
