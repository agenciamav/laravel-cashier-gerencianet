<?php

use Carbon\Carbon;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Request;

class ChargeTest extends PHPUnit_Framework_TestCase
{

    private $options;
    private $plans;

    private $user;

    public static function setUpBeforeClass()
    {
        if (file_exists(__DIR__.'/../.env')) {
            $dotenv = new Dotenv\Dotenv(__DIR__.'/../');
            $dotenv->load();
        }
    }

    public function setUp()
    {

        Eloquent::unguard();

        $db = new DB;
        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $db->bootEloquent();
        $db->setAsGlobal();

        // Create users table
        $this->schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('email');
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->biginteger('cpf')->nullable();
            $table->date('birth')->nullable();
            $table->string('gerencianet_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamps();
        });

        // Create subscriptions table
        $this->schema()->create('subscriptions', function ($table) {

            $table->increments('id');

            $table->string('name')->nullable();
            $table->integer('user_id');
            $table->integer('subscription_id');
            $table->string('status')->default("new");
            $table->string('custom_id')->nullable();
            $table->string('notification_url')->nullable();
            $table->string('payment_method')->nullable();
            $table->datetime('next_execution')->nullable();
            $table->datetime('next_expire_at')->nullable();
            $table->integer('plan_id')->nullable();
            $table->integer('occurrences')->default(0);

            // LOCAL TRIAL MANAGMENT
            $table->datetime('trial_ends_at')->nullable();
            $table->datetime('ends_at')->nullable();

            $table->timestamps();

        });

        // ------------------------------------------------------

        $faker = Faker\Factory::create('pt_BR');

        $faker->addProvider(new \Faker\Provider\pt_BR\Person($faker));
        $faker->addProvider(new \Faker\Provider\en_US\PhoneNumber($faker));

        $user = User::create([
            'email'        => $faker->email,
            'name'         => $faker->name,
            'phone_number' => $faker->regexify('[1-9]{2}9?[0-9]{8}'),
            'cpf'          => $faker->cpf(false),
            'birth'        => Carbon::now()->subYears(21)->format('Y-m-d')
        ]);
        $this->user = $user;

        $GLOBALS['user'] = $user;
    }


    public function tearDown()
    {

        $this->schema()->drop('users');
        $this->schema()->drop('subscriptions');

    }


    /**
    *  Tests...
    */
    public function test_generic_user()
    {
        $this->assertEquals( 1, count($this->user) );
    }
    //
    // public function test_charge()
    // {
    //
    //     // Single charge
    //
    //     $charge = $this->user->charge( 500 );
    //     $this->assertEquals( 500, $charge['total'] );
    // }
    //
    // public function test_item_charge()
    // {
    //     // Charging a single intem
    //
    //     $item = [
    //         'name'   => 'Item 1',
    //         'amount' => 2,
    //         'value'  => 1000
    //     ];
    //     $charge = $this->user->charge( $item );
    //     $this->assertEquals( 2000, @$charge['total'] );
    // }
    //
    // public function test_multiple_items_charge()
    // {
    //
    //     $items = [
    //         [
    //             'name'   => 'Item 1',
    //             'amount' => 1,
    //             'value'  => 1000
    //         ],
    //         [
    //             'name'   => 'Item 2',
    //             'amount' => 2,
    //             'value'  => 2000
    //         ]
    //     ];
    //     $charge = $this->user->charge( $items );
    //     $this->assertEquals( 5000, @$charge['total'] );
    //
    // }
    //
    // public function test_charge_metadata()
    // {
    //
    //     // $faker = Faker\Factory::create();
    //     // $payee_code = $faker->regexify('[a-fA-F0-9]{32}');
    //
    //     $options = [];
    //     $options['shippings'] = [
    //         ['name'  => 'My Shipping', 'value' => 2000],
    //     ['name'  => 'Shipping to someone else', 'value' => 1000, /* 'payee_code' => $payee_code */ ]
    // ];
    // $options['metadata']    = [
    //     'custom_id'        => 'Product 001',
    //     'notification_url' => 'http://127.0.0.1/notification'
    //     ];
    //     $charge = $this->user->charge( 500, $options );
    //
    //     $this->assertEquals( 3500, @$charge['total'] );
    // }
    //
    //
    // public function test_pay_charge_by_billet()
    // {
    //
    //     # Paying a charge
    //     // 1. Billet
    //     $charge = $this->user->charge( 500 );
    //     $billet = $this->user->payCharge( $charge['charge_id'], 'billet');
    //
    //     $this->assertTrue( isset($billet['barcode']) && $billet['barcode'] !== NULL );
    //     $this->assertEquals( 500, @$billet['total'] );
    //     $this->assertEquals( 'waiting', @$billet['status'] );
    //
    //
    //     $charge = $this->user->charge( 500 );
    //     $options = [
    //         'expire_at'    => Carbon::now()->addWeeks(1)->format('Y-m-d'),
    //         'instructions' => [
    //             'Pay only with money',
    //             'Do not pay with gold'
    //         ]
    //     ];
    //     $billet = $this->user->payCharge( $charge['charge_id'], 'billet', $options);
    //
    //     $this->assertTrue( isset($billet['barcode']) && $billet['barcode'] !== NULL );
    //     $this->assertEquals( 500, @$billet['total'] );
    //     $this->assertEquals( 'waiting', @$billet['status'] );
    //
    // }
    //
    // // public function test_pay_charge_by_card()
    // // {
    // //         2. Card
    // //         $charge = $user->charge( 500 );
    // //
    // //         $payment_token = $this->get_payment_token();
    // //
    // //         $args['credit_card'] = [
    // //                 'installments'    => 1,
    // //                 'billing_address' => $user->billing_address,
    // //                 'payment_token'   => $payment_token
    // //             ];
    // //
    // //         $paybycard = $user->payCharge( $charge['charge_id'], 'card', $args);
    // //
    // //         $this->assertEquals( 'credit_card', $paybycard['payment'] );
    // //         $this->assertEquals( 500, $paybycard['total'] );
    // //         $this->assertEquals( 'waiting', $paybycard['status'] );
    // //         $this->assertEquals( $paybycard['charge_id'], $charge['charge_id'] );
    // // }
    //
    //
    // public function test_charge_details()
    // {
    //
    //     # Charges details
    //     $charge        = $this->user->charge( 500 );
    //
    //     $chargeDetails = $this->user->getCharge( $charge['charge_id'] );
    //
    //     $this->assertEquals( 'new', $chargeDetails['status'] );
    //
    //
    //     $billet        = $this->user->payCharge( $charge['charge_id'], 'billet');
    //
    //     $chargeDetails = $this->user->getCharge( $billet['charge_id'] );
    //
    //     $this->assertEquals( 'waiting', $chargeDetails['status'] );
    //
    //
    //     # Updating charges details
    //
    //     $metadata = [
    //         'custom_id'        => 'Product 001',
    //         'notification_url' => 'http://127.0.0.1/notification'
    //     ];
    //     $charge = $this->user->charge( 500, ['metadata' => $metadata] );
    //
    //     $this->assertEquals( 'new', $charge['status'] );
    //     $this->assertEquals( 'Product 001', $charge['custom_id'] );
    //     $this->assertEquals( 'http://127.0.0.1/notification', $charge['notification_url'] );
    //
    //     // Change metadata...
    //     $newMetadata = [
    //         'custom_id'        => 'Product 002-beta',
    //         'notification_url' => 'http://localhost/callback'
    //     ];
    //     $chargeDetails = $this->user->updateCharge( $charge['charge_id'], $newMetadata );
    //
    //     $this->assertEquals( 'new', $chargeDetails['status'] );
    //     $this->assertEquals( 'Product 002-beta', $chargeDetails['custom_id'] );
    //     $this->assertEquals( 'http://localhost/callback',  $chargeDetails['notification_url'] );
    //
    // }
    //
    // # Resending billet
    // public function test_resend_billet()
    // {
    //     $charge = $this->user->charge( 500 );
    //     $billet = $this->user->payCharge( $charge['charge_id'], 'billet');
    //
    //     $this->assertEquals( 'waiting', $billet['status'] );
    //
    //     # Resend...
    //     $newBillet = $this->user->resendBillet( $billet['charge_id'], 'tonetlds@gmail.com');
    //
    //     $this->assertTrue( $newBillet );
    // }
    //
    // # Adding information to charge's history
    // public function test_create_charge_history()
    // {
    //     $charge = $this->user->charge( 500 );
    //     $charge = $this->user->createChargeHistory( $charge['charge_id'], "Info to be added to charges history" );
    //
    //     $this->assertEquals( count( $charge['history'] ), 2 );
    //     $this->assertEquals( $charge['history'][0]['message'], "Cobrança criada" );
    //     $this->assertEquals( $charge['history'][1]['message'], "Info to be added to charges history" );
    // }
    //
    // # Canceling a charge
    // public function test_cancel_charge()
    // {
    //     $charge = $this->user->charge( 500 );
    //     $this->assertEquals( $charge['status'], 'new' );
    //
    //     $charge = $this->user->cancelCharge( $charge['charge_id'] );
    //     $this->assertEquals( $charge['status'], 'canceled' );
    // }
    //
    // # Canceling a charge
    // public function test_update_billet()
    // {
    //     $charge = $this->user->charge( 500 );
    //     $billet = $this->user->payCharge( $charge['charge_id'], 'billet');
    //
    //     $this->assertEquals( $billet['expire_at'], Carbon::now()->addWeeks(1)->format('Y-m-d') );
    //
    //     $updatedBillet = $this->user->updateBillet( $charge['charge_id'], '2017-01-01');
    //     $this->assertEquals( $updatedBillet['code'], 200 );
    // }

    /**
    * Schema Helpers.
    */
    protected function schema()
    {
        return $this->connection()->getSchemaBuilder();
    }

    protected function connection()
    {
        return Eloquent::getConnectionResolver()->connection();
    }

    public function get_payment_token()
    {
        // Payment Token needs to be generated from Gerencianet
        return false;
    }
}

class User extends Eloquent
{
    use Laravel\Cashier\Billable;
}
