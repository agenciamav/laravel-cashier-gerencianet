<?php

use Carbon\Carbon;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Request;
use Laravel\Cashier\GerencianetSubscription;

class SubscriptionTest extends PHPUnit_Framework_TestCase
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
        global $db, $user;
        $this->user = $user;

        // Basic Plans
        $GLOBALS['basicPlans'] = [
            [
                "name"     => "basic_monthly",
                "interval" => 1,
            ],
            [
                "name"     => "basic_bimonthly",
                "interval" => 2,
            ],
            [
                "name"     => "basic_quarterly",
                "interval" => 3,
            ],
            [
                "name"     => "basic_semiannual",
                "interval" => 6,
            ],
            [
                "name"     => "basic_yearly",
                "interval" => 12,
            ]
        ];

        // Pro plans
        $GLOBALS['proPlans'] = [
            [
                "name"     => "pro_monthly",
                "interval" => 1,
            ],
            [
                "name"     => "pro_bimonthly",
                "interval" => 2,
            ],
            [
                "name"     => "pro_quarterly",
                "interval" => 3,
            ],
            [
                "name"     => "pro_semiannual",
                "interval" => 6,
            ],
            [
                "name"     => "pro_yearly",
                "interval" => 12,
            ]
        ];


    }

    public function tearDown()
    {

        // $this->schema()->drop('users');
        // $this->schema()->drop('subscriptions');

    }


    /**
    *  Tests...
    */
    public function test_generic_user()
    {
        $this->assertEquals( 1, count( $this->user ) );
    }

    public function test_create_plans()
    {
        // DONE:0 createPlans
        global $basicPlans, $proPlans;

        $basic_plans = GerencianetSubscription::createPlan( $basicPlans );

        $this->assertCount( 5, $basic_plans );

        $pro_plans = GerencianetSubscription::createPlan( $proPlans );

        $this->assertCount( 5, $pro_plans );
    }

    public function test_list_plans()
    {
        // DONE:0 listPlans
        $plans = GerencianetSubscription::listPlans();

        $this->assertTrue( count( $plans ) >= 10 );

        $basicPlans         = GerencianetSubscription::listPlans( "basic_" );
        $basicPlans         = collect( $basicPlans )->pluck('name')->all();
        $basicPlansExpected = ["basic_monthly", "basic_bimonthly", "basic_quarterly", "basic_semiannual", "basic_yearly"];

        $this->assertEquals( count(array_intersect( $basicPlans, $basicPlansExpected)), count($basicPlans) );

        $proPlans         = GerencianetSubscription::listPlans( "pro_" );
        $proPlans         = collect( $proPlans )->pluck('name')->all();
        $proPlansExpected = ["pro_monthly", "pro_bimonthly", "pro_quarterly", "pro_semiannual", "pro_yearly"];

        $this->assertEquals( count(array_intersect( $proPlans, $proPlansExpected)), count($proPlans) );


    }

    // TODO:30 deletePlan
    // TODO:20 createSubscription
    // TODO:40 detailSubscription
    // TODO:70 updateSubscriptioMetadata
    // TODO:10 cancelSubscription
    // TODO:60 paySubscription

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
