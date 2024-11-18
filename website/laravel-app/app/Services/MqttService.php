<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttService
{
    protected $mqttClient;
    protected $data = [];

    public function __construct()
    {
        $server = env('MQTT_BROKER_HOST', 'mqtt5_broker');
        $port = env('MQTT_BROKER_PORT', 1883);
        $clientId = 'sensor_listener';

        $this->mqttClient = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings())
            ->setUsername(env('MQTT_USERNAME'))
            ->setPassword(env('MQTT_PASSWORD'));

        $this->mqttClient->connect($connectionSettings);
    }

    public function subscribeAndGetData($topic)
    {
        $this->mqttClient->subscribe($topic, function ($topic, $message) {
            $this->data = json_decode($message, true);
            Log::info("Received message on topic $topic: $message");
            $this->mqttClient->interrupt();
        });
        $this->mqttClient->loop(true, 10);

        $this->mqttClient->disconnect();

        return $this->data;
    }

    protected function storeData($data)
    {
        Log::info('Stored data: ' . json_encode($data));
    }
}
