<?php

namespace App\Http\Controllers;

use App\Http\Modules\AssetAssignment\Events\AssetAssignmentCreated;
use App\AssetAssignment;
use App\Http\Requests\AssetAssignmentRequest as Request;
use App\Http\Requests\AssetAssignmentRequestUpdateRequest as UpdateRequest;

class AssetAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assignments = AssetAssignment::all();
        return response()->json(['res_type'=>'success', 'assignments'=>$assignments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assignment = AssetAssignment::create($request->all());
        //Trigger asset created event
        event( new AssetAssignmentCreated($assignment) );

        return response()->json(['assignment'=>$assignment]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAssignment($id)
    {
        $assignment = AssetAssignment::find($id);
        if (!$assignment) {
            return response()->json(['res_type'=>'not found', 'message'=>'assignment not found'], 404);
        }
        return response()->json(['res_type'=>'success', 'assignment'=>$assignment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $assignment = AssetAssignment::find($id);
        if (!$assignment) {
            return response()->json(['res_type'=>'not found', 'message'=>'Asset assignment not found'],404);
        }

        $assignment->update($request->all());

        return response()->json(['res_type'=>'success', 'message'=>'Asset assignment updated', 'assignment'=>$assignment]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $assignment = AssetAssignment::find($id);
        if (!$assignment) {
            return response()->json(['res_type'=>'not found', 'message'=>'Asset assignment not found'],404);
        }

        $assignment->delete();
        return response()->json(['deleted'=>true]);
    }
}
