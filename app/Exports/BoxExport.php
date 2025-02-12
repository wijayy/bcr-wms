<?php

namespace App\Exports;

use App\Models\Job;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BoxExport implements FromView {
    protected $job;

    function __construct(Job $job) {
        $this->job = $job;
    }
    /**
    //  * @return \Illuminate\Support\Collection
     */
    public function view(): View {
        return view('export.box', [
            "job" => $this->job,
        ]);
    }
}
