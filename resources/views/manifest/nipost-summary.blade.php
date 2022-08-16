@section('title')
SpeedafUtility - Manifest_{{$manifest->seal_number}}_{{$manifest->id}}_{{\Carbon\Carbon::now()->format('ymd hms')}}
@endsection
@extends('layouts.app')


@section('content')
<div class="col-12 text-right mb-4">
    <a href="{{ url()->previous() }}" class="btn btn-default"  ><i class="fas fa-backward"></i>Back</a>
    <a href="#" class="btn btn-info pull-right" onclick="printContent('printableArea')"><i class="fa fa-print"></i>Print Manifest</a>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 padding">
    <div class="card" id="printableArea">
        <div class="card-header p-4">
            <a class="pt-2 d-inline-block" href="index.html" data-abc="true"> <img src=" {{ asset('img/speedaf_logo.png') }}"  alt="speedaf_logo"></a>
            <div class="float-left">
                <h3 class="mb-0 display-3">Manifest</h3>
                {{-- <h3 class="mb-0 display-3">         @if($manifest->status === \App\Enums\ManifestStatus::IN_TRANSIT)
                    <span class="badge badge-default">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::IN_TRANSIT]}}<span>
                    @elseif ($manifest->status === \App\Enums\ManifestStatus::ACKNOWLEDGED)
                    <span class="badge badge-success">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::ACKNOWLEDGED]}}</span>
                    @elseif($manifest->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED)
                    <span class="badge badge-primary">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED]}}</span>
                    @endif
                </h3> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-6">
                    {{-- <div><b>Seal Number:</b> {{$manifest->seal_number}}</div> --}}
                    <div class="mb-2">
                    <b>Route:</b>
                    <span class="text-dark mb-1">{{$manifest->scan_site->name}}</span>
                    <span class="mb-3">====></span>
                    <span class="text-dark mb-1">{{$manifest->next_site->name}}</span>
                </div>
                    <div class="mb-2"><b>Manifest Id:</b> {{$manifest->id}}</div>
                    <div class="mb-2"><b>Dispatched At:</b> {{optional($manifest->created_at)->format('Y-m-d , g:i A') }}</div>
                    <div class="mb-2"><b>Dispatched By:</b> {{$manifest->created_by_user->name}}</div>
                    <div class="mb-2"><b>Email:</b> {{$manifest->created_by_user->email}}</div>
                    <div class="mb-2"><b>Phone:</b> {{$manifest->created_by_user->phone_number}}</div>
                </div>
                <div class="col-sm-6 ">
                    <div class="mb-2"><b>Seal Number:</b> {{$manifest->seal_number}}</div>
                    <div class="mb-2"><b>Shipment type:</b> {{ config('custom.shipment_type')[(int)$manifest->shipment_type]}}</div>
                    <div class="mb-2"><b>Transport type:</b> {{ config('custom.transport_type')[(int)$manifest->transport_type_id]}}</div>
                    <div class="mb-2"><b>Driver's Name:</b> {{$manifest->driver_name}}</div>
                    <div class="mb-2"><b>Driver's Phone:</b> {{$manifest->driver_phonenumber}}</div>

                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <div class="mb-2"><b>REMARK:</b> {{$manifest->remark}}</div>
                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">

                    <b>Total Waybills: </b> <span class="text-dark mb-1">{{$manifest->waybills->count()}}</span><br>
                    {{-- <b>Total Acknowledged: </b><span class="text-dark mb-1">{{$manifest->waybills->where('status', \App\Enums\ManifestStatus::ACKNOWLEDGED)->count()}}</span><br>
                    @if ($manifest->status === \App\Enums\ManifestStatus::ACKNOWLEDGED || $manifest->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED)
                    <b>Total Missing: </b><span class="text-dark mb-1">{{$manifest->waybills->where('status', \App\Enums\ManifestStatus::IN_TRANSIT)->count()}}</span><br>
                    @endif --}}

                </div>

            </div>
            @php

            $waybills_having_weight_1_to_20_gram = [];
            $waybills_having_wieght_21_to_40_gram = [];
            $waybills_having_wieght_41_to_60_gram = [];
            $waybills_having_weight_61_to_80_gram = [];
            $waybills_having_weight_81_100_gram = [];
            $waybills_having_weight_101_to_120_gram = [];
            $waybills_having_weight_121_to_140_gram = [];
            $waybills_having_weight_141_to_160_gram = [];
            $waybills_having_other_weights = [];

            $count_of_weight_1_to_20_gram = 0;
            $count_of_wieght_21_to_40_gram =0;
            $count_of_wieght_41_to_60_gram = 0;
            $count_of_weight_61_to_80_gram = 0;
            $count_of_weight_81_to_100_gram = 0;
            $count_of_weight_101_to_120_gram = 0;
            $count_of_weight_121_to_140_gram = 0;
            $count_of_weight_141_to_160_gram = 0;
            $count_of_other_weights = 0;

                $total_arrival_weight = 0;
                $total_weight_in_gram = 0;
                foreach($manifest->waybills as  $waybill)
                {
                    $total_arrival_weight += $waybill->departure_weight;
                    $weight_in_gram = $waybill->weight * 1000;
                    $total_weight_in_gram +=$weight_in_gram;


            if($weight_in_gram >= 1 && $weight_in_gram <= 20)
            {
                ++$count_of_weight_1_to_20_gram;
            }

            if($weight_in_gram >= 21 && $weight_in_gram <= 40)
            {
                ++$count_of_wieght_21_to_40_gram;
            }

            if($weight_in_gram >= 41 && $weight_in_gram <= 60)
            {
                ++$count_of_wieght_41_to_60_gram;
            }

            if($weight_in_gram >= 61 && $weight_in_gram <= 80)
            {
                ++$count_of_weight_61_to_80_gram;
            }

            if($weight_in_gram >= 81 && $weight_in_gram <= 100)
            {
                ++$count_of_weight_81_to_100_gram;
            }

            if($weight_in_gram >= 101 && $weight_in_gram <= 120)
            {
                ++$count_of_weight_101_to_120_gram;
            }

            if($weight_in_gram >= 121 && $weight_in_gram <= 140)
            {
                ++$count_of_weight_121_to_140_gram;
            }

            if($weight_in_gram >= 141 && $weight_in_gram <= 160)
            {
                ++$count_of_weight_141_to_160_gram;
            }
            else
            {
                ++$count_of_other_weights;
            }

                }
            @endphp
            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>WEIGHT(GRAM)</th>
                            <th>QUANTITY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1 - 20</td>
                            <td>{{$count_of_weight_1_to_20_gram}}</td>
                        </tr>
                        <tr>
                            <td>21 - 40</td>
                            <td>{{$count_of_wieght_21_to_40_gram}}</td>
                        </tr>
                        <tr>
                            <td>41 - 60</td>
                            <td>{{$count_of_wieght_41_to_60_gram}}</td>
                        </tr>
                        <tr>
                            <td>61 - 80</td>
                            <td>{{$count_of_weight_61_to_80_gram}}</td>
                        </tr>
                        <tr>
                            <td>81 - 100</td>
                            <td>{{$count_of_weight_81_to_100_gram}}</td>
                        </tr>
                        <tr>
                            <td>101 - 120</td>
                            <td>{{$count_of_weight_101_to_120_gram}}</td>
                        </tr>
                        <tr>
                            <td>121 - 140</td>
                            <td>{{$count_of_weight_121_to_140_gram}}</td>
                        </tr>
                        <tr>
                            <td>141 - 160</td>
                            <td>{{$count_of_weight_141_to_160_gram}}</td>
                        </tr>
                        <tr>
                            <td>Over 160</td>
                            <td>{{$count_of_other_weights}}</td>
                        </tr>
                        {{-- <tr>
                            <td></td>
                            <td>{{$total_weight_in_gram}}</td>
                        </tr> --}}
                        <tr><td colspan="2"><b class="text-darker">Total  Weight:</b> {{$total_weight_in_gram}} g</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <p class="mb-0">k9ex - An Initiative of SpeedafNg IT Department @ 2021</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

    function printContent(el) {
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(el).innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
    }
</script>
@endpush
