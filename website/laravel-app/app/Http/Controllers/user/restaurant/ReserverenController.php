<?php

namespace App\Http\Controllers\user\restaurant;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\calendar\CalendarController;
use App\Http\Controllers\user\calendar\NewEventController;
use App\Traits\AgendaManager;
use Carbon\Carbon;
use Faker\Provider\UserAgent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReserverenController extends Controller
{
    use AgendaManager;

    public function index()
    {
        return view('user.restaurant.reserveren');
    }

    public function fetchTables(Request $request)
    {
        $outside = $request->tableLocation == 'outside' ? true : false;
        $responseAvailable = [];
        $responseUnavailable = [];
        $dateTime = $request->datetime;
        $duration = $request->duration;

        if (!$dateTime || !$duration) {
            return back()->withErrors('Verplichte velden ontbreken: starttijd en duur.');
        }

        $urlAvailable = "http://restaurantapi:8080/api/tables/available/" . ($outside ? "outside" : "inside");
        $urlInavailable = "http://restaurantapi:8080/api/tables/reserved/" . ($outside ? "outside" : "inside");

        try {
            $urlAvailable .= "?startTime=" . urlencode($dateTime) . "&duration=" . $duration;
            $urlInavailable .= "?startTime=" . urlencode($dateTime) . "&duration=" . $duration;

            $responseAvailable = Http::get($urlAvailable);
            $responseUnavailable = Http::get($urlInavailable);

            Log::info('Response Available Status: ', [$responseAvailable->status()]);
            Log::info('Response Available Body: ', [$responseAvailable->body()]);
            Log::info('Response Unavailable Status: ', [$responseUnavailable->status()]);
            Log::info('Response Unavailable Body: ', [$responseUnavailable->body()]);

        } catch (\Exception $e) {
            Log::error('Er is een fout opgetreden bij het ophalen van de tafels: ' . $e->getMessage());
            return back()->withErrors('Er is een fout opgetreden bij het ophalen van de tafels.');
        }
        try {
            if ($responseAvailable->successful() && $responseUnavailable->successful()) {
                $availableTables = $responseAvailable->json();
                $unavailableTables = $responseUnavailable->json();


                return view('user.restaurant.reserveren', ['availableTables' => $availableTables, 'unavailableTables' => $unavailableTables]);
            } else {
                return back()->withErrors('Kon geen tafels ophalen.');
            }
        } catch (\Exception $e) {
            Log::error('Er is een fout opgetreden bij het verwerken van de tafels: ' . $e->getMessage());
            return back()->withErrors('Er is een fout opgetreden bij het verwerken van de tafels.');
        }
    }


    public function reserveTable(Request $request)
    {
        $validatedData = $request->validate([
            'tableId' => 'required|integer',
            'datetime' => 'required|date',
            'duration' => 'required|integer|min:1|max:4',
            'name' => 'required|string|max:255',
        ]);

        $tableId = $validatedData['tableId'];
        $dateTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->datetime)->format('Y-m-d\TH:i:s');
        $duration = $validatedData['duration'];
        $name = $validatedData['name'];

        $url = 'http://restaurantapi:8080/api/tables/' . $tableId . '/reserve';

        try {
            Log::info('Sending request to reserve table', [
                'url' => $url,
                'data' => [
                    'startTime' => $dateTime,
                    'duration' => (int) $duration,
                    'reservedBy' => $name,
                ],
            ]);

            $response = Http::put($url, [
                'startTime' => $dateTime,
                'duration' => (int) $duration,
                'reservedBy' => $name,
            ]);

            Log::info('Received response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);


            if ($response->successful()) {
                try {

                    $agendaRequest = new Request();
                    $response = $this->getAgenda(Auth::user()->id);
                    $responseData = $response->original->getData();
                    $agenda_id = $responseData['agendaOwner']['id'];
                    $agendaRequest->replace([
                        'type' => 'add',
                        'agendaId' => $agenda_id,
                        'title' => "Restaurant tafel reserving" ,
                        'description' => "Reservering voor tafel " . $tableId . " door " . $name,
                        'start' => $dateTime,
                        'duration' => $duration,
                    ]);

                    $this->ajax($agendaRequest);
                }
                catch (\Exception $e) {
                    Log::error('Er is een fout opgetreden bij het toevoegen van de reservatie aan de agenda!: ' . $e->getMessage());
                    return back()->withErrors('Er is een fout opgetreden bij het toevoegen van de reservatie aan de agenda!');
                }
                return redirect('/restaurant/reserveren')->with('success', 'Succes, het is toegevoegd aan de agenda!');
            } else {
                $errorMessage = $response->json('message', 'Kon geen tafel reserveren.');
                Log::error('Kon geen tafel reserveren: ' . $errorMessage);
                return back()->withErrors($errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Er is een fout opgetreden: ' . $e->getMessage());
            return back()->withErrors('Er is een fout opgetreden tijdens het reserveren. Probeer het opnieuw.');
        }
    }



}
