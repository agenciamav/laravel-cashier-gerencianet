<?php

use Carbon\Carbon;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Request;

class CashierTest extends PHPUnit_Framework_TestCase
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

        // Define the Gerencianet KEYS
        $this->options = [
            'client_id'       => getenv('GERENCIANET_CLIENT_ID'),
            'client_secret'   => getenv('GERENCIANET_CLIENT_SECRET'),
            'sandbox'         => getenv('GERENCIANET_SANDBOX')
        ];                    

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
            $table->integer('subscription_id');     // 1876
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


        // Create transactions table
        $this->schema()->create('charges', function ($table) {

            $table->increments('id');

            $table->integer('charge_id');     // 67477
            $table->integer('user_id');  
            $table->integer('total');  
            $table->string('status')->default("new");
            $table->string('custom_id')->nullable();
            $table->string('notification_url')->nullable();
           
            $table->timestamps();  

        });


        $faker = Faker\Factory::create();        

        $user = User::create([
                'email' => $faker->email,
                'name'  => $faker->name,
            ]);        
        $this->user = $user;

    }


    public function tearDown()
    {

        $this->schema()->drop('users');
        $this->schema()->drop('subscriptions');
        $this->schema()->drop('charges');

    }


    /**
     *  Tests...
     */
    public function test_generic_user()
    {        
        $this->assertEquals( 1, count($this->user) );        
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
}

class User extends Eloquent
{
    use Laravel\Cashier\Billable;
}