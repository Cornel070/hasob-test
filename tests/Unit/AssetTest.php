<?php

namespace Tests\Unit;

use App\Http\Controllers\AssetController;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Requests\AssetCreateRequest as Request;
use App\Http\Requests\UpdateAssetRequest as UpdateRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Asset;

class AssetTest extends TestCase
{
    use WithFaker;
    /**
     * @test
     * 
     * @return void
     */
    public function it_can_create_an_asset()
    {
        Storage::fake('images');
        $assetImg = UploadedFile::fake()->image('asset.jpg');
        $assetData = [
            'type' => $this->faker->word,
            'serial_number' => $this->faker->numerify('######'),
            'description'   => $this->faker->text(100),
            'fixed_movable' => 'fixed',
            'picture'       => $assetImg,
            'purchase_date' => $this->faker->dateTimeBetween($startDate = '-30 days', $endDate = '+30 days'),
            'start_use_date'=> '2021-06-12',
            'purchase_price'=> $this->faker->numberBetween($min = 1500, $max = 6000),
            'warranty_expiry_date' => $this->faker->dateTimeBetween($startDate = '+10 days', $endDate = '+400 days'),
            'degredation_in_years' => $this->faker->numerify('#'),
            'current_value_in_naira' => $this->faker->numberBetween($min = 1500, $max = 8000),
            'location'          => '20 somestreet, a city'
        ];

        $request = new Request($assetData);
      
        $assetCreator = new AssetController();
        $response = $assetCreator->store($request);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($data['asset']['type'], $assetData['type']);
        $this->assertEquals($data['asset']['serial_number'], $assetData['serial_number']);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_show_the_asset()
    {
        Storage::fake('images');
        $assetImg = UploadedFile::fake()->image('asset.jpg');
       $assetData = [
            'type' => $this->faker->word,
            'serial_number' => $this->faker->numerify('######'),
            'description'   => $this->faker->text(100),
            'fixed_movable' => 'fixed',
            'picture'       => $assetImg,
            'purchase_date' => $this->faker->dateTimeBetween($startDate = '-30 days', $endDate = '+30 days'),
            'start_use_date'=> '2021-06-12',
            'purchase_price'=> $this->faker->numberBetween($min = 1500, $max = 6000),
            'warranty_expiry_date' => $this->faker->dateTimeBetween($startDate = '+10 days', $endDate = '+400 days'),
            'degredation_in_years' => $this->faker->numerify('#'),
            'current_value_in_naira' => $this->faker->numberBetween($min = 1500, $max = 8000),
            'location'          => '20 somestreet, a city'
        ];

        $request = new Request($assetData);
      
        $assetCreator = new AssetController();
        $response = $assetCreator->store($request);
        $createData = json_decode($response->getContent(), true);

        $showRes = $assetCreator->showAsset($createData['asset']['id']);
        $showData = json_decode($showRes->getContent(), true);

        $this->assertEquals($showData['res_type'], 'success');
        $this->assertEquals($showData['asset']['id'], $createData['asset']['id']);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_update_the_asset()
    {
        Storage::fake('images');
        $assetImg = UploadedFile::fake()->image('asset.jpg');
       $assetData = [
            'type' => $this->faker->word,
            'serial_number' => $this->faker->numerify('######'),
            'description'   => $this->faker->text(100),
            'fixed_movable' => 'fixed',
            'picture'       => $assetImg,
            'purchase_date' => $this->faker->dateTimeBetween($startDate = '-30 days', $endDate = '+30 days'),
            'start_use_date'=> '2021-06-12',
            'purchase_price'=> $this->faker->numberBetween($min = 1500, $max = 6000),
            'warranty_expiry_date' => $this->faker->dateTimeBetween($startDate = '+10 days', $endDate = '+400 days'),
            'degredation_in_years' => $this->faker->numerify('#'),
            'current_value_in_naira' => $this->faker->numberBetween($min = 1500, $max = 8000),
            'location'          => '20 somestreet, a city'
        ];

        $request = new Request($assetData);
      
        $assetCreator = new AssetController();
        $response = $assetCreator->store($request);
        $createdData = json_decode($response->getContent(), true);

        $update = ['location'=>'Apo, Lokogoma, FCT Abuja'];
        $updateRequest = new UpdateRequest($update);
        $updateRes = $assetCreator->update($updateRequest, $createdData['asset']['id']);
        $updatedData = json_decode($updateRes->getContent(), true);

        $this->assertEquals($createdData['asset']['id'], $updatedData['asset']['id']);
        $this->assertEquals($updatedData['asset']['location'], $update['location']);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_delete_the_asset()
    {
        Storage::fake('images');
        $assetImg = UploadedFile::fake()->image('asset.jpg');
       $assetData = [
            'type' => $this->faker->word,
            'serial_number' => $this->faker->numerify('######'),
            'description'   => $this->faker->text(100),
            'fixed_movable' => 'fixed',
            'picture'       => $assetImg,
            'purchase_date' => $this->faker->dateTimeBetween($startDate = '-30 days', $endDate = '+30 days'),
            'start_use_date'=> '2021-06-12',
            'purchase_price'=> $this->faker->numberBetween($min = 1500, $max = 6000),
            'warranty_expiry_date' => $this->faker->dateTimeBetween($startDate = '+10 days', $endDate = '+400 days'),
            'degredation_in_years' => $this->faker->numerify('#'),
            'current_value_in_naira' => $this->faker->numberBetween($min = 1500, $max = 8000),
            'location'          => '20 somestreet, a city'
        ];

        $request = new Request($assetData);
      
        $assetCreator = new AssetController();
        $response = $assetCreator->store($request);
        $createdData = json_decode($response->getContent(), true);

        $delRes = $assetCreator->destroy($createdData['asset']['id']);
        $delData = json_decode($delRes->getContent(), true);

        $this->assertEquals($delData['deleted'], true);
    }
}
