<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\VendorCreationRequest as Request;
use App\Http\Requests\UpdateVendorRequest as UpdateRequest;
use App\Http\Controllers\VendorController;
use Illuminate\Foundation\Testing\WithFaker;
use App\Vendor;

class VendorUnitTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_create_a_vendor()
    {
        $vendorData = [
            'name' => $this->faker->name,
            'category'   => $this->faker->word,
        ];
      
        $vendorCreator = new VendorController();
        $data = new Request($vendorData);
        $response = $vendorCreator->store($data);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($data['vendor']['name'], $vendorData['name']);
        $this->assertEquals($data['vendor']['category'], $vendorData['category']);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_show_the_vendor()
    {   
        $createdVendor = $this->createVendor();
        $vendorCreator = new VendorController;
        $showRes = $vendorCreator->showVendor($createdVendor->id);
        $showData = json_decode($showRes->getContent(), true);

        $this->assertEquals($showData['res_type'], 'success');
        $this->assertEquals($showData['vendor']['id'], $createdVendor->id);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_update_the_vendor()
    {
        $createdVendor = $this->createVendor();
        $vendorCreator = new VendorController;

        $update = ['name'=>'Josh King'];
        $updateRequest = new UpdateRequest($update);

        $updateRes = $vendorCreator->update($updateRequest, $createdVendor->id);
        $updatedData = json_decode($updateRes->getContent(), true);

        $this->assertEquals($createdVendor->id, $updatedData['vendor']['id']);
        $this->assertEquals($updatedData['vendor']['name'], $update['name']);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_delete_the_vendor()
    {
        $createdVendor = $this->createVendor();
        $vendorCreator = new VendorController;
        $delRes = $vendorCreator->destroy($createdVendor->id);
        $delData = json_decode($delRes->getContent(), true);

        $this->assertEquals($delData['deleted'], true);
    }

    private function createVendor()
    {
        return Vendor::create([
            'name' => $this->faker->name,
            'category'   => $this->faker->word,
        ]);
    }
}
