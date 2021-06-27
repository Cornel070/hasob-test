<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Modules\Vendor\Events\VendorCreated;
use App\Vendor;
use App\Http\Requests\VendorCreationRequest;
use App\Http\Requests\UpdateVendorRequest;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::all();
        return response()->json(['res_type'=>'success', 'vendors'=>$vendors]);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorCreationRequest $request)
    {
        $vendor = Vendor::create($request->all());
        //Trigger asset created event
        event( new VendorCreated($vendor) );

        return response()->json(['vendor'=>$vendor]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showVendor($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['res_type'=>'not found', 'message'=>'vendor not found'], 404);
        }
        return response()->json(['res_type'=>'success', 'vendor'=>$vendor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVendorRequest $request, $id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['res_type'=>'not found', 'message'=>'vendor not found'],404);
        }

        $vendor->update($request->all());

        return response()->json(['res_type'=>'success', 'vendor'=>$vendor]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['res_type'=>'not found', 'message'=>'vendor not found'],404);
        }

        $vendor->delete();

        return response()->json(['deleted'=>true]);
    }
}
