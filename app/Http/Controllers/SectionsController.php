<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = sections::with('classes')->get();
        // $sections = sections::paginate(10);
        if(count($sections) > 0){
            return response()->json([
                'status' =>200,
                'sections'=> $sections
            ], 200);
        }else{
            return response()->json([
                'status' =>404,
                'message'=> "No Record Found."
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:sections,title',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $sections = sections::create($request->all());
        return response()->json($sections, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sections = sections::find($id);

        if (!$sections) {
            return response()->json(['message' => 'Section not found'], 404);
        } else {        
            return response()->json([
                   'status'=> 200,
                    'sections'=>$sections], 200
                );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sections = sections::find($id);      
        return response()->json($sections, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:sections,title' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $sections = sections::find($id);
        if (!$sections) {
            return response()->json([
                'status' => 404,
                'message' => 'Section not found'
            ], 404);
        } else {
            $sections->update($request->all());
            return response()->json($sections, 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sections = sections::find($id);
        if (!$sections) {
            return response()->json([
                'status' => 404,
                'message' => 'Section not found'
            ], 404);
        }

        $sections->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Section deleted successfully'
        ], 200);
    }
}
