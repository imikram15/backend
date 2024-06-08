<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Models\students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentFeeController extends Controller
{
    
    public function index(Request $request)
    {
        $classId = $request->input('class_id');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        $query = StudentFee::query();
    
        if ($classId > 0) {
            $query->where('class_id', $classId);
        }
    
        if ($status !== 'allstatus') {
            $query->where('status', $status);
        }
    
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
    
        $studentFees = $query->with(['class', 'student'])
            ->paginate($request->input('per_page', 10));
    
        return response()->json([
            'message' => 'Data fetched',
            'status' => 'true',
            'studentFee' => $studentFees,
        ], 200);
    }
    
    public function createBulkStudentFees(Request $request)
{
    $classId = $request->input('class_id');
    $invoiceTitle = $request->input('invoiceTitle');
    $totalAmount = $request->input('totalAmount');
    $paidAmount = $request->input('paidAmount');
    $status = $request->input('status');
    $paymentMethod = $request->input('paymentMethod');

    $students = students::where('class_id', $classId)->get();

    foreach ($students as $student) {
        $fee = new StudentFee();
        $fee->class_id = $classId;
        $fee->student_id = $student->id;
        $fee->invoiceTitle = $invoiceTitle;
        $fee->totalAmount = $totalAmount;
        $fee->paidAmount = $paidAmount;
        $fee->status = $status;
        $fee->paymentMethod = $paymentMethod;
        $fee->save();
    }

    return response()->json(['message' => 'Fees created successfully for all students in the class.'], 201);
}

 

    public function edit($id)
    {
        $studentFee = StudentFee::with(['class', 'student'])->find($id);

        if (!$studentFee) {
            return response()->json(['message' => 'Student fee not found'], 404);
        }

        return response()->json($studentFee, 200);
    }

    public function update(Request $request, $id)
    {
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:students,id',
            'invoiceTitle' => 'required|string|max:255',
            'totalAmount' => 'required|numeric',
            'paidAmount' => 'required|numeric',
            'status' => 'required|string',
            'paymentMethod' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $studentFee = StudentFee::find($id);

        if (!$studentFee) {
            return response()->json([
                'status' => 404,
                'message' => 'Student fee not found'
            ], 404);
        }

        $studentFee->update($updateData);

        return response()->json([
            'status' => 200,
            'message' => 'Student fee updated successfully.',
            'student_fee' => $studentFee
        ], 200);
    }

    public function destroy($id)
    {
        $studentFee = StudentFee::find($id);

        if (!$studentFee) {
            return response()->json(['message' => 'Student fee not found'], 404);
        }

        $studentFee->delete();

        return response()->json(['message' => 'Student fee deleted successfully'], 200);
    }

    public function getFeesByStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $studentId = $request->input('student_id');

        $studentFees = StudentFee::where('student_id', $studentId)
            ->with(['class', 'student'])->get();

        if ($studentFees->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'student_fees' => $studentFees
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No fees found for this student."
            ], 404);
        }
    }
}
