@inject('WaybillStatus', '\App\Enums\WaybillStatus')
@extends('layouts.app')


@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header bg-dark border-0">
            <div class="row">
              <div class="col-6">
                <h3 class="mb-0"><span  style="color:white">INCOMING WAYBILLS</span></h3>
              </div>
              <div class="col-6 text-right">
                {{-- <a href="#" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Edit Manifest">
                  <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                  <span class="btn-inner--text">Export</span>
                </a> --}}
              </div>
            </div>
          </div>
        <!-- Light table -->

        <div class="card-body">
            <div class="row"> {{-- Start Row --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="search-date">Date</label>
                        <input type="text" class="form-control" id="search-date" name="search-date">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="form-control-label" for="scan_site_id"> From </label>
                        @php
                            $from_attributes = [
                                'id' => 'scan_site_id',
                                'class' => 'form-control sacn_site',
                                'data-toggle' => 'select',
                                // 'placeholder' => 'All Sites',
                                'required' => true,
                            ];

                            $to_attributes = [
                                'id' => 'next_site_id',
                                'class' => 'form-control next_site',
                                'data-toggle' => 'select',
                                'placeholder' => 'All Sites',
                                'required' => true,
                            ];

                            if (Auth::user()->hasanyrole(['Quality Control Personnel'])) {
                                $from_attributes['placeholder'] = 'All Sites';

                                // $to_attributes[ 'placeholder'] = 'All Sites';
                            }

                        @endphp
                        {{ Form::select('scan_site_id', $from_sites, null, $from_attributes) }}

                        <div class="text-danger">
                            @error('scan_site_id')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- <div class="col-sm-3">
                    <div class="form-group">
                        <label class="form-control-label" for="next_site_id"> To</label>
                        {{ Form::select('next_site_id', $to_sites, null, $to_attributes) }}
                        <div class="text-danger">
                            @error('next_site_id')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="col-sm-2">
                    <div class="form-group">
                        <label class="form-control-label" for="status">Status</label>
                        {{ Form::select('status', \App\Enums\WaybillStatus::STATUS_TEXT, null, [
    'id' => 'status',
    'class' => 'form-control',
    'data-toggle' => 'select',
    'placeholder' => 'All Status',
    'required' => true,
]) }}
                        <div class="text-danger">
                            @error('status')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div> --}}

            </div> {{-- End Rows --}}
            <div class="row"> {{-- Start Row --}}

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
                        <th>Waybill Id</th>
                        <th>route</th>
                        <th>Status</th>
                        <th>Manifest ID</th>
                        <th>Dispatched</th>
                        <th>Action</th>
                        {{-- <th>Departing</th>
            <th>Arriving</th> --}}
                        {{-- <th>Total Parcels</th> --}}
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>




    <!-- The Modal -->
    <div class="modal fade" id="modal-view-details">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Waybill ( <span id="modal-waybill-id"></span>)</h4>
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

                    <div class="table-responsive" style="max-height: 250px; min-height:250px">
                        <table class="table-lighter table align-items-center table-flush table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Waybill Number</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="addtobagbody">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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
                    <h4 class="modal-title">Destination Site ( <span id="modal-destination-site"></span>)</h4>
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

                    <div class="table-responsive" style="max-height: 250px; min-height:250px">
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
            $('document').ready(function() {

                var start_date;
                var end_date;

                var start = moment(); //moment().subtract(29, 'days');
                var end = moment();

                $('#search-date').daterangepicker({
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
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    }
                }, function cb(start, end) {
                    // $('#search-date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    // console.log(start.format('YYYY-MM-DD'));
                    // console.log(end.format('YYYY-MM-DD') );

                    start_date = start.format('YYYY-MM-DD');
                    end_date = end.format('YYYY-MM-DD');
                });

                //   cb(start, end);

                //Implement Sever Side Rendering soon
                let workingTable = $('#moderator-view-table').DataTable({

                        processing: true,
                        serverSide: true,
                        dom: 'rtBp',
                        buttons: [
                            {
                extend: 'copyHtml5',
                text : 'Copy Only Wabills Numbers',
                exportOptions: {
                    columns: [ 1 ]
                }
            },
            {
                extend: 'csv',
                text: 'Export as CSV',
                className: "btn dark btn-outline",
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            }
                        ],
                        //stateSave: true, research the consequence well
                        pagingType: 'first_last_numbers',
                        pageLength: 500,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        ajax: {
                            url: "{{ route('getIncomingWaybills') }}",
                            data: function(d) {
                                d.status = $('#status').val(), //$('input[name=start]').val()
                                    d.scan_site_id = $("#scan_site_id").val(),
                                    d.next_site_id = $("#next_site_id").val(),
                                    d.start_date = start_date, //
                                    d.end_date = end_date
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: "id",
                                name: "waybills.id"
                            },
                            {
                                data: "route",
                                name: "route"
                            },
                            {
                                data: "status_label"
                            },
                            {
                                data: "manifest_id",
                                name: 'manifest_id'
                            },
                            // { data: "manifest.scan_site_id" },
                            // { data: "manifest.next_site_id"},
                            // { data: "manifest.scan_site.name", name: 'manifest.scan_site.name' },
                            // { data: "manifest.next_site.name" , name : 'manifest.next_site.name'},
                            {
                                data: "dispatched",
                                name: "dispatched"
                            },
                            {
                                data: "action",
                                name: "action"
                            },
                        ]
                    }

                );


                //   ------------------
                $('#status').change(function() {
                    workingTable.draw();
                });

                $('#scan_site_id').change(function() {
                    workingTable.draw();
                });

                $('#next_site_id').change(function() {
                    workingTable.draw();
                });

                $('#search-date').change(function() {
                    workingTable.draw();
                });

            });
        </script>
    @endpush



    @push('scripts')
        <script>
            //Your Scripts here
        </script>
    @endpush
