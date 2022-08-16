@extends('layouts.app')


@section('content')

    <div class="card">
        <div class="card-header  bg-dark border-0">
            <h3 class="mb-0"><span  style="color:white">TRACK</span></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-control-label text-darker" for="waybill_number">WAYBILL NUMBER</label>
                        {{ Form::text('waybill_number', null, [
'id' => 'waybill_number',
'class' => 'form-control',
'placeholder' => 'Enter Waybill to track here',
'required' => true,
'autocomplete' => false,
]) }}
                        @error('waybill_number')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mt-4">
                        <a class="btn btn-primary" href="#!" id="track_waybill" data-original-title="Track">
                            TRACK IT
                        </a>
                    </div>
                </div>
            </div>
            <table class="table align-items-center table-flush table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>Waybill</th>
                        <th>Manifest id</th>
                        <th>Route</th>
                        <th>Dispatched</th>
                        <th>Acknowledged</th>
                    </tr>
                </thead>
                <tbody  id="track-waybill-table">

                </tbody>
            </table>
        </div>
    @endsection


    @push('scripts')
        <script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});


            $("#track_waybill").on('click', function(event) {
                event.preventDefault();

                let waybill_number = $("#waybill_number").val();


                if (waybill_number == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Validation Error !',
                        text: 'Please enter waybill number to track.',
                    });

                    return false;
                }




                $.ajax({
                        url: `/track`,
                        type: "POST",
                        dataType: "json",
                        data: {
                            waybill_number : waybill_number
                        }
                    })
                    .done((result) => {

                        if (result['success'] === true) {
                            if(result.data.length < 1)
                                Swal.fire({
                                    type: 'warning',
                                    title: '',
                                    text: `Waybill Records not found`,
                                });
                            } else {
                                Swal.fire({
                                    type: 'info',
                                    title: 'Failed',
                                    text: `${result['message']}`,
                                });

                                return false;

                            }

                        //if success ?

                        console.log(result);
                        // return;
                        let tableRow = "";
                        result.data.forEach((waybill, index) => {
                            let dispatched = `<div>By <span class="text-darker">${waybill.created_by_user.name}</span></div>
                            <div class="text-green">${moment(waybill.created_at).format('YYYY-MM-DD hh:mm:ss A')}</div>`;

                            let acknowledged = "<span class='text-red'>Not yet Acknowledged</span>";
                            if(waybill.acknwoledged_at != null)
                            {
                                acknowledged = `<div>By <span class="text-darker">${waybill.acknowledged_by_user.name}</span></div>
                            <div class="text-green">${moment(waybill.acknwoledged_at).format('YYYY-MM-DD hh:mm:ss A')}</div>`;
                            }

                            let route =` <span style="color:coral"> From </span><span class="text-muted">${waybill.scan_site.name}<span style="color:coral"> To </span>${waybill.next_site.name}</span>`;
                            tableRow +=
                                `<tr data-info="${waybill.id}"><td>${waybill.id}</td><td><a class='border-bottom border-dark' href='/manifest/${waybill.manifest_id}/details'>${waybill.manifest_id}</a></td><td>${route}</td><td>${dispatched}</td><td>${acknowledged}</td></tr>`;
                        });

                        $("#track-waybill-table").html(tableRow);
                    })
                    .fail(function() {
                        console.log("! Error, Could not get scan records");
                    });

            });
        </script>


    @endpush
