<?php

namespace App\Exports;

use App\Models\Goods;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportGoods implements FromCollection, WithHeadings, WithDrawings, WithEvents {
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        // Ambil data dari semua tabel
        $goods = Goods::all();

        // Gabungkan data ke dalam format yang diminta
        $data = collect();

        foreach ($goods as $index => $good) {
            $data->push([
                'No'       => $index + 1 ?? '',
                'Photo'       => '',
                'Shipment'      => $good->shipment->name ?? '',
                'Supplier'      => $good->supplier->name ?? '',
                'Description of Goods'      => $good->desc ?? '',
                'Material' => $good->material,
                'Code' => $good->code,
                'Qty'     => $good->stock ?? 0,
                'Unit'     => $good->unit,
                'Price USD'     => $good->us_price,
                'Amount USD'     => $good->us_price * $good->stock ?? 0,
                'Price IDR'     => $good->id_price,
                'Amount IDR'     => $good->id_price * $good->stock ?? 0,

            ]);
        }

        return $data;
    }

    public function headings(): array {
        return ['No', 'Photo', 'Shipment', 'Supplier', 'Description of Goods', 'Material', 'Code', 'Qty', 'Unit', 'Price USD', 'Amount USD', 'Price IDR', 'Amount IDR'];
    }

    public function drawings() {
        $goods = Goods::all(['image']);
        $drawings = [];

        foreach ($goods as $index => $good) {
            if ($good->image) {
                $drawing = new Drawing();
                $drawing->setName('');
                $drawing->setDescription('');
                $drawing->setPath(public_path('storage/' . $good->image)); // Path ke gambar
                $drawing->setHeight(60); // Tinggi gambar
                $drawing->setCoordinates('B' . ($index + 2));
                $drawing->setResizeProportional(true);  // Kolom B untuk gambar
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $rowCount = Goods::count() + 1; // Jumlah baris termasuk header

                for ($row = 2; $row <= $rowCount; $row++) {
                    $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(60); // Set tinggi row 60
                }
            },
        ];
    }
}