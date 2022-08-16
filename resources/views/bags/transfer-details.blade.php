@extends('layouts.app')


@section('content')
<div class="col-12 text-right">
    <a href="{{ url()->previous() }}" class="btn btn-default"  ><i class="fas fa-backward"></i>Back</a>
    <a href="#" class="btn btn-info pull-right" onclick="printContent('printableArea')"><i class="fa fa-print"></i>Print Bag Manifest</a>
</div>

<div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 padding">
    <div class="card" id="printableArea">
        <div class="card-header p-4">
            <a class="pt-2 d-inline-block" href="index.html" data-abc="true"> <img src=" {{ asset('img/speedaf_logo.png') }}"  alt="speedaf_logo"></a>
            <div class="float-right">
                <h3 class="mb-0 display-3">Transfer #{{$transfer->id}}</h3>
                <h3 class="mb-0 display-3">         @if($transfer->status === \App\Enums\ManifestStatus::IN_TRANSIT)
                    <span class="badge badge-default">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\TransferStatus::IN_TRANSIT]}}<span>
                    @elseif ($transfer->status === \App\Enums\TransferStatus::ACKNOWLEDGED)
                    <span class="badge badge-success">{{\App\Enums\TransferStatus::STATUS_TEXT[\App\Enums\TransferStatus::ACKNOWLEDGED]}}</span>
                    @elseif($transfer->status === \App\Enums\TransferStatus::PARTIALLY_RECEIVED)
                    <span class="badge badge-primary">{{\App\Enums\TransferStatus::STATUS_TEXT[\App\Enums\TransferStatus::PARTIALLY_RECEIVED]}}</span>
                    @endif
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-6">
                <h5 class="mb-3">From:</h5>
                <h3 class="text-dark mb-1">{{$transfer->departure_site->name}}</h3>
                </div>
                <div class="col-sm-6">
                    <h5 class="mb-3">To:</h5>
                    <h3 class="text-dark mb-1">{{$transfer->destination_site->name}}</h3>
                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-6">
                    <div class="mb-2"><b>Dispatched At:</b> {{optional($transfer->created_at)->format('Y-m-d , g:i A') }}</div>
                    <div class="mb-2"><b>Dispatched By:</b> {{$transfer->created_by_user->name}}</div>
                    <div class="mb-2"><b>Email:</b> {{$transfer->created_by_user->email}}</div>
                    <div class="mb-2"><b>Phone:</b> {{$transfer->created_by_user->phone_number}}</div>
                </div>
                <div class="col-sm-6 ">
                    @if ($transfer->status === \App\Enums\TransferStatus::ACKNOWLEDGED)
                    <div><b>Acknowledged At:</b> {{optional($transfer->acknowledged_at)->format('Y-m-d , g:i A') }}</div>
                    <div><b>Acknowledged By</b>  {{$transfer->acknowledged_by_user->name}}</div>
                    <div><b>Email:</b> {{$transfer->acknowledged_by_user->email}}</div>
                    <div><b>Phone:</b> {{$transfer->acknowledged_by_user->phone_number}}</div>
                    @endif

                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">

                    {{-- <!-- <b>Total Waybills: </b> <span class="text-dark mb-1">{{$transfer->waybills->count()}}</span><br> -->
                    <!-- <b>Total Acknowledged: </b><span class="text-dark mb-1">{{$transfer->waybills->where('status', \App\Enums\TransferStatus::ACKNOWLEDGED)->count()}}</span><br> -->
                    <!-- @if ($transfer->status === \App\Enums\TransferStatus::ACKNOWLEDGED || $transfer->status === \App\Enums\TransferStatus::PARTIALLY_RECEIVED) -->
                    <!-- <b>Total Missing: </b><span class="text-dark mb-1">{{$transfer->waybills->where('status', \App\Enums\TransferStatus::IN_TRANSIT)->count()}}</span><br> -->
                    <!-- @endif --> --}}

                </div>

            </div>
            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bag</th>
                            <th>Status</th>
                            <th>Dispatched</th>
                            <th>Acknowledged</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfer->transfer_bags as  $bag)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td class="left strong">{{$bag->bag_id}}</td>
                            <td class="left strong">  @if($bag->status === \App\Enums\BagStatus::IN_USE)
                                <span class="badge badge-default">{{\App\Enums\BagStatus::STATUS_TEXT[\App\Enums\BagStatus::IN_USE]}}<span>
                                @elseif ($bag->status === \App\Enums\BagStatus::ACKNOWLEDGED)
                                <span class="badge badge-success">{{\App\Enums\BagStatus::STATUS_TEXT[\App\Enums\BagStatus::ACKNOWLEDGED]}}</span>
                                @elseif($bag->status === \App\Enums\BagStatus::ON_TRANSFER)
                                <span class="badge badge-primary">{{\App\Enums\BagStatus::STATUS_TEXT[\App\Enums\BagStatus::ON_TRANSFER]}}</span>
                                @endif
                            </td>
                            <td> By ({{$bag->created_by_user->name}}) <br>
                                On {{$bag->created_at}}</td>
                            <td>

                                <br>
                                @if ($bag->status === \App\Enums\BagStatus::ACKNOWLEDGED)
                                By {{optional($bag->acknowledged_by_user)->name}} <br/>
                                On the  {{$bag->acknwoledged_at}}
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
