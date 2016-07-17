<?php

use Carbon\Carbon;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Request;

class ChargeTest extends PHPUnit_Framework_TestCase
{

    use SetupTrait;

    /**
    *  Tests...
    */
    public function test_generic_user()
    {
        $this->assertEquals( 1, count($this->user) );
    }

    public function test_charge()
    {

        // Single charge

        $charge = $this->user->charge( 500 );
        $this->assertEquals( 500, $charge['total'] );
    }

    public function test_item_charge()
    {
        // Charging a single intem

        $item = [
            'name'   => 'Item 1',
            'amount' => 2,
            'value'  => 1000
        ];
        $charge = $this->user->charge( $item );
        $this->assertEquals( 2000, @$charge['total'] );
    }

    public function test_multiple_items_charge()
    {

        $items = [
            [
                'name'   => 'Item 1',
                'amount' => 1,
                'value'  => 1000
            ],
            [
                'name'   => 'Item 2',
                'amount' => 2,
                'value'  => 2000
            ]
        ];
        $charge = $this->user->charge( $items );
        $this->assertEquals( 5000, @$charge['total'] );

    }

    public function test_charge_metadata()
    {

        // $faker = Faker\Factory::create();
        // $payee_code = $faker->regexify('[a-fA-F0-9]{32}');

        $options = [];
        $options['shippings'] = [
            ['name'  => 'My Shipping', 'value' => 2000],
        ['name'  => 'Shipping to someone else', 'value' => 1000, /* 'payee_code' => $payee_code */ ]
    ];
    $options['metadata']    = [
        'custom_id'        => 'Product 001',
        'notification_url' => 'http://127.0.0.1/notification'
        ];
        $charge = $this->user->charge( 500, $options );

        $this->assertEquals( 3500, @$charge['total'] );
    }


    public function test_pay_charge_by_billet()
    {

        # Paying a charge
        // 1. Billet
        $charge = $this->user->charge( 500 );
        $billet = $this->user->payCharge( $charge['charge_id'], 'billet');

        $this->assertTrue( isset($billet['barcode']) && $billet['barcode'] !== NULL );
        $this->assertEquals( 500, @$billet['total'] );
        $this->assertEquals( 'waiting', @$billet['status'] );


        $charge = $this->user->charge( 500 );
        $options = [
            'expire_at'    => Carbon::now()->addWeeks(1)->format('Y-m-d'),
            'instructions' => [
                'Pay only with money',
                'Do not pay with gold'
            ]
        ];
        $billet = $this->user->payCharge( $charge['charge_id'], 'billet', $options);

        $this->assertTrue( isset($billet['barcode']) && $billet['barcode'] !== NULL );
        $this->assertEquals( 500, @$billet['total'] );
        $this->assertEquals( 'waiting', @$billet['status'] );

    }

    // public function test_pay_charge_by_card()
    // {
    //         2. Card
    //         $charge = $user->charge( 500 );
    //
    //         $payment_token = $this->get_payment_token();
    //
    //         $args['credit_card'] = [
    //                 'installments'    => 1,
    //                 'billing_address' => $user->billing_address,
    //                 'payment_token'   => $payment_token
    //             ];
    //
    //         $paybycard = $user->payCharge( $charge['charge_id'], 'card', $args);
    //
    //         $this->assertEquals( 'credit_card', $paybycard['payment'] );
    //         $this->assertEquals( 500, $paybycard['total'] );
    //         $this->assertEquals( 'waiting', $paybycard['status'] );
    //         $this->assertEquals( $paybycard['charge_id'], $charge['charge_id'] );
    // }


    public function test_charge_details()
    {

        # Charges details
        $charge        = $this->user->charge( 500 );

        $chargeDetails = $this->user->getCharge( $charge['charge_id'] );

        $this->assertEquals( 'new', $chargeDetails['status'] );


        $billet        = $this->user->payCharge( $charge['charge_id'], 'billet');

        $chargeDetails = $this->user->getCharge( $billet['charge_id'] );

        $this->assertEquals( 'waiting', $chargeDetails['status'] );


        # Updating charges details

        $metadata = [
            'custom_id'        => 'Product 001',
            'notification_url' => 'http://127.0.0.1/notification'
        ];
        $charge = $this->user->charge( 500, ['metadata' => $metadata] );

        $this->assertEquals( 'new', $charge['status'] );
        $this->assertEquals( 'Product 001', $charge['custom_id'] );
        $this->assertEquals( 'http://127.0.0.1/notification', $charge['notification_url'] );

        // Change metadata...
        $newMetadata = [
            'custom_id'        => 'Product 002-beta',
            'notification_url' => 'http://localhost/callback'
        ];
        $chargeDetails = $this->user->updateCharge( $charge['charge_id'], $newMetadata );

        $this->assertEquals( 'new', $chargeDetails['status'] );
        $this->assertEquals( 'Product 002-beta', $chargeDetails['custom_id'] );
        $this->assertEquals( 'http://localhost/callback',  $chargeDetails['notification_url'] );

    }

    # Resending billet
    public function test_resend_billet()
    {
        $charge = $this->user->charge( 500 );
        $billet = $this->user->payCharge( $charge['charge_id'], 'billet');

        $this->assertEquals( 'waiting', $billet['status'] );

        # Resend...
        $newBillet = $this->user->resendBillet( $billet['charge_id'], 'tonetlds@gmail.com');

        $this->assertTrue( $newBillet );
    }

    # Adding information to charge's history
    public function test_create_charge_history()
    {
        $charge = $this->user->charge( 500 );
        $charge = $this->user->createChargeHistory( $charge['charge_id'], "Info to be added to charges history" );

        $this->assertEquals( count( $charge['history'] ), 2 );
        $this->assertEquals( $charge['history'][0]['message'], "Cobrança criada" );
        $this->assertEquals( $charge['history'][1]['message'], "Info to be added to charges history" );
    }

    # Canceling a charge
    public function test_cancel_charge()
    {
        $charge = $this->user->charge( 500 );
        $this->assertEquals( $charge['status'], 'new' );

        $charge = $this->user->cancelCharge( $charge['charge_id'] );
        $this->assertEquals( $charge['status'], 'canceled' );
    }

    # Canceling a charge
    public function test_update_billet()
    {
        $charge = $this->user->charge( 500 );
        $billet = $this->user->payCharge( $charge['charge_id'], 'billet');

        $this->assertEquals( $billet['expire_at'], Carbon::now()->addWeeks(1)->format('Y-m-d') );

        $updatedBillet = $this->user->updateBillet( $charge['charge_id'], '2017-01-01');
        $this->assertEquals( $updatedBillet['code'], 200 );
    }

}
