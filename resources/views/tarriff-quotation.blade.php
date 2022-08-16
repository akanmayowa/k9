@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col">
        <div class="card-wrapper">
            <!-- Custom form validation -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Tarriff Quotation</h3>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    {!! Form::open(['route' => 'getTarriff', 'id' => 'manifest-create', 'class' => 'needs-validation', 'novalidate' => true]) !!}
                    <div class="form-row">

                        <div class="col-md-4 mb-3">

                            <label class="form-control-label" for="departure_location_id">From</label>
                            {{ Form::select('departure_location_id', $locations, null, [
                                    'id' => 'departure_location_id',
                                    'class' => 'form-control',
                                    'data-toggle'=>"select",
                                    'placeholder' => 'From Where?',
                                    'required' => true
                              ]) }}
                            <div class="text-danger">
                                @error('destination_location_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">

                            <label class="form-control-label" for="destination_location_id">To</label>
                            {{ Form::select('destination_location_id', $locations, null, [
                                    'id' => 'destination_location_id',
                                    'class' => 'form-control',
                                    'data-toggle'=>"select",
                                    'placeholder' => 'To Where?',
                                    'required' => true
                              ]) }}
                            <div class="text-danger">
                                @error('destination_location_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-control-label" for="forwarding_locations">Forwarding Locations</label>
                            {{ Form::select('forwarding_locations',[], null, [
                                    'id' => 'forwarding_locations',
                                    'class' => 'form-control',
                                    'data-toggle'=>"select",
                                    'placeholder' => 'Pick exact location?',
                                    'required' => true
                              ]) }}
                            <div class="text-danger">
                                @error('forwarding_locations')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <label class="form-control-label" for="weight">Weight</label>
                                {{ Form::number('weight', null, [
                                    'id' => 'weight',
                                    'class'=>'form-control',
                                    'placeholder'=>'Shipment Weight',
                                    'required' => true,
                                    'autocomplete' => false,
                                    'maxlength' => 10
                                 ]) }}
                                @error('weight')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <label class="form-control-label" for="percentage_discount">Discount</label>
                                {{ Form::number('percentage_discount', null, [
                                    'id' => 'percentage_discount',
                                    'class'=>'form-control',
                                    'placeholder'=>'Discount Percentage',
                                    'required' => true,
                                    'autocomplete' => false,
                                    'maxlength' => 10
                                 ]) }}
                                @error('percentage_discount')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" id="quote">Get Quote</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Button trigger modal -->
{{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-get-quote">
    Launch demo modal
  </button> --}}

  <!-- Modal -->
  <div class="modal fade" id="modal-quotation" tabindex="-1" role="dialog" aria-labelledby="modal-quotation" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Quote</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-info-circle" style="font-size: 20px;"></i>
                <div><span class="destination_location"></span> falls under zone <span class="zone"></span> in <span class="departure_location"></span> Freight Chart</div>
                <div class="mb-4 mt-4">To Move <span class="weight-display text-dark"></span><span class="text-dark">KG</span> From <span class="departure_location text-dark bold"></span> to <span class="destination_location text-dark"></span> <br/> with <span class="forwarding_charge  text-darker"></span> Fowarding charge and <span class="percentage_discount  text-darker"></span>  Percentage Discount</div>
                <p id="tarriff-display" class="text-dark display-4">0</p>
            </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">PDF</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
$("#quote").on('click',function(event){
            event.preventDefault();

            let destinationLocationId = parseInt($("#destination_location_id").val());
            let departureLocationId = parseInt($("#departure_location_id").val());
            let weight = parseFloat($("#weight").val());
            let forwardingLocationId = parseInt($("#forwarding_locations").val());
            let percentage_discount = parseInt($("#percentage_discount").val());
            console.log(destinationLocationId, departureLocationId, weight, "percent d ", percentage_discount);



            if (isNaN(weight)) {
                Swal.fire({
                    type: 'error',
                    title: 'Validation Error !',
                    text: 'Please enter the weight of the shipment.',
                });

                return false;
            }


            if (isNaN(departureLocationId)) {
                Swal.fire({
                    type: 'error',
                    title: 'Validation Error !',
                    text: 'Please select a departure Location.',
                });

                return false;
            }

            if (isNaN(destinationLocationId)) {
                Swal.fire({
                    type: 'error',
                    title: 'Validation Error !',
                    text: 'Please select a desitnation Location.',
                });

                return false;
            }

            if (isNaN(forwardingLocationId)) {

                forwardingLocationId = null;
            }

            if (isNaN(percentage_discount)) {

                percentage_discount = null;
                }




            if (destinationLocationId > 0 && departureLocationId > 0 && weight > 0) {

            $.ajax({
                url: `/tarriff-quotation/${departureLocationId}/${destinationLocationId}/${forwardingLocationId}/${percentage_discount}/${weight}`,
                type: "GET",
                dataType: "json",
            })
                .done((tarriff) => {
                    console.log(tarriff.tarriff, tarriff.zone);
                    $(".departure_location").text(tarriff.departure_location);
                    $(".zone").text(tarriff.zone);
                    $(".weight-display").text(tarriff.weight);
                    $(".destination_location").text(tarriff.destination_location);
                    $(".percentage_discount").text(tarriff.percentage_discount);
                    $(".forwarding_charge").text(tarriff.forwarding_charge);
                    $("#tarriff-display").text(numberWithCommas(tarriff.tarriff));
                    $("#modal-quotation").modal('show');
                })
                .fail(function () {
                    console.log("! Error, Could not get Tarriff");
                });
        }
        });

        function numberWithCommas(x) {
    return `#${x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")}`;
}




$("#destination_location_id").on('change', function() {
            let destination_location_id = parseInt($("#destination_location_id").val());
            if (isNaN(destination_location_id)) {
                // Swal.fire({
                //     type: 'error',
                //     title: 'Validation Error !',
                //     text: 'Please select a destination location.',
                // });

                return false;
            }


				var url = '{{ route("getForwardingLocations", ":location_id") }}';

				url = url.replace(':location_id', destination_location_id);
            $.ajax({
                url: url,
                type: "GET",
                data : {
                    location_id : destination_location_id,
                },
                dataType: "json",
            })
                .done((result) => {

                    let temp = "";
                    if(result.success == true)
                    {
                        console.log(result.data);
                    let options =
                        "<option value='0'>Select exact location </option>";

                    result.data.forEach(function (forwardingmap) {
                    options += `<option value='${forwardingmap.id}'>${forwardingmap.town}</option>`;

                    });
                    $("#forwarding_locations").html(options);
                    }
                    else {
                        console.log("Could not retrieve forwarding locations from server'");
                                Swal.fire({
                                type: 'info',
                                title: 'Oops!',
                                text: 'Could not retrieve forwarding locations from server',
                                // footer: '<a href>Why do I have this issue?</a>'
                                });

                    }

                })
                .fail(function () {
                    console.log("! Error, Could not connect to k9 server to retrieve forwarding locations");
                    Swal.fire({
                                type: 'error',
                                title: 'Server Error',
                                text: 'Could not connect to server',
                                });
                });

        });

</script>
@endpush
