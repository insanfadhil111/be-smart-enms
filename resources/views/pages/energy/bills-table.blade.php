<table class="table table-striped table-hover">
    <tr>
        <th class="text-center" width="15%">Bulan</th>
        <th class="text-center" width="20%">Tahun</th>
        <th class="text-center" width="20%">Energi (KWH)</th>
        <th class="text-center" width="10%"></th>
        <th class="text-center" width="15%">Total</th>
        <th class="text-center" width="20%">Than Last Month</th>
    </tr>
    @foreach ($paginatedData as $item)
    <tr>
        <td class="text-start">{{$item['bulan']}}</td>
        <td class="text-center">{{$item['tahun']}}</td>
        <td class="text-center">{{ $item['total'] }}</td>
        <td class="text-end">Rp </td>
        <td class="text-end">{{$item['bill']}}</td>
        @if($item['diffStatus']=='naik')
        <td class="text-center text-sm mb-0"><i class="fa-solid fa-sort-up text-danger "></i><span class="mx-2">+
                {{$item['diff'] }} %</span></td>
        @elseif ($item['diffStatus']=='turun')
        <td class="text-center text-sm my-0 mx-2"><i class="fa-solid fa-sort-down text-success "></i>
            <span class="mx-2"> {{$item['diff'] }} %</span>
        </td>
        @else()
        <td class="text-center text-sm mb-0"></td>
        @endif
    </tr>
    @endforeach
</table>
<!-- Add pagination links -->
<div>
    {{ $paginatedData->links() }}
</div>