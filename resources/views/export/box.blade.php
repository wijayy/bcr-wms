<table border="1">
    <thead>
        <tr>
            <th rowspan="2">Box.No</th>
            <th rowspan="2">Total Box</th>
            <th rowspan="2">Supplier</th>
            <th rowspan="2">Material</th>
            <th rowspan="2">Code</th>
            <th rowspan="2">Description</th>
            <th colspan="3">Dimensi</th>
            <th>Volume</th>
            <th>GW</th>
            <th colspan="4">Qty</th>
            <th>Price</th>
            <th>Price</th>
            <th>Total</th>
            <th>Total</th>
        </tr>
        <tr>
            <th>P</th>
            <th>L</th>
            <th>T</th>
            <th>M3</th>
            <th>KGM</th>
            <th>PCS</th>
            <th>SET</th>
            <th>UNIT</th>
            <th>PRS</th>
            <th>IDR</th>
            <th>$</th>
            <th>IDR</th>
            <th>$</th>
        </tr>
    </thead>
    <tbody>
        @php
            $prevNoBox = null;
            $detailCount = 1;
            $gw = 0;
            $volume = 0;
            $totalIDR = 0;
            $totalUS = 0;
        @endphp
        @foreach ($job->box_detail as $item)
            @php
                if ($item->box->no_box != $prevNoBox) {
                    $detailCount = $item->box->box_detail->count();
                }
                $totalIDR += $item->stocks->goods->id_price * $item->amount;
                $totalUS += $item->stocks->goods->us_price * $item->amount;
            @endphp
            <tr>
                <td>{{ $item->box->no_box }}{{ $item->box->prefix }}</td>
                <td>{{ $item->box->no_box != $prevNoBox ? $item->box->count : 'Inside' }}</td>
                <td>{{ $item->stocks->goods->supplier->name }}</td>
                <td>{{ $item->stocks->goods->material }}</td>
                <td>{{ $item->stocks->goods->code }}</td>
                <td>{{ $item->amount > 0 ? $item->stocks->goods->desc : $item->stocks->name }}</td>
                @if ($item->box->no_box != $prevNoBox)
                    @php
                        $volume +=
                            ($item->box->height * $item->box->width * $item->box->length) /
                            ($item->box->count * 1000000);
                        // $gw += 1;
                    @endphp
                    <td rowspan="{{ $detailCount }}">{{ $item->box->length }}</td>
                    <td rowspan="{{ $detailCount }}">{{ $item->box->width }}</td>
                    <td rowspan="{{ $detailCount }}">{{ $item->box->height }}</td>
                    <td rowspan="{{ $detailCount }}">
                        {{ ($item->box->height * $item->box->width * $item->box->length) / ($item->box->count * 1000000) }}
                    </td>
                    <td rowspan="{{ $detailCount }}">
                        @php
                            $g = $item->box->weight;
                            foreach ($job->box_detail->where('box_id', $item->box_id) as $key => $value) {
                                $g +=
                                    $value->amount > 0
                                        ? $value->stocks->goods->weight * $item->amount
                                        : $value->stocks->weight;
                            }
                            $gw += $g;
                        @endphp
                        {{ $g }}
                    </td>
                @endif
                @if ($item->amount > 0)
                    <td>{{ $item->stocks->goods->unit == 'pcs' ? "$item->amount" : '' }}</td>
                    <td>{{ $item->stocks->goods->unit == 'set' ? "$item->amount" : '' }}</td>
                    <td>{{ $item->stocks->goods->unit == 'unit' ? "$item->amount" : '' }}</td>
                    <td>{{ $item->stocks->goods->unit == 'prs' ? "$item->amount" : '' }}</td>
                @else
                    <td colspan="4">Partof</td>
                @endif
                <td>{{ $item->amount > 0 ? $item->stocks->goods->id_price : '-' }}</td>
                <td>{{ $item->amount > 0 ? $item->stocks->goods->us_price : '-' }}</td>
                <td>{{ $item->amount > 0 ? $item->stocks->goods->id_price * $item->amount : '-' }}</td>
                <td>{{ $item->amount > 0 ? $item->stocks->goods->us_price * $item->amount : '-' }}</td>
            </tr>

            @php
                $prevNoBox = $item->box->no_box;
            @endphp
        @endforeach
        <tr>
            <td rowspan="2">Packages</td>
            <td rowspan="2">{{ $job->box->sum('count') }}</td>
            <td rowspan="2"></td>
            <td rowspan="2" colspan="6">Total</td>
            <td>{{ $volume }}</td>
            <td>{{ $gw }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $totalIDR }}</td>
            <td>{{ $totalUS }}</td>
        </tr>
        <tr>
            <td>CBM</td>
            <td>KGM</td>
            <td>PCS</td>
            <td>SET</td>
            <td>UNIT</td>
            <td>PRS</td>
            <td>IDR</td>
            <td>$</td>
            <td>IDR</td>
            <td>$</td>
        </tr>

    </tbody>
</table>
