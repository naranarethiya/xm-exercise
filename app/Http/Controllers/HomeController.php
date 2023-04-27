<?php

namespace App\Http\Controllers;

use App\Mail\SendHistoricalData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function getCompanySymbols()
    {
        return Cache::remember('company-symbols', (2 * 60 * 60), function () {
            return Http::get('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json')
                ->throw()
                ->json();
        });
    }

    public function getHistoricalData(Request $request): array
    {
        $request->validate($this->getRules());

        $symbol = $request->input('symbol');
        $startDate = strtotime($request->input('start_date'));
        $endDate = strtotime($request->input('end_date'));

        try {
            $historicalData = Cache::remember('historic-'.$symbol, (2 * 60 * 60), function () use ($symbol) {
                $url = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data';

                return Http::withHeaders([
                    'x-rapidapi-key' => env('RAPID_API_KEY'),
                    'x-rapidapi-host' => 'yh-finance.p.rapidapi.com',
                    'useQueryString' => true,
                ])
                    ->get($url, [
                        'symbol' => $symbol,
                        'region' => 'US',
                    ])
                    ->throw()
                    ->json();
            });

            $historicalData = collect($historicalData['prices'] ?? [])
                ->whereBetween('date', [$startDate, $endDate]);

            return ['status' => '1', 'prices' => $historicalData->values()->all()];
        } catch (Exception $e) {
            logger()->error($e->getMessage());

            return ['status' => '0', 'message' => 'Error while getting historical data.'];
        }
    }

    public function sendHistoricData(Request $request): array
    {
        $request->validate($this->getRules());

        $symbols = array_column($this->getCompanySymbols(), 'Company Name', 'Symbol');

        Mail::to($request->email)->send(new SendHistoricalData(
            Carbon::parse($request->input('start_date')),
            Carbon::parse($request->input('end_date')),
            $request->input('symbol'),
            $symbols[$request->input('symbol')] ?? ''
        ));

        return ['status' => '1', 'message' => 'Email Send Successfully.'];
    }

    private function getRules(): array
    {
        return [
            'symbol' => [
                'required',
                Rule::in(array_column($this->getCompanySymbols(), 'Symbol')),
            ],
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'email' => 'required|email',
        ];
    }
}
