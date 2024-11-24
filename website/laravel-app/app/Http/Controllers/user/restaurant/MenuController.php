<?php

namespace App\Http\Controllers\user\restaurant;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MenuController extends Controller
{
    public function index()
    {
        $menu = [];
        $url = "http://restaurantapi:8080/api/menu/getMenu";
        try {
            $menuResponse = Http::get($url);
        }catch (\Exception $e) {
            Log::error('Er is een fout opgetreden bij het ophalen van de menu: ' . $e->getMessage());
            return back()->withErrors('Er is een fout opgetreden bij het ophalen van de menu.');
        }
        try {
            if ($menuResponse->successful()) {
                $menu = $menuResponse->json();

                $desertItems = $menu['desertItems'];
                $foodItems = $menu['foodItems'];
                $drinkItems = $menu['drinkItems'];

                return view('user.restaurant.menu', ['desertItems' => $desertItems, 'foodItems' => $foodItems, 'drinkItems' => $drinkItems]);
            } else {
                return back()->withErrors('Kon geen menu ophalen.');
            }
        } catch (\Exception $e) {
            Log::error('Er is een fout opgetreden bij het verwerken van de menu: ' . $e->getMessage());
            return back()->withErrors('Er is een fout opgetreden bij het verwerken van de menu.');
        }

    }
}
