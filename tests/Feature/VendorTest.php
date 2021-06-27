<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Vendor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Requests\VendorCreationRequest;
use App\Http\Requests\UpdateVendorRequest;

class VendorTest extends TestCase
{
    use WithFaker, WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSuccessfulVendorCreation()
    {
        $vendorData = [
            'name' => $this->faker->name,
            'category'   => $this->faker->word,
        ];

        $this->json('POST', 'api/vendor/create', $vendorData, ['Accept' => 'application/json'])
        ->assertStatus(200)
            ->assertJsonStructure([
                "vendor" => [
                    'name',
                    'category',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function testShowVendor()
    {
        $vendor = $this->createVendor();

        $this->json('GET', 'api/vendor/'.$vendor->id, ['Accept' => 'application/json'])
        ->assertStatus(200)
            ->assertJsonStructure([
                'res_type',
                'vendor' => [
                    'name',
                    'category',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function testUpdateAsset()
    {
        $vendor = $this->createVendor();
        $data = ['name'=> $this->faker->name, 'category'=> $this->faker->word];
        $this->json('POST', 'api/vendor/'.$vendor->id.'/update', $data, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('res_type','success')
        ->assertJsonPath('vendor.name', $data['name'])
        ->assertJsonPath('vendor.category',$data['category']);
    }

    public function testDeleteVendor()
    {
        $vendor = $this->createVendor();
        $this->json('DELETE', 'api/vendor/'.$vendor->id, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('deleted',true);
    }

    public function testGetAllVendors()
    {
        $this->json('GET', 'api/vendor', ['Accept' => 'application/json'])
        ->assertStatus(200);
    }

    private function createVendor()
    {
        return Vendor::create([
            'name' => $this->faker->name,
            'category'   => $this->faker->word,
        ]);
    }
}
