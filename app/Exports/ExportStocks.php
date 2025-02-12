<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportStocks implements FromCollection, WithHeadings, WithDrawings, WithEvents {

    private $month;
    private $year;

    private $stocks;

    public function __construct($month, $year) {
        $this->month = $month;
        $this->year = $year;

        $this->stocks = Stock::whereMonth('created_at', '=', $this->month)
            ->whereYear('created_at', '=', $this->year)
            ->get();
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        $data = collect();
        foreach ($this->stocks as $index => $item) {
            $data->push([
                'No'       => $index + 1 ?? '',
                'Date'       => $item->created_at->format("Y-m-d:H:i"),
                'Photo'       => '',
                'Shipment'      => $item->goods->shipment->name ?? '',
                'Supplier'      => $item->goods->supplier->name ?? '',
                'Description of Goods'      => $item->goods->desc ?? '',
                'Material' => $item->goods->material,
                'Code' => $item->goods->code,
                'Type' => $item->type,
                'Amount' => $item->amount,
                'Stock' => $item->stock,
                'Description' => $item->desc,
                'Note' => ''
            ]);
        }

        return $data;
    }

    public function headings(): array {
        return ['No', 'Date', 'Photo', 'Shipment', 'Supplier', 'Description of Goods', 'Material', 'Code', "Type", 'Amount', 'Stock', 'Description', "Note"];
    }

    public function drawings() {
        $drawings = [];

        foreach ($this->stocks as $index => $item) {
            if ($item->goods->image) {
                $drawing = new Drawing();
                $drawing->setName('Goods Image');
                $drawing->setDescription('Image of The Goods');
                $drawing->setPath(public_path('storage/' . $item->goods->image)); // Path ke gambar
                $drawing->setHeight(60); // Tinggi gambar
                $drawing->setCoordinates('C' . ($index + 2));
                $drawing->setResizeProportional(true);  // Kolom B untuk gambar
                $drawings[] = $drawing;
            }
            if ($item->note) {
                $drawing = new Drawing();
                $drawing->setName('Note');
                $drawing->setDescription('Note');
                $drawing->setPath(public_path('storage/' . $item->note)); // Path gambar tambahan
                $drawing->setCoordinates('M' . ($index + 2)); // Kolom C untuk gambar 2
                $drawing->setHeight(50);
                $drawing->setResizeProportional(true);
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $rowCount = $this->stocks->count() + 1; // Jumlah baris termasuk header

                for ($row = 2; $row <= $rowCount; $row++) {
                    $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(60); // Set tinggi row 60
                }
            },
        ];
    }
}