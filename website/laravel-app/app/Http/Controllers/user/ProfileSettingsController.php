<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProfileSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $userDetails = null;

        try {
            $soapClient = new \SoapClient("http://user_profile_api:5109/ProfileService.asmx?wsdl");
            $params = ['userId' => $userId];
            Log::info("SOAP Request Parameters (GetProfileById): " . json_encode($params));

            $response = $soapClient->__soapCall('GetProfileById', [$params]);
            Log::info("GetProfileById Response: " . print_r($response, true));

            if (empty((array)$response)) {
                Log::info("Profiel niet gevonden voor gebruiker ID: {$userId}");
            } else {
                $userDetails = $response->GetProfileByIdResult;
                Log::info("Profiel gevonden voor gebruiker ID: {$userId}");
            }
        } catch (\Exception $e) {
            Log::error("Fout bij ophalen profiel: " . $e->getMessage());
            return false;
        }


        return view('/user/profileSettings', [
            'user' => $user,
            'userDetails' => $userDetails,
            'address' => $userDetails->Address ?? null // Verondersteld dat Address een eigenschap is van userDetails
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->save();

        try {
            $soapClient = new \SoapClient("http://user_profile_api:5109/ProfileService.asmx?wsdl");

            // Bouw de parameters array
            $params = [
                'userId' => $user->id,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'nationality' => $request->nationality,
                'phoneNumber' => $request->phoneNumber,
                'city' => $request->city,
                'country' => $request->country,
                'postalCode' => $request->postalCode,
                'state' => $request->state,
                'street' => $request->street,
            ];

            // Log de parameters voor debugging
            Log::info("SOAP Request Parameters (UpdateProfile): " . json_encode($params));

            // Roep de UpdateProfile SOAP methode aan
            $response = $soapClient->__soapCall('UpdateProfile', [$params]);

            // Controleer de respons
            if ($response && $response->UpdateProfileResult) {
                Log::info("Profile successfully updated for user ID: {$user->id}");
            } else {
                Log::error("Failed to update profile for user ID: {$user->id}");
            }
        } catch (\Exception $e) {
            Log::error("Error updating profile: " . $e->getMessage());
        }

        return redirect()->route('profileSettings');
    }

}
