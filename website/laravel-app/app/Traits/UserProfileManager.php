<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use SoapClient;

trait UserProfileManager
{
    protected function createProfile(User $user)
    {
        Log::info("createProfile aangeroepen voor gebruiker ID: {$user->id}");

        if (!$user) {
            Log::error("User not found");
            return false;
        }

        $userId = $user->id;

        try {
            $soapClient = new \SoapClient("http://user_profile_api:5109/ProfileService.asmx?wsdl");

            $params = ['userId' => $userId];
            Log::info("SOAP Request Parameters (GetProfileById): " . json_encode($params));

            $testResponse = $soapClient->__soapCall('GetProfileById', [$params]);

            if (empty((array)$testResponse)) {
                Log::info("Profiel niet gevonden, maak een nieuw profiel aan");
                Log::info("SOAP Request Parameters (CreateProfile): " . json_encode($params));

                $mainResponse = $soapClient->__soapCall('CreateProfile', [$params]);

                if (!empty((array)$mainResponse)) {
                    Log::info("Profiel succesvol aangemaakt voor gebruiker ID: {$userId}");
                    return true;
                } else {
                    throw new \Exception("Fout bij aanmaken profiel voor gebruiker ID: {$userId}. Geen resultaat terug ontvangen.");
                }
            } else {
                throw new \Exception("Error bij aanmaken van Profiel voor gebruiker ID: {$userId}. Profiel bestaat al. Antwoord: " . json_encode($testResponse));
            }
        } catch (\Exception $e) {
            Log::error("Fout bij aanmaken profiel: " . $e->getMessage());
            return false;
        }
    }
}
