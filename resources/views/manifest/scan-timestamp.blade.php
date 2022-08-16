@extends('layouts.app')
@section('content')
    @php

    $to_attributes = [
        'id' => 'departure_list_filter_site_id',
        'class' => 'form-control departure_list_filter_site_id',
        'data-toggle' => 'select',
        'placeholder' => 'All Sites',
        'required' => true,
    ];
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark border-0">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="mb-0"><span style="color:white">CREATE MANIFEST</span></h3>
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
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-sm btn-primary text-white mb-3"
                                id="scanning-create-group">create group</button>
                        </div>
                        {{-- <div class="col">
                            <button type="button" class="btn btn-sm btn-default text-white mb-3" id="get-departure-list">K9
                                Departure List</button>
                        </div> --}}
                          {{-- <div class="col">
                            <button type="button" class="btn btn-sm btn-default text-white mb-3" id="get-departure-list">Last N K9 Scan</button>
                        </div> --}}
                    </div>
                    <div id='loadingmessage' style='display:none'>
                        <img src='img/ajax-loader.gif' />
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush table-striped" id="scan-timestamps-table">
                            <thead class="bg-default text-white">

                                <tr>
                                    <th>Next Site</th>
                                    <th>Period</th>
                                    {{-- <th>Waybill Count</th> --}}
                                    <th>Scanned By</th>
                                    <th>Remark</th>
                                    {{-- <th>Scan Site</th> --}}
                                    {{-- <th>Seal Number</th> --}}
                                    <th>Action</th>
                                    {{-- <th>End Time</th> --}}
                                    <th></th>
                                    {{-- <th></th> --}}
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- modal-group-form --}}
    <!-- Modal -->
    <div class="modal fade" id="modal-group-form" tabindex="-1" role="dialog" aria-labelledby="modal-group-form"
        aria-hidden="true">
        <div class="modal-dialog modal-lg  modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-errorLabel">Create Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-errorBody">
                    <div class="row"> {{-- Start Row --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="next_site_id"> Next Site</label>
                                {{ Form::select('next_site_id', $site_list, null, [
    'id' => 'next_site_id',
    'class' => 'form-control next_site_id',
    'data-toggle' => 'select',
    'placeholder' => 'Select Next Site',
    'required' => true,
]) }}
                                <div class="text-danger">
                                    @error('next_site_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="scanner_id"> Scanner</label>
                                {{ Form::select('scanner_id', $site_users, null, [
    'id' => 'scanner_id',
    'class' => 'form-control scanner_id',
    'data-toggle' => 'select',
    'placeholder' => 'Every Scanner',
    'required' => true,
]) }}
                                <div class="text-danger">
                                    @error('scanner_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period_type">Period Type</label>
                                <select name="period_type" id="period_type" class="custom-select">
                                    {{-- <option value="">Select Period Type</option> --}}
                                    <option value="auto_period">Auto Period</option>
                                    <option value="custom_period">Custom Period</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" style="display: none" id="group-create-date-range-row">
                            <div class="form-group">
                                <label for="group-create-date-range">Scan Period (Start Time to End Time)</label>
                                <input type="text" class="form-control" id="group-create-date-range"
                                    name="group-create-date-range">
                            </div>
                        </div>
                        <div class="col-md-6" style="display: none" id="auto_period_input_row">
                            <div class="form-group">
                                <label for="auto_period_input">Scan Period (Start Time to End Time)</label>
                                <input type="text" class="form-control" id="auto_period_input" name="auto_period_input"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="group-tag">Add remark ? </label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="add-group-tag" value="true" checked>
                            {{-- <label class="form-check-label" for="inlineCheckbox1">1</label> --}}
                        </div>
                        <textarea class="form-control" id="group-tag" rows="3"
                            placeholder="For example, Abuja DC Bag 1 that was scanned by somebody!"></textarea>
                    </div>
                    <input type="hidden" id="start_date" name="start_date">
                    <input type="hidden" id="end_date" name="end_date">
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                    <button type="button" class="btn btn-primary" id="scanning-save-group">Start Grouping</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Start --}}




    <div class="modal fade" id="modal-departure-list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg  modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Departure List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- BODY STARTS HERE --}}

                    <div class="row" id="departure_list_filters"> {{-- Start Row --}}
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="departure_list_filter_date">Date / Time</label>
                                <input type="text" class="form-control" id="departure_list_filter_date"
                                    name="departure_list_filter_date">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-control-label" for="departure_list_filter_site_id"> Next </label>
                                {{ Form::select('departure_list_filter_site_id', $site_list, null, $to_attributes) }}

                                <div class="text-danger">
                                    @error('departure_list_filter_site_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-control-label" for="departure_list_filter_scanner_id"> Scanner</label>
                                {{ Form::select('departure_list_filter_scanner_id', $site_users, null, [
    'id' => 'departure_list_filter_scanner_id',
    'class' => 'form-control',
    'data-toggle' => 'select',
    'placeholder' => 'Select Scanner',
    'required' => true,
]) }}
                                <div class="text-danger">
                                    @error('departure_list_filter_scanner_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div> {{-- End Rows --}}


                    <div class="table-responsive" style="max-height: 300px; min-height:250px">
                        <table id="departure_list_table" class="table table-dark align-items-center table-flush table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Waybill Number</th>
                                    <th>Next Site</th>
                                    <th>Scan Date</th>
                                    <th>SCANNER</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>


                    {{-- BODY ENDS HERE --}}
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>






    {{-- End list --}}


    {{-- CREATE MANIFEST FORM------------------------- START --}}

    <div class="modal" tabindex="-1" id="modal-manifest-form" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Manifest</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- START DALOG BODY --}}

                    {!! Form::open(['route' => 'storeManifest', 'id' => 'manifest-create', 'class' => 'needs-validation', 'novalidate' => true]) !!}
                    <div class="text-default"><b>Total Waybills </b> ( <span id="number_of_waybills_to_send"
                            class="text-red">0</span> )
                    </div>
                    <div class="table-responsive" style="max-height: 600px; min-height:400px">
                        <table id="waybills-table" class="table align-items-center table-flush table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Waybill Number</th>
                                    <th>Scan Date</th>
                                    <th>Weight</th>
                                    <th>SCANNER</th>

                                </tr>
                            </thead>
                            <tbody id="waybills_table">
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="seal-number">Seal Number <span class="text-red">*</span></label> <a
                                    class="btn btn-sm btn-primary" href="#!" id="get_seal_number"
                                    data-original-title="Request Seal Number">
                                    virtual seal
                                </a>
                                <input type="text" class="form-control" id="seal-number" name="seal_number">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="bag_number">Bag Number  <span class="text-red">( e.g DCWH-0001)</span></label>
                                <input type="text" class="form-control" id="bag_number" name="bag_number">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-control-label" for="shipment_type"> Shipment Type <span
                                        class="text-red">*</span> </label>
                                {{ Form::select('shipment_type', config('custom.shipment_type', ['Forward', 'Reverse']), null, [
    'id' => 'shipment_type',
    'class' => 'form-control shipment_type',
    'data-toggle' => 'select',
    'placeholder' => 'Select Shipment Type',
    'required' => true,
]) }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-control-label" for="transport_type"> Transport Type <span
                                        class="text-red">*</span></label>
                                {{ Form::select('transport_type', config('custom.transport_type', ['Air', 'Shuttle', '3rd Party', 'others']), null, [
    'id' => 'transport_type',
    'class' => 'form-control transport_type',
    'data-toggle' => 'select',
    'placeholder' => 'Select transport type',
    'required' => true,
]) }}
                                <div class="text-danger">
                                    @error('transport_type')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="group-tag" class="text-darker">Add optional details ? </label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="add-optional-details" value="true">
                            {{-- <label class="form-check-label" for="inlineCheckbox1">1</label> --}}
                        </div>
                        {{-- <textarea class="form-control" id="group-tag" rows="3"></textarea> --}}
                    </div>
                    <div class="row" id="optional-details">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="manifest-remark">Remark</label>
                            <textarea class="form-control" id="manifest-remark"  name = "manifest_remark" rows="4" placeholder="For Example: The parcels in this bag are fragile...."></textarea>
                          </div>
                    </div>


                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="driver-name">Driver's Name </label>
                                <input type="text" class="form-control" id="driver-name" name="driver_name">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="driver-phonenumber">Driver's Phone Number </label>
                                <input type="email" class="form-control" id="driver-phonenumber" name="driver_phonenumber">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="truck-platenumber">Truck Plate Number </label>
                                <input type="email" class="form-control" id="truck-platenumber" name="truck_platenumber">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="truck_seal_number">Truck Seal Number </label>
                                <input type="email" class="form-control" id="truck_seal_number" name="truck_seal_number">
                            </div>
                        </div>
                        <input type="hidden" value="" id="waybills" name="waybills">
                        <input type="hidden" value="" id="groups_to_send" name="groups_to_send">
                        <input type="hidden" value="" id="waybills_next_site_id" name="waybills_next_site_id">
                        <input type="hidden" value="" id="manifest_bags" name="manifest_bags">
                        {{-- END DIALOG BODY --}}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" id="dispatch-manifest" type="submit">Dispatch Manifest</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abort Operation</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>



        {{-- END OF CRATE  MANIFEST --}}


        {{-- ------------------------------------------------------- --}}

        {{-- DEPARTURE LIST ------------------------- START --}}




        {{-- DEPARTURE LIST -----END --}}
    @endsection



    @push('scripts')
        <script>
            var start_date;
            var end_date;
            let workingTable;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //--------------------AJAX LOADING INDICATOR-------------
            $(document).on({
                ajaxStart: function() {
                    // console.log("Loading.............");
                    $('#loadingmessage').show();
                },
                ajaxStop: function() {
                    // console.log("Done............");
                    $('#loadingmessage').hide();
                }
            });
            //------------END OF AJAX LOADING INDICATOR-------------



            $('document').ready(function() {



                // if ($('#open-filters').checked) {
                //     $("#filters").show();
                // } else {
                //     $("#filters").hide();
                // }
                // GROUP SEARCHING START


                var filter_start_date;
                var filter_end_date;

                var filter_start =  moment().startOf('day');//.add(10, 'minutes'); //moment().subtract(29, 'days');
                var filter_end = moment().startOf('hour').add(32, 'hour');

                function cb2(filter_start, filter_end) {
                    // $('#search-date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    // console.log(filter_start.format('YYYY-MM-DD hh:mm A'));
                    // console.log(filter_end.format('YYYY-MM-DD hh:mm A'));

                    filter_start_date = filter_start.format('YYYY-MM-DD hh:mm:ss A');
                    filter_end_date = filter_end.format('YYYY-MM-DD hh:mm:ss A');
                }

                var today_filter = moment();

                $('#departure_list_filter_date').daterangepicker({
                    // opens: 'left',
                    timePicker: true,
                    // timePicker24Hour: true,
                    timePickerIncrement: 1,
                    timePickerSeconds: true,
                    startDate: moment().startOf('day'),//.add(10, 'minutes'),
                    endDate: moment().startOf('hour').add(32, 'hour'),
                    minDate: moment().startOf('day'),
                    maxDate: moment().endOf('day').subtract(10, 'minutes'), //subtract(1, 'month')
                    locale: {
                        format: 'hh:mm:ss A'
                    }
                }, cb2).on('show.daterangepicker', function(ev, picker) {
                    picker.container.find(".calendar-table").hide();
                });;

                cb2(filter_start, filter_end);




                //GROUP SEARCH STOP
                let group_type = 'auto'; //Adjust the interface

                // console.log("hello");
                //Implement Sever Side Rendering soon
                workingTable = $('#scan-timestamps-table').DataTable({

                        processing: true,
                        serverSide: true,
                        dom: 'rtBp',
                        buttons: [
                            'print'
                        ],
                        //stateSave: true, research the consequence well
                        bFilter: false,
                        pagingType: 'first_last_numbers',
                        pageLength: 100,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        ajax: {
                            url: "{{ route('getScanTimestamps') }}",
                            data: function(d) {
                                // d.status = $('#status').val(), //$('input[name=start]').val()
                                d.scanner_id = $("#filter_scanner_id").val(),
                                    d.next_site_id = $("#filter_next_site_id").val(),
                                    d.start_date = filter_start_date, //
                                    d.end_date = filter_end_date
                            }
                        },
                        columns: [

                            // {
                            //         data: 'DT_RowIndex',
                            //         name: 'DT_RowIndex',
                            //         orderable: false,
                            //         searchable: false,
                            //     },
                            {
                                data: "next_site_name",
                                name: "next_site_name"
                            },
                            {
                                data: 'start_date',
                                name: 'start_date',
                                orderable: false,
                                searchable: false,
                            }

                            // , {
                            //     data: "seal_number",
                            //     name: 'seal_number'
                            // }
                            // ,
                            // {
                            //     data: "waybills_count",
                            //     name: "waybills_count"
                            // }
                            ,
                            {
                                data: "scanner_name",
                                name: 'scanner_name',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: "tag",
                                name: "tag"
                            }

                            // , {
                            //     data: "next_site.name",
                            //     name: 'next_site.name'
                            // }


                            ,


                            {
                                data: "end_scan",
                                name: "end_scan"
                            }, {
                                data: "action",
                                name: "action"
                            }


                        ]
                    }

                );






                //   ------------------
                // $('#status').change(function() {
                //     workingTable.draw();
                // });

                $('#departure_list_filter_date').change(function() {
                    departureListTable.draw();
                    console.log($("#departure_list_filter_date").val());
                });

                $('#departure_list_filter_scanner_id').change(function() {
                    departureListTable.draw();
                });

                $('#departure_list_filter_site_id').change(function() {
                    departureListTable.draw();
                });
                // $('#scanning-end').hide(); // by default





                var scan_site_id;
                var next_site_id;
                var tag;

                var start = moment().startOf('day');//.add(10, 'minutes'); //moment().subtract(29, 'days');
                var end = moment().endOf('day').subtract(10, 'minutes');
                start_date = start;
                end_date = end;
                function cb(start, end) {
                    // $('#search-date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    // console.log(start.format('YYYY-MM-DD hh:mm:ss A'));
                    // console.log(end.format('YYYY-MM-DD hh:mm:ss A'));

                    start_date = start.format('YYYY-MM-DD hh:mm:ss A');
                    end_date = end.format('YYYY-MM-DD hh:mm:ss A');
                }

                var today = moment();

                $('#group-create-date-range').daterangepicker({
                    opens: 'left',
                    timePicker: true,
                    // timePicker24Hour: true,
                    timePickerIncrement: 1,
                    timePickerSeconds: true,
                    startDate: moment().startOf('day'),//.add(10, 'minutes'),
                    endDate: moment().endOf('day').subtract(10, 'minutes'),
                    minDate: moment().startOf('day'),//.add(10, 'minutes'),
                    maxDate: moment().endOf('day').subtract(10, 'minutes'), //subtract(1, 'month')
                    locale: {
                        format: 'hh:mm:ss A'
                    }
                }, cb).on('show.daterangepicker', function(ev, picker) {
                    picker.container.find(".calendar-table").hide();
                });

                cb(start, end);


                var intervalId = window.setInterval(function() {
                    // cb(start, end);
                    let periodValue = `${moment().format('hh:mm:ss A')} ---------> Later`;
                    $("#auto_period_input").val(periodValue);
                    $("#auto_period_input").css("color", "green");

                    // console.log($("#auto_period_input").val());

                }, 1000);

                function resetParameters() {
                    start_date = null;
                    end_date = null;
                    scan_site_id = null,
                        next_site_id = null,
                        tag = null;


                    $("#next_site_id").val('').trigger('change');
                    $("#scanner_id").val('').trigger('scanner_id');
                    $("#group-tag").val("");
                    $("#seal-number").val("");

                }



                $('#scanning-save-group').click(function() {


                    let create_start_date;
                    let create_end_date;
                    let period_type = $("#period_type").val();

                    if (period_type == "auto_period") {
                        console.log("Save Scanning auto");


                        create_start_date = moment().format(
                            'YYYY-MM-DD hh:mm:ss A'); //----------------get start date --> NOW() for auto

                        create_end_date = null;
                    } else {

                        // console.log("Save Scanning custom");

                        create_start_date = start_date; //date picker stuff;
                        create_end_date = end_date; //date picker stuff

                    }

                    // grouping_type =
                    //     if(this.value == "auto_period")
                    // {
                    // resetParameters()
                    // console.log("Start ", create_start_date, "End ", create_end_date);
                    // return false;

                    let next_site_id = parseInt($("#next_site_id").val()); //---------get next_site_id
                    let scanner_id = parseInt($("#scanner_id").val());
                    tag = $("#group-tag").val(); //-----------------get tag


                    if (isNaN(next_site_id)) {
                        Swal.fire({
                            type: 'error',
                            title: 'Validation Error !',
                            text: 'Please select a next site.',
                        });

                        return false;
                    }

                    if (isNaN(scanner_id)) {

                        scanner_id = null;

                    }

                    // console.log(moment().format('YYYY-MM-DD hh:mm:ss A'));

                    //Validate Remark
                    if ($("#add-group-tag").prop('checked') == true) {

                        if (tag == null) {
                            Swal.fire({
                                type: 'error',
                                title: 'Validation Error !',
                                text: 'Please Enter a remark or uncheck the ADD REMARK CHECKBOX',
                            });

                            return false;
                        }

                    }

                    // console.log(`tag:${tag}, start_time: ${create_start_date}, next site: ${next_site_id}, period type: ${period_type}, scanner: ${scanner_id},
                    //    end date: ${create_end_date}`);

                    // return false;


                    $.ajax({
                            url: `/scan-timestamps`,
                            type: "POST",
                            data: {
                                start_date: create_start_date,
                                end_date: create_end_date,
                                next_site_id: next_site_id,
                                tag: tag,
                                // seal_number: seal_number,
                                scanner_id: scanner_id,
                                period_type: period_type //specify here
                            },
                            dataType: "json",
                        })
                        .done((result) => {
                            console.log(result);
                            if (result['success'] === true) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Operation Successful',
                                    text: `${result['message']}`,
                                });

                                workingTable.draw();
                                $("#modal-group-form").modal('hide');

                            } else {
                                Swal.fire({
                                    type: 'info',
                                    title: 'Sorry, could not save group.. please try again ',
                                    text: `${result['message']}`,
                                });

                            }
                        })
                        .fail(function() {
                            console.log("! Error, Could not save Group");
                            Swal.fire({
                                type: 'error',
                                title: 'Could Not Save ',
                                text: `Could not connect to server!`,
                            });
                        });


                    resetParameters();

                });



                let departureListTable = null;

                $("#get-departure-list").on('click', function(event) {

                    //Clear the Datable
                    $("#departure_list_table").html("");
                    $("#modal-departure-list").modal('show');

                    departureListTable = $('#departure_list_table').DataTable({

                            processing: true,
                            serverSide: true,
                            // dom: 'rtBp',
                            // buttons: [
                            //     'print'
                            // ],
                            //stateSave: true, research the consequence well
                            bFilter: true,
                            pagingType: 'first_last_numbers',
                            pageLength: 1000,
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, 100, 150, 1000] // "All"
                            ],
                            bDestroy: true,
                            ajax: {
                                url: "{{ route('k9_getCurrentDayDepartureListForSite') }}",
                                data: function(d) {
                                        d.scanner_id = $("#departure_list_filter_scanner_id").val(),
                                        d.next_site_id = $("#departure_list_filter_site_id").val(),
                                        d.start_date = filter_start_date, //
                                        d.end_date = filter_end_date
                                }
                            },
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    orderable: false,
                                    searchable: false,
                                },
                                {
                                    data: "BILL_CODE",
                                    name: "BILL_CODE"
                                }
                                ,
                                {
                                    data: "SCAN_DATE",
                                    name: 'SCAN_DATE'
                                }
                                ,
                                {
                                    data: "next_site.SITE_NAME",
                                    name: 'next_site.SITE_NAME'
                                }
                                ,
                                {
                                    data: 'employee.EMPLOYEE_NAME',
                                    name: 'employee.EMPLOYEE_NAME'
                                }


                            ]
                        }

                    );



                });




            });


            /*

                useful
               $("#auto").is(":checked")
               $("#custom").prop("checked", true);

            */
            //Create Group
            $("#scanning-create-group").on('click', function(event) {
                event.preventDefault();

                //Reset Controls
                $("#next_site_id").val('').trigger('change');
                $("#scanner_id").val('').trigger('change');
                $("#period_type").val("auto_period");
                $("#period_type").change(); // manually fires the change event so it can affect the button label
                $('#add-group-tag').prop('checked', false);
                $('#add-group-tag').change();

                $("#modal-group-form").modal('show');
            });


            // function toggleGroupingDateContainer() {
            //     if ($("#auto").is(":checked")) {
            //         $("#group-create-date-range-row").hide();
            //     } else {
            //         $("#group-create-date-range-row").show();

            //     }
            // }

            $("#period_type").on('change', function() {
                //alert( this.value );
                if (this.value == "auto_period") {
                    $("#auto_period_input_row").show();
                    $("#group-create-date-range-row").hide();
                    $("#scanning-save-group").text("Start Grouping");
                } else {
                    $("#scanning-save-group").text("Save Group");
                    $("#auto_period_input_row").hide();
                    $("#group-create-date-range").css("color", "green");
                    $("#group-create-date-range-row").show();
                }
            });

            $('#add-group-tag').on('change',
                function() {
                    if (this.checked) {
                        $("#group-tag").show();
                    } else {
                        $("#group-tag").val("");
                        $("#group-tag").hide();

                    }
                }
            );


            // $("input[name=group_types]").change(function() {
            //     toggleGroupingDateContainer();
            // });




            $("#scan-timestamps-table").on('click', '.end_scan', function(event) {
                // //Stop funny behaviour jor
                // event.preventDefault();
                let group_id = event.target.dataset.group;

                console.log(group_id);

                // START END SCAN


                Swal.fire({
                    title: 'Confirm Operation',
                    text: "Do you want to end this grouping ?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, please end it'
                }).then((result) => {
                    if (result.value === true) {
                        $.ajax({
                                url: `/scan-timestamps/${group_id}/end-scan`,
                                type: "POST",
                                data: {
                                    //YYYY-MM-DD hh:mm:ss A
                                    end_date: moment().add(5, 'seconds').format('YYYY-MM-DD hh:mm:ss A')
                                },
                                dataType: "json",
                            })
                            .done((result) => {
                                // console.log(result);
                                if (result['success'] === true) {
                                    // $("#modal-user-group").modal('hide');
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Operation successful',
                                        text: `${result['message']}`,
                                    });

                                    // window.location.reload();
                                    workingTable.draw();
                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Failed',
                                        text: `${result['message']}`,
                                    });

                                }
                            })
                            .fail(function() {
                                console.log("! Error, Could not end grouping");
                            });

                    } else {
                        // alert("hello");
                    }
                });




            });



            $("#scan-timestamps-table").on('click', '.dispatch', function(event) {
                // //Stop funny behaviour jor
                // event.preventDefault();
                // //Do we even have a waybill to work  on ? check now o
                // let sealNumber = event.target.closest("tr").dataset.info;

                // manifestBags = manifestBags.filter(bag => bag.sealNumber !== sealNumber);

                // populateBagTable(manifestBags);
                let group_id = event.target.dataset.group;

                // console.log("dispatch group ", group_id);

                // ------------------START




                let scanGroups = [parseInt(group_id)];


                $.ajax({
                        url: `/scan-timestamps/getWatbillsForScanGroup`,
                        type: "GET",
                        data: {
                            scan_groups: scanGroups
                        },
                        dataType: "json",
                    })
                    .done((result) => {
                        console.log(result);
                        if (result['success'] === true) {
                            // console.log("here", result);
                            // console.log("log",  result.data);
                            let tableRow = "";
                            waybills = result.data;
                            let groups_to_send = result.group;

                            if (waybills.length < 1) {
                                Swal.fire({
                                    type: 'warning',
                                    title: 'Oops !',
                                    text: 'No record found',
                                });


                                // return false;

                            }

                            Swal.fire({
                                type: 'info',
                                title: 'Record Found',
                                text: `${waybills.length} found!`,
                            });


                            //reset waybills to send to send
                            waybills_to_send = [];
                            // console.log(waybills);
                            waybills.forEach((waybill, index) => {


                                $("#waybills_next_site_id").val(waybill.PRE_OR_NEXT_STATION_CODE);
                                // console.log('Waybill', waybill);
                                // waybills_to_send.push(parseInt(waybill.BILL_CODE));
                                waybills_to_send.push(waybill.BILL_CODE); // na here e dey
                                //dddd, MMMM Do, YYYY
                                tableRow +=
                                    `'<tr data-info="${waybill.BILL_CODE}"><td>${++index}</td><td>${waybill.BILL_CODE}</td><td>${moment(waybill.SCAN_DATE).format('hh:mm:ss A')}</td><td>${waybill.WEIGHT}</td><td>${waybill.SCAN_MAN_CODE}</td></tr>`;

                                // console.log(tableRow);

                            });

                            $("#waybills_table").html(tableRow);
                            $("#groups_to_send").val(JSON.stringify(groups_to_send));
                            $("#number_of_waybills_to_send").text(waybills.length);
                            $("#waybills").val(JSON.stringify(waybills));
                            // $("#waybills").val(JSON.stringify(waybills_to_send));
                            // console.log("manifest remark: ",groups_to_send);
                            //tempora; this can be configurable while creating grouos
                            $("#manifest-remark").val(groups_to_send.tag);


                            //---------------- hide optional fields
                            if (this.checked) {
                                $("#optional-details").show();
                            } else {
                                $("#optional-details").hide();
                            }
                            //"modal-manifest-form
                            $("#modal-manifest-form").modal('show');
                        }
                    })
                    .fail(function() {
                        console.log("! Error, connect to server to get waybills");
                    });



            //         //---------------------- Load the bags-----------

            //         $.ajax({
            //             url: `/bags/getAvailableBagsInSite`,
            //             type: "GET",
            //     // data : {
            //     //     scan_site_id : scan_site_id,
            //     // },
            //     dataType: "json",
            // })
            //     .done((result) => {

            //         console.log(result);
            //         let temp = "";
            //         if(result.success == true)
            //         { //Bags retrieved successfully
            //             console.log(result.data);
            //         let options =
            //             "<option value=''>Select Bag Number</option>";

            //         result.data.forEach(function (bag) {
            //         options += `<option value='${bag.id}'>${bag.code2}</option>`;

            //         });
            //         $("#bag_number").html(options);
            //         }
            //         else { //Could not retrieve bags
            //                 console.log(result.message);
            //         }

            //     })
            //     .fail(function () {
            //         console.log("Could not connect to server, no bags retrived");
            //     });



                //END

            });



            //



            $('#add-optional-details').change(
                function() {
                    if (this.checked) {
                        $("#optional-details").show();
                    } else {
                        $("#optional-details").hide();
                    }
                });




            let waybills = [];
            let waybills_to_send = [];


            //------------------BEGIN DISPATCH MANIFEST---------------
            $("#dispatch-manifest").click(function(event) {

                var form = $("#manifest-create");

                event.preventDefault();


                // console.log(waybills_to_send);
                next_site_id = parseInt($("#waybills_next_site_id").val());
                if (isNaN(next_site_id)) {
                    Swal.fire({
                        type: 'error',
                        title: 'Validation Error !',
                        text: 'Please select a next site.',
                    });

                    return false;
                }

                let seal_number = $("#seal-number").val(); //----------seal number
                if (seal_number == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Validation Error !',
                        text: 'Please Enter a Seal Number',
                    });

                    return false;
                }


                let bag_number = $("#bag_number").val(); //----------seal number
                // console.log("Bag Number is : ", bag_number);
                // if (bag_number == "") {
                //     Swal.fire({
                //         type: 'error',
                //         title: 'Validation Error !',
                //         text: 'Please Select a Bag Number',
                //     });

                //     return false;
                // }



                let shipment_type = $("#shipment_type").val();
                console.log("Shipment type", shipment_type);
                if (shipment_type == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Validation Error !',
                        text: 'Please Select Shipment Type',
                    });

                    return false;
                }

                let transport_type = $("#transport_type").val(); //----------seal number
                if (transport_type == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Validation Error !',
                        text: 'Please Select Transport Type',
                    });

                    return false;
                }




                if (waybills_to_send.length == 0) {
                    Swal.fire({
                        type: 'error',
                        title: 'Validation Error !',
                        text: 'Please add waybills to the parcel bag ',
                        // footer: '<a href>Why do I have this issue?</a>'
                    });

                    return false;
                }

                console.log(this);
                Swal.fire({
                    title: 'Confirm Operation',
                    text: "Do you want to dispatch this manifest ?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value === true) {

                        // alert("I got here");
                        form.submit();
                    } else {
                        // alert("hello");
                    }
                });




            }); //---------------END DISPATCH MANIFEST-----------------




            // {END SCAN}


            // $('#open-filters').change(
            //     function() {
            //         if (this.checked) {
            //             $("#filters").show();
            //         } else {
            //             $("#filters").hide();
            //         }
            //     });



            $("#scan-timestamps-table").on('click', '.cancel_scan', function(event) {
                // //Stop funny behaviour jor
                event.preventDefault();
                let group_id = event.target.dataset.group;

                // console.log(group_id);

                // START END SCAN

                // return false;
                Swal.fire({
                    title: 'Confirm Operation',
                    text: "Do you want to delete this group ?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value === true) {
                        $.ajax({
                                url: `/scan-timestamps/${group_id}/cancel`,
                                type: "POST",
                                data: {
                                    end_date: moment().format('YYYY-MM-DD hh:mm A')
                                },
                                dataType: "json",
                            })
                            .done((result) => {
                                // console.log(result);
                                if (result['success'] === true) {
                                    // $("#modal-user-group").modal('hide');
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Operation successful',
                                        text: `${result['message']}`,
                                    });

                                    // window.location.reload();
                                    workingTable.draw();
                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Failed',
                                        text: `${result['message']}`,
                                    });
                                }
                            })
                            .fail(function() {
                                console.log("! Error, Could not end grouping");
                            });

                    } else {
                        // alert("hello");
                    }
                });
            });


            //Seal Number


            $("#get_seal_number").click(function(event) {
                $.ajax({
                        url: `/manifest/virtual-sealnumber/new`,
                        type: "GET",
                        dataType: "json",
                    })
                    .done((virtualSealnumber) => {
                        console.log("Seal Number : ", virtualSealnumber);
                        $("#seal-number").fadeIn().val(virtualSealnumber);
                    })
                    .fail(function() {
                        console.log("! Error, Could not get virtual Seal Number");
                    });


                // $('#add-virtual-seal-number').modal('hide');

            });
        </script>



    @endpush
