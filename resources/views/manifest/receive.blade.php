@extends('layouts.app')


@section('content')
{{-- <div>Confirm Parcels For Manifest <b>{{$manifest->id}}</b></div>
<p>Total Parcels {{count($manifest->waybills)}}</p> --}}
<div class="row justify-content-center">
<div class="col-md-10">
    <div class="card-wrapper">
        <!-- Custom form validation -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header bg-dark border-0">
                {{-- <h3 class="mb-0"><span  style="color:coral">ACKNOWLEDGE MANIFEST </span></h3> --}}
                <h3 class="mb-0"><span  style="color:white">ACKNOWLEDGE MANIFEST</span></h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-sm-6 display-6">
                        {{-- <div>MANIFEST ID:  {{$manifest->id }}</div> --}}
                        <div class="mb-2"><b>Manifest ID:</b> {{$manifest->id}}</div>
                        <div class="mb-2"><b>Route :</b>  {{$manifest->scan_site->name}} -----------------  {{$manifest->next_site->name}}</div>
                        {{-- <div>ROUTE: {{$manifest->scan_site->name}} -----------------  {{$manifest->next_site->name}}</div> --}}
                        {{-- <div>DISPATCHED BY: {{$manifest->created_by_user->name}}</div>
                        <div>EMAIL : {{$manifest->created_by_user->email}}</div>
                        <div>PHONE NUMBER: {{$manifest->created_by_user->phone_number}}</div> --}}

                        <div class="mb-2"><b>Seal Number:</b> {{$manifest->seal_number}}</div>
                        <div class="mb-2"><b>Dispatched At:</b> {{optional($manifest->created_at)->format('Y-m-d , g:i A') }}</div>
                        <div class="mb-2"><b>Dispatched By:</b> {{$manifest->created_by_user->name}}</div>
                        <div class="mb-2"><b>Email:</b> {{$manifest->created_by_user->email}}</div>
                        <div class="mb-2"><b>Phone:</b> {{$manifest->created_by_user->phone_number}}</div>
                        <div class="mb-2"><b>Trasport type:</b> {{ config('custom.transport_type')[(int)$manifest->transport_type_id]}}</div>
                        <div class="mb-2"><b>Driver's Name:</b> {{$manifest->driver_name}}</div>
                        <div class="mb-2"><b>Driver's Phone:</b> {{$manifest->driver_phonenumber}}</div>
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

                        <b>Total Waybills: </b> <span class="text-dark mb-1">{{$manifest->waybills->count()}}</span><br>
                        <b>Total Acknowledged: </b><span class="text-dark mb-1">{{$manifest->waybills->where('status', \App\Enums\ManifestStatus::ACKNOWLEDGED)->count()}}</span><br>
                        @if ($manifest->status === \App\Enums\ManifestStatus::ACKNOWLEDGED || $manifest->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED)
                        <b>Total Missing: </b><span class="text-dark mb-1">{{$manifest->waybills->where('status', \App\Enums\ManifestStatus::IN_TRANSIT)->count()}}</span><br>
                        @endif

                    </div>

                </div>
                {!! Form::open(['route' => 'acknowledgeManifest', 'id' => 'manifest-acknowledge', 'class' => 'needs-validation', 'novalidate' => true]) !!}
                <div class="form-group">
                </div>

                {{-- <div class="text-white bg-gray mt-3 p-2">Toal Parcels in Bag ( <span id="numberofparcels" class="text-white">{{count($arrived_manifest_waybills_on_k9)}}</span> )
                </div> --}}

                <div class="table-responsive"  style="max-height: 400px; min-height:250px" >
                    <table class="table align-items-center table-flush table-striped">
                      <thead class="thead-light">
                        <tr>
                          <th>S/N</th>
                          <th>Waybill Number</th>
                          <th>K9 Status</th>
                          <th>K9X Status</th>
                          {{-- <th>SCANNER</th> --}}
                          {{-- <th></th> --}}
                        </tr>
                      </thead>
                      <tbody id="addtobagbody">
                          @foreach ($manifest->waybills as $waybill)
                            @php
                            $k9_status = '<span class="text-warning">Not Yet Arrived on K9</span>';
                            $k9x_status = '<span class="text-warning">Not Yet Acknowledged</span>';
                            if ($arrived_manifest_waybills_on_k9->contains($waybill->id))
                            {
                                $k9_status = '<span class="text-success">Arrived On K9</span>';
                            }

                            if($waybill->status == 1)
                            {
                                $k9x_status = '<span class="text-success">Acknowledged</span>';
                            }
                            @endphp

                         <tr data-info="${waybill}"><td>{{++$loop->index }}</td><td>{{$waybill->id}}</td><td>{!!$k9_status!!}</td><td>{!!$k9x_status!!}</td></tr>
                          @endforeach
                   </tbody>
                    </table>
                  </div>

                  <input type="hidden" value="{{$manifest->id}}" name="manifest_id">
                  <input type="hidden" value="{{json_encode($arrived_manifest_waybills_on_k9)}}" id="waybills" name="waybills">
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


@push('scripts')
<script>


$("#acknowledge-manifest").click(function(event)
{

    manifest_id = parseInt($("#manifest_id").val());
    if(linesToSend.length == 0)
    {
        Swal.fire({
            icon: 'Error',
            title: 'Validation Error !',
            text: 'Please add waybills to the parcel bag ',
            footer: '<a href>Why do I have this issue?</a>'
            });

            return false;
    }

    // let isValid = false;
    //          $.ajax({
    //             url: "{{ route('acknowledgeManifest') }}",
    //             type: "POST",
    //             data: {seal_number: seal_number, manifest_id : manifest_id, waybills : linesToSend },
    //             dataType: "json",
    //         })
    //             .done((result) => {
    //                 // console.log("Seal Number : ", virtualSealnumber);
    //                 // $("#seal-number").fadeIn().val(virtualSealnumber);
    //                 Swal.fire({
    //                 icon: 'success',
    //                 title: 'Validation Error !',
    //                 text: result,
    //                 footer: '<a href>Why do I have this issue?</a>'
    //                 });

    //                 isValid = true;
    //             })
    //             .fail(function () {
    //                 console.log("! Error, Could not connect to server");
    //             });

});


// $("#modal-addparcels").on('show.bs.modal', function(){

//             $("#parcels").val("");
// });


</script>
@endpush
