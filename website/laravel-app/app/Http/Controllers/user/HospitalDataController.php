<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Services\MqttService;
use Illuminate\Support\Facades\Log;

class HospitalDataController extends Controller
{
    protected $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    public function index()
    {
        $data = $this->mqttService->subscribeAndGetData('hospital/sensors');

        if (!$data) {
            Log::warning('No data received from MQTT topic.');
        }

        return view('user.hospitalData', ['data' => $data]);
    }
}
