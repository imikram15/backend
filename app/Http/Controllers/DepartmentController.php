<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department = Departments::all();
        if(count($department) > 0){
            return response()->json([
                'status' =>200,
                'department'=> $department
            ], 200);
        }else{
            return response()->json([
                'status' =>404,
                'message'=> "No Records Found."
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
            'title' => 'required|string|max:255|unique:departments',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $department = Departments::create($request->all());
        return response()->json($department, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Departments::find($id);

        if (!$department ) {
            return response()->json(['message' => 'Department not found'], 404);
        } else {        
            return response()->json([
                   'status'=> 200,
                    'department'=>$department ], 200
                );
        }
    }

    public function edit($id){
        $department = Departments::find($id);      
        return response()->json($department ,200);
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
            'title' => 'required|string|max:255|unique:departments,title,' . $id,
          ]);
      
          if ($validator->fails()) {
              return response()->json([
                  'status' => 422,
                  'errors' => $validator->messages()
              ], 422);
          }
      
              $department = Departments::find($id);      
          if (!$department) {
              return response()->json([
                  'status' => 404,
                  'message' => 'Department not found'
              ], 404);
          }
      
          $department->update($request->all());
              return response()->json($department, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Departments::find($id);
        if (!$department ) {
            return response()->json([
                'status' => 404,
                'message' => 'Department not found'
            ], 404);
        }
    
       $department ->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Department deleted successfully'
        ], 200);
    }
}
