@extends('layouts.app')


@section('content')

    <div class="card">
        <div class="card-header  bg-dark border-0">
            <h3 class="mb-0"><span  style="color:white">STATION ARRIVED WAYBILL SCAN BOARD</span></h3>

        </div>
        <div class="card-body">
                    <div class="row">            {{-- Start Row --}}
            <div class="col-sm-4">
                <div class="form-group">
					<label for="search-date">PERIOD(start date - end date)</label>
					<input type="text" class="form-control" id="search-date" name="search-date" >
				</div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-control-label" for="scan_site_id">ARRIVAL STATION </label>
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
                            // 'placeholder' => 'All Sites',
                            'required' => true
                ];

                    @endphp
                        {{ Form::select('scan_site_id', $from_sites, null, $from_attributes ) }}

                    <div class="text-danger">
                        @error('scan_site_id')
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
            <table class="table align-items-center table-flush"   id="scan-records-table">
                <thead class="thead-light">
                    <tr>
                        <th>S/N</th>
                        <th>Waybill</th>
                        <th>Delivery Scan</th>
                        <th>Collection Scan</th>
                        <th>Issue Parcel Scan</th>
                        <th>Return Scan</th>
                        <th>Departure Scan</th>
                    </tr>
                </thead>
                <tbody>

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

        function getStatus()
        {
            if( $('#status').val() == "")
            {
                return -1;
            }
            else
            {
                return  $('#status').val();
            }
        }

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
          let workingTable =  $('#scan-records-table').DataTable(
            {

                processing: true,
                serverSide: true,
                dom: 'rtBp',
                buttons: [
                    'csv', 'print'
                ],
                //stateSave: true, research the consequence well
                pagingType: 'first_last_numbers',
                pageLength: 1000,
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                ajax:
                {
                    url:  "{{ route('StationArrivedWaybillsScanRecord') }}",
                    data: function(d){
                        d.status = getStatus(), //$('input[name=start]').val()
                        d.scan_site_id = $("#scan_site_id").val(),
                        d.next_site_id = $("#next_site_id").val(),
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

                    // {data: "id", name:"id"},
                    { data: "BILL_CODE", name: "BILL_CODE" },
                    { data: "display_delivery_count", name: "display_delivery_count" },
                    { data: "display_collection_count" , name : 'display_collection_count'},
                    {data:"display_issue_parcel_count", name:"display_issue_parcel_count"},
                    { data: "display_return_count", name: 'display_return_count' },
                    { data: "display_departure_count", name: "display_departure_count" },

                    // {data: "route", name: "route"},
                    // {data: "dispatched_waybills_count", name:"dispatched_waybills_count"},
                    // {data: "acknowledged_waybills_count", name:"acknowledged_waybills_count"},
                    // {data: "pending_waybills_count", name:"pending_waybills_count"},
                    // { data: "status_label" },
                    // // {data: "total_parcels"},
                    // {data: "dispatched", name:"dispatched"},
                    // {data: "pending_waybills_count_classic", name:"pending_waybills_count_classic"},
                    // {data: "acknowledged_waybills_count_classic", name:"acknowledged_waybills_count_classic"},
                    // {data: "dispatched_waybills_count_classic", name:"dispatched_waybills_count_classic"},
                    // {data: "dispatched_date_classic", name:"dispatched_date_classic"},
                    // {data: "dispatched_time_classic", name:"dispatched_time_classic"},
                    // {data: "action", name:"action"},
                ]
            }

          );


        //   ------------------
        $('#status').change(function(){
            workingTable.draw();
            console.log('status', getStatus());
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

        });
    </script>
@endpush
