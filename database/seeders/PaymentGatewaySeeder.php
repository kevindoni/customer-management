<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentGateways = [
            [
                'name' => 'TriPay',
                'value' => 'tripay',
            ],
            [
                'name' => 'Midtrans',
                'value' => 'midtrans',
            ]
        ];

        foreach ($paymentGateways as $paymentGateway) {
            PaymentGateway::create(
                $paymentGateway
            );
        }
        //  PaymentGateway::create([
        //      'name' => 'TriPay',
        //      'value' => 'tripay',
        // ]);
        // PaymentGateway::create([
        //    'name' => 'Midtrans',
        //   'value' => 'midtrans',
        // ]);
    }
}
