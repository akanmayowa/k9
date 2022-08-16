@extends('layouts.app')


@section('content')
<div class="row justify-content-center">
<div class="col-md-10">
    <div class="card-wrapper">
        <!-- Custom form validation -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header bg-dark border-0">
                {{-- <h3 class="mb-0"><span  style="color:coral">ACKNOWLEDGE MANIFEST </span></h3> --}}
                <h3 class="mb-0"><span  style="color:white">ACKNOWLEDGE TRANSFER</span></h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-sm-6 display-6">
                        <div class="mb-2"><b>Trasfer ID:</b> {{$transfer->id}}</div>
                        <div class="mb-2"><b>Route :</b>  {{$transfer->departure_site->name}} -----------------  {{$transfer->destination_site->name}}</div>
                        <div class="mb-2"><b>Dispatched At:</b> {{optional($transfer->created_at)->format('Y-m-d , g:i A') }}</div>
                        <div class="mb-2"><b>Dispatched By:</b> {{$transfer->created_by_user->name}}</div>
                        <div class="mb-2"><b>Email:</b> {{$transfer->created_by_user->email}}</div>
                        <div class="mb-2"><b>Phone:</b> {{$transfer->created_by_user->phone_number}}</div>
                    </div>
                    <div class="col-sm-6 ">

                        {{-- <div>MANIFEST ID:  {{$manifest->id }}</div>
                        <div>ROUTE: {{$manifest->scan_site->name}} -----------------  {{$manifest->next_site->name}}</div>
                        <div>DISPATCHED BY: {{$manifest->created_by_user->name}}</div>
                        <div>EMAIL : {{$manifest->created_by_user->email}}</div>
                        <div>PHONE NUMBER: {{$manifest->created_by_user->phone_number}}</div> --}}
                    </div>
                </div>
                <hr>
                <div class="row mb-4">
                    <div class="col-sm-12">

                        <b>Total Bags: </b> <span class="text-dark mb-1">{{$transfer->transfer_bags->count()}}</span><br>
                        <b>Total Acknowledged: </b><span class="text-dark mb-1">{{$transfer->transfer_bags->where('status', \App\Enums\TransferStatus::ACKNOWLEDGED)->count()}}</span><br>
                        @if ($transfer->status === \App\Enums\ManifestStatus::ACKNOWLEDGED || $transfer->status === \App\Enums\TransferStatus::PARTIALLY_RECEIVED)
                        <b>Total Missing: </b><span class="text-dark mb-1">{{$transfer->transfer_bags->where('status', \App\Enums\TransferStatus::IN_TRANSIT)->count()}}</span><br>
                        @endif

                    </div>

                </div>
                {!! Form::open(['route' => 'transfer.acknowledge', 'id' => 'transfer-acknowledge', 'class' => 'needs-validation', 'novalidate' => true]) !!}
                <div class="form-group">
                </div>

                {{-- <div class="text-white bg-gray mt-3 p-2">Toal Parcels in Bag ( <span id="numberofparcels" class="text-white">{{count($arrived_manifest_waybills_on_k9)}}</span> )
                </div> --}}

                <div class="table-responsive"  style="max-height: 400px; min-height:250px" >
                    <table class="table align-items-center table-flush table-striped">
                      <thead class="thead-light">
                        <tr>
                          <th>S/N</th>
                          <th>Bag Number</th>
                        </tr>
                      </thead>
                      <tbody id="addtobagbody">
                          @foreach ($transfer->transfer_bags as $bag)
                         <tr data-info="${waybill}"><td>{{++$loop->index }}</td><td>{{$bag->bag_id}}</td></tr>
                          @endforeach
                   </tbody>
                    </table>
                  </div>

                  <input type="hidden" value="{{$transfer->id}}" name="transfer_id">
                  <div class="col text-center mt-3">
                      <button class="btn btn-success" id= "acknowledge-manifest" type="submit">Acknowledge Receipt</button>
                    </div>
                  {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
