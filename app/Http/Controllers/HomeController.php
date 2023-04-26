<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class HomeController extends Controller
{
    public function getHistoricalData(Request $request) {
        // $request->validate([
        //     'symbol' => 'required',
        //     'start_date' => 'required|date|before_or_equal:today',
        //     'end_date' => 'required|date|after_or_equal:start_date',
        //     'email' => 'required|email',
        // ]);

        $url = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data';

        try {
            return Http::withHeaders([
                    'x-rapidapi-key' => env('RAPID_API_KEY'),
                    'x-rapidapi-host' => 'yh-finance.p.rapidapi.com',
                    'useQueryString' => true,
                ])
                ->get($url, [
                    'symbol' => $request->input('symbol'),
                    'region' => 'US',
                ])
                ->throw()
                ->json();
        }
        catch(Exception $e) {
            logger()->error($e->getMessage());
            return ["status"=>"0", "message"=> $e->getMessage()];
            return ["status"=>"0", "message"=>"Error getting historical data"];
        }
    }
    
}
