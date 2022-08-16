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

                </div>

            </div>

            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>SEND COMPANY</th>
                            <th>WAYBILL</th>
                            <th>QUANTITY</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $count_of_sub_waybills_count = 0;
                        // $total_weight_in_g = 0;
                    @endphp

                    @foreach ($manifest->waybills->where('main_id',null)->where('quantity', '>', 1) as  $waybill)
                        @php
                            $count_of_sub_waybills_count = $waybill->where('main_id', $waybill->id)->count();
                            if($count_of_sub_waybills_count < 1)
                            {
                                $count_of_sub_waybills_count = 1;
                            }
                        @endphp

                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$waybill->send_company}}</td>
                        <td>{{$waybill->id}}</td>
                        <td>
                            {{$count_of_sub_waybills_count}}
                        </td>
                    </tr>
                    {{--  --}}
                    @endforeach
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
