<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithEvents
{
    use RegistersEventListeners;

    protected $classId;
    protected $month;
    protected $year;
    protected $className;

    public function __construct($classId, $month, $year)
    {
        $this->classId = $classId;
        $this->month = $month;
        $this->year = $year;
        $this->className = DB::table('classes')->where('id', $classId)->value('title');
    }

    public function collection()
    {
        return DB::table('students')
            ->leftJoin('attendances', 'students.id', '=', 'attendances.student_id')
            ->leftJoin('attendance_status', 'attendances.status_id', '=', 'attendance_status.id')
            ->where('students.class_id', $this->classId)
            ->whereYear('attendances.date', $this->year)
            ->whereMonth('attendances.date', $this->month)
            ->select(
                'students.first_name',
                'students.last_name',
                'attendances.date',
                'attendance_status.title as attendance_status',
                'attendances.created_at'
            )
            ->orderBy('students.id')
            ->orderBy('attendances.date')
            ->get();
    }
    public function headings(): array
    {
        return [];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->afterSheet($event);
            },
        ];
    }

    public function afterSheet(AfterSheet $event)
    {
        /** @var Worksheet $sheet */
        $sheet = $event->sheet->getDelegate();
        $monthYear = date('F Y', mktime(0, 0, 0, $this->month, 1, $this->year));

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'Attendance Report of ' . $monthYear . ' for ' . $this->className);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $headings = [
            'First Name',
            'Last Name',
            'Date',
            'Status',
            'Marked at',
        ];

        foreach ($headings as $index => $heading) {
            $column = chr(65 + $index);
            $sheet->setCellValue("{$column}2", $heading);
        }

        $sheet->getStyle('A2:F2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }

}