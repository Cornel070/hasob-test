<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\AssetAssignment;
use App\Asset;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Requests\AssetAssignmentRequest as Request;
use App\Http\Requests\AssetAssignmentRequestUpdateRequest as UpdateRequest;
use Illuminate\Support\Str;
use App\User;

class AssignmentTest extends TestCase
{
    use WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSuccessfulAssignmentCreation()
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

        $this->json('POST', 'api/assgn/create', $assignmentData, ['Accept' => 'application/json'])
        ->assertStatus(200)
            ->assertJsonStructure([
                "assignment" => [
                    'asset_id',
                    'assignment_date',
                    'status',
                    'is_due',
                    'due_date',
                    'assigned_user_id',
                    'assigned_by',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function testShowAssignment()
    {
        $assignment = $this->createAssignment();

        $this->json('GET', 'api/assgn/'.$assignment->id, ['Accept' => 'application/json'])
        ->assertStatus(200)
            ->assertJsonStructure([
                'res_type',
                "assignment" => [
                    'asset_id',
                    'assignment_date',
                    'status',
                    'is_due',
                    'due_date',
                    'assigned_user_id',
                    'assigned_by',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function testUpdateAssignment()
    {
        $assignment = $this->createAssignment();
        $data = ['is_due'=> 'yes', 'status'=> 'expired'];
        $this->json('POST', 'api/assgn/'.$assignment->id.'/update', $data, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('res_type','success')
        ->assertJsonPath('assignment.is_due', $data['is_due'])
        ->assertJsonPath('assignment.status',$data['status']);
    }

    public function testDeleteAssignment()
    {
        $assignment = $this->createAssignment();
        $this->json('DELETE', 'api/assgn/'.$assignment->id, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('deleted',true);
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
