<?php

namespace App\Http\Controllers;

use App\Models\school_class;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolClassController extends Controller
{
    public function index()
    {
        $school_class = school_class::all();
        if(count($school_class) > 0){
            return response()->json([
                'status' =>200,
                'school_class'=> $school_class
            ], 200);
        }else{
            return response()->json([
                'status' =>404,
                'message'=> "No Record Found."
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:school_class',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $school_class = school_class::create($request->all());
        return response()->json($school_class, 201);
    }

    public function show($id)
    {
        $school_class = school_class::find($id);

        if (!$school_class) {
            return response()->json(['message' => 'Class not found'], 404);
        } else {        
            return response()->json([
                   'status'=> 200,
                    'school_class'=>$school_class], 200
                );
        }
    }

    public function edit($id){
        $school_class = school_class::find($id);      
        return response()->json($school_class, 200);
        
       }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:school_class,title,' . $id,
          ]);
      
          if ($validator->fails()) {
              return response()->json([
                  'status' => 422,
                  'errors' => $validator->messages()
              ], 422);
          }
      
              $school_class = school_class::find($id);     
          if (!$school_class) {
              return response()->json([
                  'status' => 404,
                  'message' => 'Class not found'
              ], 404);
          }
      
          $school_class->update($request->all());
              return response()->json($school_class, 200);
    }

    public function destroy($id)
    {
        $school_class = school_class::find($id);
        if (!$school_class ) {
            return response()->json([
                'status' => 404,
                'message' => 'Class not found'
            ], 404);
        }
    
       $school_class ->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Class deleted successfully'
        ], 200);
    }
    
}
