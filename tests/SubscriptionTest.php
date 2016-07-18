<?php

use Laravel\Cashier\Subscription;
use Laravel\Cashier\GerencianetSubscription;

class SubscriptionTest extends PHPUnit_Framework_TestCase
{

    use SetupTrait;

    /**
    *  Tests...
    */
    public function test_generic_user()
    {
        $this->assertEquals( 1, count( $this->user ) );
    }

    /**
     * Test plan creation
     */
    public function test_create_plans()
    {
        // DONE:0 createPlans
        // Basic Plans
        $basicPlans = [
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
        $proPlans = [
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

        $basic_plans = GerencianetSubscription::createPlan( $basicPlans );

        $this->assertCount( 5, $basic_plans );

        $pro_plans = GerencianetSubscription::createPlan( $proPlans );

        $this->assertCount( 5, $pro_plans );
    }

    /**
     *  Test list plans
     */
    public function test_list_plans()
    {
        // DONE:10 listPlans
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

    /**
     * Tests plan exclusion
     */
    public function teste_delete_plan()
    {
        // DONE:20 deletePlan
        $plan = [
            "name" => "plan_for_delete",
        ];
        $plan = GerencianetSubscription::createPlan( $plan );

        $this->assertTrue( !isset( $plan['error'] ) );
        $this->assertTrue( isset( $plan['plan_id'] ) );
        $this->assertEquals( "plan_for_delete", $plan['name'] );

        $deletePlan = GerencianetSubscription::deletePlan( $plan['plan_id'] );

        $this->assertTrue( $deletePlan );

    }

    public function test_create_subscription()
    {
        // DOING:0 createSubscription
        $user = $this->user;

        $items = [
            [
                'name'   => 'Product 1',
                'amount' => 1,
                'value'  => 1000
            ],
            [
                'name'   => 'Product 2',
                'amount' => 2,
                'value'  => 2000
            ]
        ];

        $subscription = $user->newSubscription('basic_monthly', $items);
        $this->assertInstanceOf( 'Laravel\Cashier\Subscription', $subscription );


    }
    // TODO:20 detailSubscription
    // TODO:30 updateSubscriptioMetadata
    // TODO:40 cancelSubscription
    // TODO:50 paySubscription

}
