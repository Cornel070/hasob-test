<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AssetCreateRequest as Request;
use App\Http\Requests\UpdateAssetRequest as UpdateRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Asset;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use WithFaker, WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSuccessfulAssetCreation()
    {
        Storage::fake('images');
        $assetImg = UploadedFile::fake()->image('asset.jpg');
        $assetData = [
            'type' => $this->faker->word,
            'serial_number' => $this->faker->numerify('######'),
            'description'   => $this->faker->text(100),
            'fixed_movable' => 'fixed',
            'picture'       => $assetImg,
            'purchase_date' => '2020-02-22',
            'start_use_date'=> '2021-06-12',
            'purchase_price'=> 10000,
            'warranty_expiry_date' => '2023-03-01',
            'degredation_in_years' => $this->faker->numerify('#'),
            'current_value_in_naira' => $this->faker->numberBetween($min = 1500, $max = 8000),
            'location'          => '20 Somestreet, a city'
        ];

        $this->json('POST', 'api/asset/create', $assetData, ['Accept' => 'application/json'])
        ->assertStatus(200)
            ->assertJsonStructure([
                "asset" => [
                    'type',
                    'serial_number',
                    'description',
                    'fixed_movable',
                    'purchase_date',
                    'start_use_date',
                    'purchase_price',
                    'warranty_expiry_date',
                    'degredation_in_years',
                    'current_value_in_naira',
                    'location',
                    'picture_path',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function testShowAsset()
    {
        $asset = $this->createAsset();

        $this->json('GET', 'api/asset/'.$asset->id, ['Accept' => 'application/json'])
        ->assertStatus(200)
            ->assertJsonStructure([
                'res_type',
                "asset" => [
                    'type',
                    'serial_number',
                    'description',
                    'fixed_movable',
                    'purchase_date',
                    'start_use_date',
                    'purchase_price',
                    'warranty_expiry_date',
                    'degredation_in_years',
                    'current_value_in_naira',
                    'location',
                    'picture_path',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function testUpdateAsset()
    {
        $asset = $this->createAsset();
        $data = ['type'=> 'Test Asset', 'serial_number'=> 0112];
        // $updateData = new UpdateRequest($data);
        $this->json('POST', 'api/asset/'.$asset->id.'/update', $data, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('res_type','success')
        ->assertJsonPath('asset.serial_number', $data['serial_number'])
        ->assertJsonPath('asset.type',$data['type']);
    }

    public function testDeleteAsset()
    {
        $asset = $this->createAsset();
        $this->json('DELETE', 'api/asset/'.$asset->id, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('deleted',true);
    }

    public function testGetAllAssets()
    {
        $this->json('GET', 'api/asset', ['Accept' => 'application/json'])
        ->assertStatus(200);
    }

    private function createAsset()
    {
        $assetData = [
            'type' => $this->faker->word,
            'serial_number' => $this->faker->numerify('######'),
            'description'   => $this->faker->text(100),
            'fixed_movable' => 'fixed',
            'picture_path'       => 'http://hasob.test/assets/images/upload/assets/1624782836asset.JPG',
            'purchase_date' => '2020-02-22',
            'start_use_date'=> '2021-06-12',
            'purchase_price'=> 10000,
            'warranty_expiry_date' => '2023-03-01',
            'degredation_in_years' => $this->faker->numerify('#'),
            'current_value_in_naira' => $this->faker->numberBetween($min = 1500, $max = 8000),
            'location'          => '20 Somestreet, a city'
        ];

        return Asset::create($assetData);
    }
}
