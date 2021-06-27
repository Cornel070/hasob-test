<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\AssetAssignmentRequest as Request;
use App\Http\Requests\AssetAssignmentRequestUpdateRequest as UpdateRequest;
use App\Http\Controllers\AssetAssignmentController;
use Illuminate\Foundation\Testing\WithFaker;
use App\AssetAssignment;
use Illuminate\Support\Str;
use App\User;
use App\Asset;

class AssignmentUnitTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_create_an_assignment()
    {
        $asset = $this->createAsset();
        $user = $this->createLoginUser(false);
        $assignmentData = [
            'asset_id' => $asset->id,
            'assignment_date' => '2021-02-21',
            'status' => 'moved',
            'is_due' => 'no',
            'due_date' => '2021-12-02',
            'assigned_user_id' => $user->id,
            'assigned_by' => $this->faker->name
        ];
      
        $assgnCreator = new AssetAssignmentController();
        $data = new Request($assignmentData);
        $response = $assgnCreator->store($data);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($data['assignment']['assigned_by'], $assignmentData['assigned_by']);
        $this->assertEquals($data['assignment']['assigned_user_id'], $assignmentData['assigned_user_id']);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_show_the_assignment()
    {   
        $createdAssgn = $this->createAssignment();
        $assgnCreator = new AssetAssignmentController;

        $showRes = $assgnCreator->showAssignment($createdAssgn->id);
        $showData = json_decode($showRes->getContent(), true);

        $this->assertEquals($showData['res_type'], 'success');
        $this->assertEquals($showData['assignment']['id'], $createdAssgn->id);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_update_the_assignment()
    {
        $createdAssgn = $this->createAssignment();
        $assgnCreator = new AssetAssignmentController;

        $update = ['due_date'=>'2022-05-21'];
        $updateRequest = new UpdateRequest($update);

        $updateRes = $assgnCreator->update($updateRequest, $createdAssgn->id);
        $updatedData = json_decode($updateRes->getContent(), true);

        $this->assertEquals($createdAssgn->id, $updatedData['assignment']['id']);
        $this->assertEquals($updatedData['assignment']['due_date'], $update['due_date']);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_delete_the_assignment()
    {
        $createdAssgn = $this->createAssignment();
        $assgnCreator = new AssetAssignmentController;

        $delRes = $assgnCreator->destroy($createdAssgn->id);
        $delData = json_decode($delRes->getContent(), true);

        $this->assertEquals($delData['deleted'], true);
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

    private function createAssignment()
    {
        $asset = $this->createAsset();
        $user = $this->createLoginUser(false);

        return AssetAssignment::create([
            'asset_id' => $asset->id,
            'assignment_date' => '2021-02-21',
            'status' => 'moved',
            'is_due' => 'no',
            'due_date' => '2021-12-02',
            'assigned_user_id' => $user->id,
            'assigned_by' => $this->faker->name
        ]);
    }

    private function createLoginUser($login = true)
    {
        $str = Str::random(3);

        $user = new User;
        $user->first_name = 'John';
        $user->last_name = 'Doe';
        $user->middle_name = 'Tan';
        $user->email = 'ekupnse16@gmail.com'.$str;
        $user->phone = '09088875666';
        $user->picture_url = 'http://hasob.test/assets/images/upload1624394551Me.JPG';
        $user->password = 'Godsplan22@';
        $user->save();

        if ($login) {
            auth()->login($user);
            return true;
        }
        return $user;
    }
}
