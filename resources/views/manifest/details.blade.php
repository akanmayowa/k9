@extends('layouts.app')


@section('content')
<div class="col-12 text-right">
    <a href="{{ url()->previous() }}" class="btn btn-default"  ><i class="fas fa-backward"></i>Back</a>
    <a href="#" class="btn btn-info pull-right" onclick="printContent('printableArea')"><i class="fa fa-print"></i>Print Manifest</a>
</div>

<div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 padding">
    <div class="card" id="printableArea">
        <div class="card-header p-4">
            <a class="pt-2 d-inline-block" href="index.html" data-abc="true"> <img src=" {{ asset('img/speedaf_logo.png') }}"  alt="speedaf_logo"></a>
            <div class="float-right">
                <h3 class="mb-0 display-3">Manifest #{{$manifest->id}}</h3>
                <h3 class="mb-0 display-3">         @if($manifest->status === \App\Enums\ManifestStatus::IN_TRANSIT)
                    <span class="badge badge-default">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::IN_TRANSIT]}}<span>
                    @elseif ($manifest->status === \App\Enums\ManifestStatus::ACKNOWLEDGED)
                    <span class="badge badge-success">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::ACKNOWLEDGED]}}</span>
                    @elseif($manifest->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED)
                    <span class="badge badge-primary">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED]}}</span>
                    @endif
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-6">
                <h5 class="mb-3">From:</h5>
                <h3 class="text-dark mb-1">{{$manifest->scan_site->name}}</h3>
                </div>
                <div class="col-sm-6">
                    <h5 class="mb-3">To:</h5>
                    <h3 class="text-dark mb-1">{{$manifest->next_site->name}}</h3>
                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-6">
                    {{-- <div><b>Seal Number:</b> {{$manifest->seal_number}}</div> --}}
                    <div class="mb-2"><b>Bag Number:</b> {{$manifest->bag_id}}</div>
                    <div class="mb-2"><b>Seal Number:</b> {{$manifest->seal_number}}</div>
                    <div class="mb-2"><b>Dispatched At:</b> {{optional($manifest->created_at)->format('Y-m-d , g:i A') }}</div>
                    <div class="mb-2"><b>Dispatched By:</b> {{$manifest->created_by_user->name}}</div>
                    <div class="mb-2"><b>Email:</b> {{$manifest->created_by_user->email}}</div>
                    <div class="mb-2"><b>Phone:</b> {{$manifest->created_by_user->phone_number}}</div>
                    <div class="mb-2"><b>Shipment type:</b> {{ config('custom.shipment_type')[(int)$manifest->shipment_type]}}</div>
                    <div class="mb-2"><b>Transport type:</b> {{ config('custom.transport_type')[(int)$manifest->transport_type_id]}}</div>
                    <div class="mb-2"><b>Driver's Name:</b> {{$manifest->driver_name}}</div>
                    <div class="mb-2"><b>Driver's Phone:</b> {{$manifest->driver_phonenumber}}</div>
                    <div class="mb-2"><b>Remark:</b> {{$manifest->remark}}</div>
                </div>
                <div class="col-sm-6 ">
                    {{-- <div>478, Nai Sadak</div> --}}
                    @if ($manifest->status === \App\Enums\ManifestStatus::ACKNOWLEDGED)
                    <div><b>Acknowledged At:</b> {{optional($manifest->acknowledged_at)->format('Y-m-d , g:i A') }}</div>
                    <div><b>Acknowledged By</b>  {{$manifest->acknowledged_by_user->name}}</div>
                    <div><b>Email:</b> {{$manifest->acknowledged_by_user->email}}</div>
                    <div><b>Phone:</b> {{$manifest->acknowledged_by_user->phone_number}}</div>
                    @endif

                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">

                    <b>Total Waybills: </b> <span class="text-dark mb-1">{{$manifest->waybills->count()}}</span><br>
                    <b>Total Acknowledged: </b><span class="text-dark mb-1">{{$manifest->waybills->where('status', \App\Enums\ManifestStatus::ACKNOWLEDGED)->count()}}</span><br>
                    @if ($manifest->status === \App\Enums\ManifestStatus::ACKNOWLEDGED || $manifest->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED)
                    <b>Total Missing: </b><span class="text-dark mb-1">{{$manifest->waybills->where('status', \App\Enums\ManifestStatus::IN_TRANSIT)->count()}}</span><br>
                    @endif

                </div>

            </div>
            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Waybill</th>
                            <th>Status</th>
                            <th>Dispatched</th>
                            <th>Acknowledged</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($manifest->waybills as  $waybill)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td class="left strong">{{$waybill->id}}</td>
                            <td class="left strong">  @if($waybill->status === \App\Enums\ManifestStatus::IN_TRANSIT)
                                <span class="badge badge-default">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::IN_TRANSIT]}}<span>
                                @elseif ($waybill->status === \App\Enums\ManifestStatus::ACKNOWLEDGED)
                                <span class="badge badge-success">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::ACKNOWLEDGED]}}</span>
                                @elseif($waybill->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED)
                                <span class="badge badge-primary">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED]}}</span>
                                @endif
                            </td>
                            <td> By ({{$waybill->created_by_user->name}}) <br>
                                On {{$waybill->created_at}}</td>
                            <td><br>
                                @if ($waybill->status === \App\Enums\ManifestStatus::ACKNOWLEDGED)
                                By {{optional($waybill->acknowledged_by_user)->name}} <br/>
                                On the  {{$waybill->acknwoledged_at}}
                                @else
                                -
                                @endif

                            </td>       {{-- can ne null --}}
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
