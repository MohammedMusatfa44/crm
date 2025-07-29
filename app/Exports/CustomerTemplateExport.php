<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerTemplateExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;
    protected $headings;

    public function __construct($headings, $data)
    {
        $this->headings = $headings;
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'رقم الحساب',
            'الاسم الكامل',
            'رقم الجوال',
            'البريد الإلكتروني',
            'التعليق',
            'الحالة',
            'سبب الشكوى',
            'الجنسية',
            'المدينة',
            'طريقة التواصل',
            'تواصل مع طرف آخر',
            'طرق الدفع',
            'تاريخ الريادة'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 