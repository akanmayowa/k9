@extends('layouts.app')
@section('title', 'SpeedafUtility - Create Manifest');
@section('content')
    <div class="row">
        <div class="col">
            <div class="card-wrapper">
                <!-- Custom form validation -->
                <div class="card">
        <!-- Card header -->
        <div class="card-header bg-dark border-0">
            <div class="row">
              <div class="col-6">
                <h3 class="mb-0"><span  style="color:white">CREATE MANIFEST</span></h3>
              </div>
              <div class="col-6 text-right">
                {{-- <a href="#" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Edit Manifest">
                  <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                  <span class="btn-inner--text">Export</span>
                </a> --}}
              </div>
            </div>
          </div>
                    <!-- Card body -->
                    <div class="card-body">
                        {!! Form::open(['route' => 'storeManifest', 'id' => 'manifest-create', 'class' => 'needs-validation', 'novalidate' => true]) !!}
                       <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-control-labe text-darker" for="next_site_id"> Next Site</label>
                                    {{ Form::select('next_site_id', $site_list, null, [
                                        'id' => 'next_site_id',
                                        'class' => 'form-control next_site',
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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label text-darker" for="group_id"> Scan Group         <a class="text-red" href="#!" id="show-search-form"  data-original-title="Refresh Group List">
                                        {{-- (refresh groups) --}}
                                         </a></label>
                                    {{ Form::select('group_id', [], null, [
                                        'id' => 'group_id',
                                        'class' => 'form-control next_site',
                                        'data-toggle' => 'select',
                                        'placeholder' => 'Select Group',
                                        'required' => true,
                                    ]) }}
                                    <div class="text-danger">
                                        @error('group_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mt-4">
                                    <a class="btn btn-secondary text-red" href="#!" id="show-scan-groups"  data-original-title="Custom Search">
                                      Refresh Scan Group
                                      </a>
                                </div>
                            </div>
                       </div>
                       <div class="row">

                   </div>
                        {{-- Try and rap this table with a row and column tag --}}

                        <div class="table-responsive" style="max-height: 400px; min-height:250px">
                            <table id="waybills-table"
                                class="table-dark table align-items-center table-flush table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Waybill Number</th>
                                        <th>Next Site</th>
                                        <th>Scan Date</th>
                                        <th>SCANNER</th>
                                        {{-- <th></th> --}}
                                    </tr>
                                </thead>
                                <tbody id="waybills_table">
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="transport_type_id"> Transport Type </label>
                                    {{ Form::select('transport_type_id',  config('custom.transport_type', ['Air', 'Shuttle', '3rd Party', 'others']), null, [
                                            'id' => 'transport-type-id',
                                            'class' => 'form-control transport-type',
                                            'data-toggle' => 'select',
                                            'placeholder' => 'Select transport type',
                                            'required' => true,
                                        ]) }}
                                    <div class="text-danger">
                                        @error('transport_type_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="driver-name">Driver's Name </label>
                                    <input type="text" class="form-control" id="driver-name" name="driver_name">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="driver-phonenumber">Driver's Phone Number </label>
                                    <input type="email" class="form-control" id="driver-phonenumber"
                                        name="driver_phonenumber">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="truck-platenumber">Truck Plate Number</label>
                                    <input type="email" class="form-control" id="truck-platenumber"
                                        name="truck_platenumber">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="number_of_bags">Numbers Of Bags </label>
                                    <input type="number" class="form-control" id="number-of-bags" name="number_of_bags">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="truck_seal_number">Truck Seal Number </label>
                                    <input type="email" class="form-control" id="truck_seal_number"
                                        name="truck_seal_number">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="text-default mt-3">Bags( <span id="numbers_of_bags" class="text-red">0</span> )
                                    <a href="#!" id="show-manifest-bag"  data-original-title="Add bag">
                                        <i class="fas fa-plus text-default"></i>
                                      </a>

                                </div>
                            <div class="table-responsive"  style="max-height: 210px; min-height:50px" >
                                <table id="bags-table" class="table-light table align-items-center table-flush table-striped">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>S/N</th>
                                      <th>Shiptment Type</th>
                                      <th>Seal Number</th>
                                      <th>Quantity</th>
                                      <th></th>

                                    </tr>
                                  </thead>
                                  <tbody id="bags_table_body">


                               </tbody>
                                </table>
                              </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Senders Note</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="7"></textarea>
                              </div>
                        </div>
                            {{-- End Row --}}
                        </div>
                        {{-- Total parcels in Wyabills Table, Total Parcels in Bags Table --}}
                        <input type="hidden" value="" id="waybills" name="waybills">
                        <input type="hidden" value="" id="manifest_bags" name="manifest_bags">
                        {{-- <div class="col text-center">
                        <button class="btn btn-warning" id= "dispatch-manifest" type="submit">Dispatch Manifest</button>
                      </div> --}}
                        <hr>

                        <div class="row mt-8 text-center">
                            <div class="col">
                                <button class="btn btn-warning" id="dispatch-manifest" type="submit">Dispatch
                                    Manifest</button>
                            </div>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-waybills-in" tabindex="-1" role="dialog" aria-labelledby="modal-waybills-in" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal-errorLabel">Paste Waybill Numbers Here</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="modal-errorBody">

                <div class="form-group">
                    <label for="waybills-in">Waybill Numbers</label>
                    <textarea class="form-control" id="waybills-in" rows="3"></textarea>
                  </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="get-waybills-in">Get Waybills</button>
          </div>
        </div>
      </div>
      </div>


      <!-- Modal -->
  <div class="modal fade" id="modal-manifest-bag-form" tabindex="-1" role="dialog" aria-labelledby="modal-manifest-bag-form" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-errorLabel">Add Bag</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-errorBody">
            <div class="form-group">
                <label class="form-control-label" for="shipment_type"> Shipment Type</label>
                {{ Form::select('shipment_type',config('custom.shipment_type', ['Forward', 'Reverse']), null, [
                    'id' => 'shipment_type',
                    'class' => 'form-control shipment_type',
                    'data-toggle' => 'select',
                    'placeholder' => 'Select Shipment Type',
                    'required' => true,
                ]) }}
            </div>



            <div class="form-group">
                <label for="seal_number">Seal Number <span
                        class="optional-field badge badge-warning">unique</span></label>
                <input type="text" class="form-control" id="seal_number" name="seal_number">
            </div>

            <div class="form-group">
                <label for="number_of_waybills">Number of waybills </label>
                <input type="number" class="form-control" id="number_of_waybills" name="number_of_waybills">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="add-manifest-bag">Save</button>
      </div>
    </div>
  </div>
  </div>

    <!-- Modal -->
    {{-- <div class="modal fade" id="modal-search-waybills-form" tabindex="-1" role="dialog" aria-labelledby="modal-search-waybills-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal-errorLabel">Custom Waybill Search</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="modal-errorBody">
                <div class="row">

            </div>
            <ul class="list-group list-group-hover" id="scan-group">

            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="get-scan-group-waybills">Get Waybills</button>
          </div>
        </div>
      </div>
      </div> --}}



  <!-- Modal -->
  <div class="modal fade" id="modal-search-form" tabindex="-1" role="dialog" aria-labelledby="modal-search-form" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-errorLabel">Scan Group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-errorBody">
            {{-- STRAT --}}




                <div class="form-group">
                    <label for="search-date">Date</label>
                    <input type="text" class="form-control" id="search-date" name="search-date">
                </div>


                <div class="form-group">
                    <label class="form-control-label" for="scanner_id">Scanned By</label>
                    {{ Form::select('scanner_id', $site_users, null, [
                        'id' => 'scanner_id',
                        'class' => 'form-control',
                        'data-toggle' => 'select',
                        'placeholder' => 'Select Scanner',
                        'required' => true,
                    ]) }}
                    <div class="text-danger">
                        @error('scanner_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <button class="btn btn-secondary" id="search-departure-scans">
                    <i class="fas fa-search text-warning"></i> SEARCH
                </button>


            {{-- END --}}

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="scanning-save-manual">Save</button>
      </div>
    </div>
  </div>
  </div>
@endsection

{{-- @push('scripts')
    <script>
        $('document').ready(function () {
            //Implement Sever Side Rendering soon
          let workingTable =  $('#waybills-table').DataTable({
        dom: 'Bfrtip',
        buttons:
        [
                    {
                        extend: 'copy',
                        exportOptions: {
                    columns: [ 0, 1, 3 ]
                }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                    columns: [ 0, 1, 3 ]
                }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                    columns: [ 0, 1, 3 ]
                }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                    columns: [ 0, 1, 3 ]
                }
                    }
                ]
    });
        });
    </script>
@endpush --}}








@push('scripts')
    <script>
        //----------------BEGIN AJAX SET UP-------------
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); //------------------END OF AJAX SETUP


        //--------------------AJAX LOADING INDICATOR-------------
        $(document).on({
            ajaxStart: function() {
                console.log("Loading.............");
                $("#search-departure-scans").hide();
                $("#ajax-loading-indicator").show();
            },
            ajaxStop: function() {
                console.log("Done............");
                $("#search-departure-scans").show();
                $("#ajax-loading-indicator").hide();
            }
        });
        //------------END OF AJAX LOADING INDICATOR-------------

        let waybills = [];
        let waybills_to_send = [];

        //------------------BEGIN DISPATCH MANIFEST---------------
        $("#dispatch-manifest").click(function(event) {

            var form = $("#manifest-create");

            event.preventDefault();


            // console.log(waybills_to_send);
            next_site_id = parseInt($("#next_site_id").val());
            if (isNaN(next_site_id)) {
                Swal.fire({
                    type: 'error',
                    title: 'Validation Error !',
                    text: 'Please select a next site.',
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


        $("#next_site_id").on('change', function() {
            let next_site_id = parseInt($("#next_site_id").val());
            if (isNaN(next_site_id)) {
                // Swal.fire({
                //     type: 'error',
                //     title: 'Validation Error !',
                //     text: 'Please select a next site.',
                // });

                return false;
            }



            $.ajax({
                url: `/scan-timestamps/list`,
                type: "GET",
                data : {
                    next_site_id : next_site_id,
                },
                dataType: "json",
            })
                .done((result) => {

            //         console.log("Get the scan groups for " + next_site_id);
            // return false;
                    let temp = "";
                    if(result.success == true)
                    {
                        console.log(result.data);
                    let options =
                        "<option value='0'>Select Group</option>";

                    result.data.forEach(function (scan_timeststamp) {
                    options += `<option value='${scan_timeststamp.id}'>${scan_timeststamp.tag}</option>`;

                    });
                    $("#group_id").html(options);
                    }
                    else {
                        console.log("No groups found");
                                // Swal.fire({
                                // type: 'info',
                                // title: 'Oops!',
                                // text: 'Could not retrieve data from server',
                                // // footer: '<a href>Why do I have this issue?</a>'
                                // });

                    }

                })
                .fail(function () {
                    console.log("! Error, Could not connect to k9 server to get scan groups");
                    // Swal.fire({
                    //             type: 'error',
                    //             title: 'Server Error',
                    //             text: 'Could not connect to server',
                    //             });
                });

        });


        $("#show-scan-groups").on('click', function(event){
            event.preventDefault();

            let next_site_id = parseInt($("#next_site_id").val());
            if (isNaN(next_site_id)) {
                Swal.fire({
                    type: 'error',
                    title: 'Validation Error !',
                    text: 'Please select a next site.',
                });

                return false;
            }


                $.ajax({
                url: `/scan-timestamps/list`,
                type: "GET",
                data : {
                    next_site_id : next_site_id,
                },
                dataType: "json",
            })
                .done((result) => {

            //         console.log("Get the scan groups for " + next_site_id);
            // return false;
                    let temp = "";
                    if(result.success == true)
                    {
                        console.log(result.data);
                    let options =
                        "<option value='0'>Select Group</option>";

                    result.data.forEach(function (scan_timeststamp) {
                    options += `<option value='${scan_timeststamp.id}'>${scan_timeststamp.tag}</option>`;

                    });
                    $("#group_id").html(options);
                    }
                    else {
                        console.log("No groups found");
                                // Swal.fire({
                                // type: 'info',
                                // title: 'Oops!',
                                // text: 'Could not retrieve data from server',
                                // // footer: '<a href>Why do I have this issue?</a>'
                                // });

                    }

                })
                .fail(function () {
                    console.log("! Error, Could not connect to k9 server to get scan groups");
                    // Swal.fire({
                    //             type: 'error',
                    //             title: 'Server Error',
                    //             text: 'Could not connect to server',
                    //             });
                });


            // $("#modal-scan-groups").modal('show');
        });

        $("#group_id").on('change', function() {
            event.preventDefault();

            let next_site_id = parseInt($("#next_site_id").val());
            if (isNaN(next_site_id) || next_site_id === 0) {

                console.log("next_site_id: " , next_site_id);
                return false;
            }

            //Reset the waybills table
            $("#waybills_table").html("");
            console.log("next_site_id: " , next_site_id);
            console.log("I have change");
           console.log($("#group_id").val());

           let scanGroups = [parseInt($("#group_id").val())];
           $.ajax({
                    url: `/scan-timestamps/getWatbillsForScanGroup`,
                    type: "GET",
                    data: {scan_groups : scanGroups},
                    dataType: "json",
                })
                    .done((result) => {
                        // console.log(result);
                        if(result['success'] === true)
                        {
                            console.log(result);
                   let tableRow = "";
                    waybills = result.data;

                    if (waybills.length < 1) {
                        Swal.fire({
                            type: 'warning',
                            title: 'Oops !',
                            text: 'No record found',
                        });


                        return false;

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


                            // console.log('Waybill', waybill);
                            // waybills_to_send.push(parseInt(waybill.BILL_CODE));
                            waybills_to_send.push(waybill.BILL_CODE);
                            tableRow +=
                                `'<tr data-info="${waybill.BILL_CODE}"><td>${++index}</td><td>${waybill.BILL_CODE}</td><td>${waybill.PRE_OR_NEXT_STATION_CODE}</td><td>${waybill.REGISTER_DATE}</td><td>${waybill.SCAN_MAN_CODE}</td></tr>`;

                            // console.log(tableRow);

                        });

                        $("#waybills_table").html(tableRow);
                        $("#numberofparcels").text(waybills.length);
                        $("#waybills").val(JSON.stringify(waybills_to_send));

                        }
                    })
                    .fail(function () {
                        console.log("! Error, connect to server to get waybills");
                    });


        });

        $("#get-scan-group-waybills").on('click', function(event){
                console.log("Scan Groupwaybills");


            event.preventDefault();
            scanGroups = [];
            $('input[type="checkbox"]:checked').each(function() {
                scanGroups.push(parseInt($(this).val()));
            });


            if(scanGroups.length == 0)
            {
                console.log("Nothing to get");
            }
            else
            {
                console.log("Kindly get waybills for  ", scanGroups);
                 // return false;
                $.ajax({
                    url: `/scan-timestamps/getWatbillsForScanGroup`,
                    type: "GET",
                    data: {scan_groups : scanGroups},
                    dataType: "json",
                })
                    .done((result) => {
                        // console.log(result);
                        if(result['success'] === true)
                        {
                            console.log(result);
                   let tableRow = "";
                    waybills = result.data;

                    if (waybills.length < 1) {
                        Swal.fire({
                            type: 'warning',
                            title: 'Oops !',
                            text: 'No record found',
                        });


                        return false;

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


                            // console.log('Waybill', waybill);
                            // waybills_to_send.push(parseInt(waybill.BILL_CODE));
                            waybills_to_send.push(waybill.BILL_CODE);
                            tableRow +=
                                `'<tr data-info="${waybill.BILL_CODE}"><td>${++index}</td><td>${waybill.BILL_CODE}</td><td>${waybill.PRE_OR_NEXT_STATION_CODE}</td><td>${waybill.REGISTER_DATE}</td><td>${waybill.SCAN_MAN_CODE}</td></tr>`;

                            // console.log(tableRow);

                        });

                        $("#waybills_table").html(tableRow);
                        $("#numberofparcels").text(waybills.length);
                        $("#waybills").val(JSON.stringify(waybills_to_send));

                        }
                    })
                    .fail(function () {
                        console.log("! Error, connect to server to get waybills");
                    });
            }
        });


        $("#show-manifest-bag").on('click', function(event) {
            event.preventDefault();


            $("#shipment_type").val(1);
            $("#seal_number").val("");
           $("#number_of_waybills").val("");
            $("#modal-manifest-bag-form").modal('show');
        });




        let manifestBags = [];
        $("#add-manifest-bag").on('click', function(event) {
            event.preventDefault();


            let shipmentType = $("#shipment_type").val();
            let sealNumber = $("#seal_number").val();
            let numberOfWaybills = $("#number_of_waybills").val();


           bag =  manifestBags.find(bag => bag.sealNumber === sealNumber);

            if(bag != null)
            {

                Swal.fire({
                        type: 'error',
                        title: 'Operation failed!',
                        text: 'Bag already exist in the table',
                    });
                return false;
            }

            //add
            console.log(shipmentType, sealNumber, numberOfWaybills);

            manifestBags.push ({
                sealNumber: sealNumber,
                shipmentType: shipmentType,
                numberOfWaybills: numberOfWaybills
                });

                $("#bags_table_body").html("");
                populateBagTable(manifestBags);

            //Reset
            $("#modal-manifest-bag-form").modal('hide');
        });


        function populateBagTable(bags)
        {
            let tempTd ="";
                bags.forEach((bag, index) => {
                    tempTd += `'<tr data-info="${bag.sealNumber}"><td>${++index}</td><td>${bag.shipmentType}</td><td>${bag.sealNumber}</td><td>${bag.numberOfWaybills}</td><td><a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="Delete product">
                                            <i class="fas fa-trash text-red remove-item"></i>
                                        </a></td></tr>`;

                    console.log(tempTd);

                });


                $("#bags_table_body").html(tempTd);
                $("#numbers_of_bags").text(manifestBags.length);
                $("#manifest_bags").val(JSON.stringify(manifestBags));
                $('#modal-manifest-bag-form').modal('hide');
        }




        $("#bags_table_body").on('click', '.remove-item', function(event){
            //Stop funny behaviour jor
            event.preventDefault();
            //Do we even have a waybill to work  on ? check now o
            let sealNumber = event.target.closest("tr").dataset.info;

            manifestBags = manifestBags.filter(bag => bag.sealNumber !== sealNumber);

            populateBagTable(manifestBags);

        });





    </script>
@endpush
