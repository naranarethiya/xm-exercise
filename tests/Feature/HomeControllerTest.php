<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    private $validData = [
        'symbol' => 'AMRN',
        'start_date' => '2023-01-01',
        'end_date' => '2023-04-27',
        'email' => 'naranarethiya@gmail.com',
    ];

    public function atest_validation_symbol(): void
    {
        // invalid symbol test
        $data = $this->validData;
        $data['symbol'] = 'AMRN1';
        $response = $this->postJson('/api/historical-data', $data);
        $response->assertStatus(422);

        // required test
        $data = $this->validData;
        $data['symbol'] = '';
        $response = $this->postJson('/api/historical-data', $data);
        $response->assertStatus(422);

    }

    public function test_validation_start_date(): void
    {
        // invalid start date test
        $data = $this->validData;
        $data['start_date'] = date('Y-m-d', strtotime('+5 days'));
        $response = $this->postJson('/api/historical-data', $data);
        $response->assertStatus(422);

        // invalid end date test
        $data = $this->validData;
        $data['start_date'] = date('Y-m-d', strtotime('today'));
        $data['end_date'] = date('Y-m-d', strtotime('-2 days'));
        $response = $this->postJson('/api/historical-data', $data);
        $response->assertStatus(422);
    }

    public function test_validation_email(): void
    {
        // invalid email test
        $data = $this->validData;
        $data['email'] = 'naranarethiya';
        $response = $this->postJson('/api/historical-data', $data);
        $response->assertStatus(422);
    }

    public function test_validation(): void
    {
        $data = $this->validData;
        $response = $this->postJson('/api/historical-data', $data);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'prices' => [
                '*' => [
                    'close',
                    'date',
                    'high',
                    'low',
                    'open',
                ],
            ],
        ]);
    }

    public function test_send_historic(): void
    {
        $data = $this->validData;
        $response = $this->postJson('/api/historical-data/send', $data);
        $response->assertStatus(200);
    }

    public function test_symbols(): void
    {
        $response = $this->getJson('/api/company-symbols');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'Company Name',
                'Financial Status',
                'Market Category',
                'Round Lot Size',
                'Security Name',
                'Symbol',
                'Test Issue',
            ],
        ]);
    }
}
