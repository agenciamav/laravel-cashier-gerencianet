<?php

use Carbon\Carbon;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Request;

/**
 * Basic evironment trait
 */
trait SetupTrait
{

    private $options;
    private $plans;

    private $user;

    public static function setUpBeforeClass()
    {
        if (file_exists(__DIR__.'/../../.env')) {
            $dotenv = new Dotenv\Dotenv(__DIR__.'/../../');
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
