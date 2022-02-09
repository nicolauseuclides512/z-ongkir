<?php

namespace Tests\Unit;

use App\Models\AssetCountry;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CountryTest extends TestCase
{

    use DatabaseMigrations;

    private $country;

    protected function setUp()
    {
        parent::setUp();

        $this->country = $country = factory(AssetCountry::class)->create([
            "code" => "ID",
            'name' => 'Indonesia',
            'status' => true
        ]);
    }

    /**
     * @test
     */
    public function it_can_create_new_country_return_match_value()
    {
        $this->assertEquals('ID', $this->country->code);
        $this->assertEquals('Indonesia', $this->country->name);
        $this->assertEquals(true, $this->country->status);
    }

    public function it_should_return_a_country_list_with_match_value()
    {



    }

}
