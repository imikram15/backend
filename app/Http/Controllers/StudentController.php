<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\students;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentController extends Controller
{

    public function getStudentsByClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|integer|exists:classes,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $classId = $request->input('class_id');
        $students = students::where('class_id', $classId)->get();

        if ($students->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'students' => $students
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No students found for this class."
            ], 404);
        }
    }
    
    public function index(){

        $students = students::with('classes')->paginate(10);

        if(count($students) > 0){
            return response()->json([
                'status' =>200,
                'students'=> $students
            ], 200);
        }else{
            return response()->json([
                'status' =>404,
                'message'=> "No Record Found."
            ], 404);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'roll_no' => 'required|integer|max:100|unique:students,roll_no',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'b_form' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'father_cnic' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'class_id' => 'required|string|max:255',
            'dob' => 'required|date',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|max:20',
            'blood_group' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            
            $fileName =  Str ::random(10).'.'.'png'; 
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $uploadPath = "images/profile";
                $file->move($uploadPath, $fileName);
            }
             $postObj = $request->all();
             $postObj['image'] = $fileName; 
    
            $student = students::create($postObj);
    
            if ($student) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Student created successfully.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to create Student.'
                ], 500);
            }
         }
    }

    public function show($id){
        $student = students::find($id);
    
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        } else {        
        return response()->json($student);
        }
       }

    public function edit($id){
        $student = students::find($id);     
        return response()->json($student);            
        }

    public function update(Request $request, $id)
    {
        $updateData = $request->except('image');

        $validator = Validator::make($updateData , [
            'roll_no' => 'required|integer|max:100|unique:students,roll_no,' . $id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'b_form' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'father_cnic' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'class_id' => 'required|string|max:255',
            'dob' => 'required|date',
            'email' => 'required|email|unique:students,email,' . $id,
            'password' => 'required|string|max:20',
            'blood_group' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            
        ]);    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }    
        $students = students::find($id);
        if (!$students) {
            return response()->json([
                'status' => 404,
                'message' => 'Student not found'
            ], 404);
        }
        else{
             $students->update($updateData);         
             return response()->json($students, 200);
        }
    
    }
    public function destroy($id)
{
    $students = students::find($id);

    if (!$students) {
        return response()->json(['message' => 'Student not found'], 404);
    }else{
        $students->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }   
}

}
