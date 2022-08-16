@inject('ManifestStatus', '\App\Enums\ManifestStatus')
@extends('layouts.app')

@php
$from_attributes = [
        'id' => 'scan_site_id',
        'class' => 'form-control sacn_site',
        'data-toggle'=>"select",
        // 'placeholder' => 'All Sites',
        'required' => true
];


$to_attributes =  [
    'id' => 'next_site_id',
    'class' => 'form-control next_site',
    'data-toggle'=>"select",
    'placeholder' => 'All Sites',
    'required' => true
];

if(Auth::user()->hasanyrole(['Quality Control Personnel']))
{
 $from_attributes['placeholder'] = 'All Sites';

$to_attributes[ 'placeholder'] = 'All Sites';

}
@endphp
@section('content')
<div class="card">
            <!-- Card header -->
            <div class="card-header bg-dark border-0">
                <div class="row">
                  <div class="col-6">
                    <h3 class="mb-0"><span  style="color:white">DISPATCHED MANIFESTS</span></h3>
                  </div>
                  <div class="col-6 text-right">
                    {{-- <a href="#" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Edit Manifest">
                      <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                      <span class="btn-inner--text">Export</span>
                    </a> --}}
                  </div>
                </div>
              </div>
    <div class="card-body">
        <div class="row">            {{-- Start Row --}}
            <div class="col-sm-4">
                <div class="form-group">
					<label for="search-date">DATE</label>
					<input type="text" class="form-control" id="search-date" name="search-date" >
				</div>
            </div>
            {{-- <div class="col-sm-3">
                <div class="form-group">
                    <label class="form-control-label" for="scan_site_id"> From </label>

                        {{ Form::select('scan_site_id', $from_sites, null, $from_attributes ) }}

                    <div class="text-danger">
                        @error('scan_site_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div> --}}
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="form-control-label" for="next_site_id"> TO</label>
                    {{ Form::select('next_site_id', $to_sites, null, $to_attributes) }}
                    <div class="text-danger">
                        @error('next_site_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="form-control-label" for="created_by"> BY</label>
                    {{ Form::select('created_by', $site_users, null, [
                            'id' => 'created_by',
                            'class' => 'form-control',
                            'data-toggle' => 'select',
                            'placeholder' => 'Select Operator',
                            'required' => true,
                            ]) }}
                    <div class="text-danger">
                        @error('created_by')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            {{-- <div class="col-sm-2">
                <div class="form-group">
                  <label class="form-control-label" for="status">Status</label>
                    {{ Form::select('status', \App\Enums\ManifestStatus::STATUS_TEXT, null, [
                            'id' => 'status',
                            'class' => 'form-control',
                            'data-toggle'=>"select",
                            'placeholder' => 'All Status',
                            'required' => true
                    ]) }}
                    <div class="text-danger">
                        @error('status')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div> --}}

          </div> {{-- End Rows --}}
          <div class="row">            {{-- Start Row --}}

            {{-- <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-control-label" for="start_date"> Start Date</label>
                        {{ Form::select('start_date', ['Test', 'Test'], null, [
                                'id' => 'start_date',
                                'class' => 'form-control next_site',
                                'data-toggle'=>"select",
                                'placeholder' => 'Select Start Date',
                                'required' => true
                        ]) }}

                    <div class="text-danger">
                        @error('start_date')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-sm-4">
                <div class="form-group">
					<label for="search-date">End Date</label>
					<input type="text" class="form-control" id="search-date" name="search-date" >
				</div>
            </div> --}}

            {{-- <div class="col-sm-4">
                <div class="form-group mt-4">
                  <button class="btn btn-default" id="search-departure-scans" >
                    <i class="fas fa-search text-warning"></i> SEARCH
                  </button>
                </div>
            </div> --}}

          </div> {{-- End Rows --}}
    </div>
    <div class="table-responsive">
      <table class="table align-items-center table-flush table-striped" id="moderator-view-table">
        <thead class="thead-light">

          <tr>
            <th>S/N</th>
            <th>Seal Number</th>
            {{-- <th>Departing</th>
            <th>Arriving</th> --}}
            <th>route</th>
            <th>Dispatched</th>
            <th>DWC</th>
            {{-- <th>AWC</th>
            <th>PWC</th> --}}
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>




        	<!-- The Modal -->
            <div class="modal fade" id="modal-manifest-waybills">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <h4 class="modal-title" >Manifest ( <span id="modal-manifest-id"></span>)</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        {{-- <div class="error-space">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                <span class="alert-text"><strong>Default!</strong> This is a default alert—check it out!</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div> --}}

                        {{-- <div class="alert alert-info" role="alert">
                            <strong class="text-uppercase">Warning!</strong> Some Information here
                        </div> --}}

                        <div class="table-responsive"  style="max-height: 250px; min-height:250px" >
                            <table class="table-dark table align-items-center table-flush table-striped">
                              <thead class="thead-light">
                                <tr>
                                  <th>S/N</th>
                                  <th>Waybill Number</th>

                                </tr>
                              </thead>
                              <tbody id="manifest_waybills_table">
                           </tbody>
                            </table>
                          </div>
                        {{-- <form id="addtobag-form">
                        <div class="form-group">
                            <label for="parcels">Parcels</label>
                            <textarea class="form-control" id="parcels" rows="3" required></textarea>
                          </div> --}}
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                      {{-- <button type="button" class="btn btn-primary"  id="continue">Continue</button>
                      <button type="submit"  id="continue" class="btn btn-primary" value="Continue"> --}}
                    </div>
                {{-- </form> --}}
                  </div>
                </div>
              </div>

              <div class="modal fade" id="modal-view-destination-site">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <h4 class="modal-title" >Destination Site ( <span id="modal-destination-site"></span>)</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        {{-- <div class="error-space">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                <span class="alert-text"><strong>Default!</strong> This is a default alert—check it out!</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div> --}}

                        {{-- <div class="alert alert-info" role="alert">
                            <strong class="text-uppercase">Warning!</strong> Some Information here
                        </div> --}}

                        <div class="table-responsive"  style="max-height: 250px; min-height:250px" >
                            <table class="table-dark table align-items-center table-flush table-striped">
                              <thead class="thead-light">
                                <tr>
                                  <th>S/N</th>
                                  <th>Account ID</th>
                                  <th>Name</th>
                                  <th>Phone Number</th>


                                </tr>
                              </thead>
                              <tbody id="site-employees-table">
                           </tbody>
                            </table>
                          </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                  </div>
                </div>
              </div>
@endsection

@push('scripts')
<script>

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});


</script>
@endpush

{{-- [
    'copy', 'csv', 'excel', 'pdf', 'print'
] --}}

@push('scripts')

    <script>


        $('document').ready(function () {

            var start_date;
            var end_date;

            var start = moment();//moment().subtract(29, 'days');
            var end = moment();
        function cb(start, end) {
            // $('#search-date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            // console.log(start.format('YYYY-MM-DD'));
            console.log("start_date: " , start.format('YYYY-MM-DD'), " end_date: ", end.format('YYYY-MM-DD'));

            start_date = start.format('YYYY-MM-DD');
            end_date = end.format('YYYY-MM-DD');
        }


          $('#search-date').daterangepicker(
            {
                showDropdowns: true,
                timePicker: true,
                startDate: start,
                endDate: end,
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
    }, cb
          );

          cb(start, end);

            //Implement Sever Side Rendering soon
          let workingTable =  $('#moderator-view-table').DataTable(
            {

                processing: true,
                serverSide: true,
                dom: 'rtBp',
                buttons: [
                    'csv', 'print'
                ],
                //stateSave: true, research the consequence well
                pagingType: 'first_last_numbers',
                pageLength: 30,
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                ajax:
                {
                    url:  "{{ route('getDispatchedManifests') }}",
                    data: function(d){
                        d.status = $('#status').val(), //$('input[name=start]').val()
                        d.scan_site_id = $("#scan_site_id").val(),
                        d.next_site_id = $("#next_site_id").val(),
                        d.created_by =  $("#created_by").val()
                        d.start_date = start_date,//
                        d.end_date = end_date
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    { data: "seal_number", name: "seal_number" },
                    // { data: "scan_site.name", name: 'scan_site.name' },
                    // { data: "next_site.name" , name : 'next_site.name'},
                    {data: "route", name: "route"},
                    {data: "dispatched", name:"dispatched"},
                    {data: "dispatched_waybills_count", name:"dispatched_waybills_count"},
                    // {data: "acknowledged_waybills_count", name:"acknowledged_waybills_count"},
                    // {data: "pending_waybills_count", name:"pending_waybills_count"},
                    { data: "status_label" },
                    // {data: "total_parcels"},
                    {data: "action", name:"action"},
                ]
            }

          );


        //   ------------------
        $('#status').change(function(){
            workingTable.draw();
    });

    $('#scan_site_id').change(function(){
            workingTable.draw();
    });

    $('#next_site_id').change(function(){
            workingTable.draw();
    });

    $('#search-date').change(function(){
            workingTable.draw();
    });

    $('#created_by').change(function(){
            workingTable.draw();
    });




        });


        $(document).on('click', '.manifest_waybills', function(event){
            $("#manifest_waybills_table").html("");

            console.log(event.target.closest('tr').dataset.manifest_id);
            let manifest_id = event.target.closest('tr').dataset.manifest_id;
            let status =  event.target.dataset.waybill_status;

            console.log('status: ', status);

            // console.log("get pending waybills");
            $.ajax({
                        url: `/manifest-waybills`,
                        data: {
                            manifest_id : manifest_id,
                            status : status
                        },
                        type: "GET",
                        dataType: "json",
                    })
                    .done((result) => {
                    if (result['success'] === true) {
                            let tableRow = "";
                        console.log("Waybills : ", result.data);
                        // $("#seal-number").fadeIn().val(virtualSealnumber);

                        result.data.forEach((waybill, index) => {
                        tableRow +=
                            `'<tr data-info="${waybill.id}"><td>${++index}</td><td>${waybill.id}</td></tr>`;

                        // console.log(tableRow);

                        });
                        $("#modal-manifest-id").html(manifest_id);
                        $("#manifest_waybills_table").html(tableRow);
                        $('#modal-manifest-waybills').modal('show');

                    }
                    else
                    {
                        Swal.fire({
                                    type: 'info',
                                    title: 'Oops !',
                                    text: 'No record found',
                                });

                                return false;

                    }
                    })
                    .fail(function() {
                        console.log("! Error, Could not get Manifest Waybills");
                    });


        });
    </script>
@endpush
