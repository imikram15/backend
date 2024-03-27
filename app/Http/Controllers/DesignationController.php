<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designation = Designation::all();
        if(count($designation) > 0){
            return response()->json([
                'status' =>200,
                'designation'=> $designation
            ], 200);
        }else{
            return response()->json([
                'status' =>404,
                'message'=> "No Record Found."
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
       
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:designations',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $designation = Designation::create($request->all());
        return response()->json($designation, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $designation = Designation::find($id);

        if (!$designation) {
            return response()->json(['message' => 'Designation not found'], 404);
        } else {        
            return response()->json([
                   'status'=> 200,
                    'designation'=>$designation], 200
                );
        }
    }
    public function edit($id){
        $designation = Designation::find($id);      
        return response()->json($designation,200);
       }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
   {
     $validator = Validator::make($request->all(), [
      'title' => 'required|string|max:255|unique:designations,title,' . $id,
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'errors' => $validator->messages()
        ], 422);
    }

        $designation = Designation::find($id);      
    if (!$designation) {
        return response()->json([
            'status' => 404,
            'message' => 'Designation not found'
        ], 404);
    }

        $designation->update($request->all());
        return response()->json($designation, 200);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

    $designation = Designation::find($id);
    if (!$designation) {
        return response()->json([
            'status' => 404,
            'message' => 'Designation not found'
        ], 404);
    }

    $designation->delete();
    return response()->json([
        'status' => 200,
        'message' => 'Designation deleted successfully'
    ], 200);
}

}
