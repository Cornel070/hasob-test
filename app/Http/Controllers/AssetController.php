<?php

namespace App\Http\Controllers;

use App\Http\Modules\Asset\Events\AssetCreated;
use App\Http\Requests\AssetCreateRequest;
use App\Http\Requests\UpdateAssetRequest;
use Illuminate\Http\Request;
use  Illuminate\Http\Testing\File;
use App\Asset;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = Asset::all();
        return response()->json(['res_type'=>'success', 'assets'=>$assets]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetCreateRequest $request)
    {
        $file = $request->picture;
        $photo_name = time().$file->getClientOriginalName();
        $request->picture->move(public_path('assets/images/upload/assets/'), $photo_name);

        $asset = Asset::create(
            array_merge(
                $request->all(), 
                ['picture_path'=> asset('assets/images/upload/assets/').'/'.$photo_name]
            )
        );

        //Trigger asset created event
        event( new AssetCreated($asset) );

        return response()->json(['asset'=>$asset]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAsset($id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            return response()->json(['res_type'=>'not found', 'message'=>'Asset not found'], 404);
        }
        return response()->json(['res_type'=>'success', 'asset'=>$asset]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssetRequest $request, $id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            return response()->json(['res_type'=>'not found', 'message'=>'Asset not found'],404);
        }

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $photo_name = time().$file->getClientOriginalName();
            $request->picture->move(public_path('assets/images/upload/assets/'), $photo_name);

            $asset->update(
                array_merge(
                    $request->all(), 
                    ['picture_path'=>asset('assets/images/upload/assets').'/'.$photo_name]
                )
            );
        }else{
            $asset->update($request->all());
        }

        return response()->json(['res_type'=>'success', 'message'=>'Asset updated', 'asset'=>$asset]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset = Asset::find($id);
        $asset->delete();

        return response()->json(['deleted'=>true]);
    }
}
