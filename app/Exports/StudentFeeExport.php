<?php

namespace App\Exports;

use App\Models\StudentFee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class StudentFeeExport implements FromCollection, WithStyles, WithMapping, WithHeadings
{
    protected $startDate;
    protected $endDate;
     protected $class_id;
     protected $status;
    public function __construct($startDate, $endDate, $class_id, $status)
    {
        $this->startDate = Carbon::parse($startDate)->startOfDay()->toDateTimeString();
        $this->endDate = Carbon::parse($endDate)->endOfDay()->toDateTimeString();
        $this->class_id = $class_id;
        $this->status =$status;
        
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {   
        // $query = StudentFee::query();

       return StudentFee::with([
            'class:id,title',
            'student:id,first_name,last_name',
        ])
        ->whereBetween('created_at', [$this->startDate, $this->endDate])  ->when($this->class_id != 0, function ($query) {
            $query->where('class_id', $this->class_id);
        })
        ->when($this->status != 'allstatus', function ($query) {
            $query->where('status', $this->status);
        })
        ->select('id','class_id','student_id','invoiceTitle','totalAmount','paidAmount','status','paymentMethod','created_at')->get();
    
    }
    public function styles(Worksheet $sheet) {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function map($studentFee):array{
        return [
          $studentFee->id,
          $studentFee->class ? $studentFee->class->title : 'N/A',
          $studentFee->student ? $studentFee->student->first_name . ' ' . $studentFee->student->last_name : 'N/A',
          $studentFee->invoiceTitle,
          $studentFee->totalAmount,
          $studentFee->paidAmount,
          $studentFee->status,
          $studentFee->paymentMethod,
          $studentFee->created_at->format('Y-m-d'),

        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Class',
            'Student Name',
            'Invoice Title',
            'Total Amount',
            'Paid Amount',
            'Status',
            'Payment Method',
            'Created At',
        ];
    }

    
    // ->where('class_id',$this->class_id)->where('status',$this->status)
    // $data = Employee::with([
    //     'designation:id,title',
    //     'department:id,title',
    //     'banks:id,account_number,swift_code,bank_name,account_title,employee_id,status',
    //     'salary:employee_id,salary_type,amount,id',
    //     'status:id,title'
    // ])->where('status_id',$this->active)
    //     ->select('employees.id','status_id', 'name_in_en', 'name_in_ar', 'email', 'phone', 'dob', 'designation_id', 'department_id', 'gender', 'address', 'joing_date', 'citizen_id','file_id')
    //     ->get();
    // $headerRow = ['Sr #','File Number','Employee Id','Name',"اسم", 'Address', 'Phone', 'Email', 'Gender',"Date Of Birth",'Joining Date',"Department",'Designation','Basic Salary',"Insurance",'Transport Charges','Additional Charges','Others','Total Salary','Bank Name','Account Number','IBAN Number','Account Title'];

    // $rows = collect([$headerRow]);


    // $students = StudentFee::all();
    // $headerRow = ['#','Name','Amount'];
    // $formatRows = collect([$headerRow]);
    // $formatRows->push($students);
    // return $formatRows;

      // $headerRow = ['#','Class','Name','Invoice Title','Total Amount','Paid Amount','Status','Payment Method','Created At'];
        // $formatRows = collect([$headerRow]);
        // $formatRows->push($students);
        // return $formatRows;
    
}
