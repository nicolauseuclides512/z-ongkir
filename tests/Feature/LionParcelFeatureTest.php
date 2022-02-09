<?php

namespace Tests\Feature;

use Tests\TestCase;

class LionParcelFeatureTest extends TestCase
{

    /**
     * @test
     */
    public function itCanGetShippingCost()
    {
        $requestParams = [
            'origin' => "Jakarta (CGK)",
            'destination' => "Medan (KNO)",
            'weight' => 1,
        ];

        $this->json("POST", "/test")
            ->assertJson([
                'created' => true
            ]);


        // Here we are saying the \Request facade should expect the all method to be called and that all method should
//        // return some pre-defined things which we will use in our asserts.
//        \Request::shouldReceive('all')->once()->andReturn($requestParams);
//
//
//        // Here we are just using Laravel's IoC container to instantiate your controller.  Change YourController to whatever
//        // your controller is named
//        $class = App::make('LionParcelController');
//
//        // Getting results of function so we can test that it has some properties which were supposed to have been set.
//        $return = $class->getInput();
//
//        // Again change this to the actual name of your controller.
//        $this->assertInstanceOf('LionParcelController', $return);
    }


}
