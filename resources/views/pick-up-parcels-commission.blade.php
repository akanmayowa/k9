@inject('ManifestStatus', '\App\Enums\ManifestStatus')
@section('title', 'Test Page')
@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
@php
@endphp
@section('content')
<div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row">
                  <div class="col-6">
                    <h3 class="mb-0"><span  style="color:dark">PICKUP</span></h3>
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
                            <label for="search-date">Period: </label>
                            <input type="text" class="form-control" id="search-date" name="search-date" >
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label" for="site_id"> Site: </label>
                            @php
                                $site_attributes = [
                                        'id' => 'site_id',
                                        'class' => 'form-control site_id',
                                        'data-toggle'=>"select",
                                        // 'placeholder' => 'All Sites',
                                        'required' => true
                        ];

                            if(Auth::user()->hasanyrole(['Quality Control Personnel']))
                             {
                                 $from_attributes['placeholder'] = 'All Sites';

                                $to_attributes[ 'placeholder'] = 'All Sites';

                             }
                            @endphp
                                {{ Form::select('site', $sites, null, $site_attributes ) }}
                        </div>
                    </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="form-control-label" for="cod">COD / Prepaid</label>
                                {{ Form::select('status', ['PREPAID', 'COD'], null, [
                                    'id' => 'cod',
                                    'class' => 'form-control',
                                    'data-toggle' => 'select',
                                    'placeholder' => 'All',
                                    'required' => true,
                                ]) }}
                                <div class="text-danger">
                                    @error('status')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                  </div> {{-- End Rows --}}
                  <div class="row">            {{-- Start Row --}}



                  </div> {{-- End Rows --}}
            </div>


<!--Basic Bootstrap Tab start -->
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"><a class="nav-link active" href="#bootstrap-home" aria-controls="home" role="tab" data-toggle="tab">Default</a></li>
    <li class="nav-item"><a class="nav-link" href="#bootstrap-about" aria-controls="about" role="tab" data-toggle="tab">Fixed</a></li>
    <li class="nav-item"><a class="nav-link" href="#bootstrap-work" aria-controls="work" role="tab" data-toggle="tab">Scrollable</a></li>
</ul>

<div class="card pmd-card">
    <div class="card-body">
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tablist" aria-labelledby="home-tab" class="tab-pane fade show active" id="bootstrap-home">
                <div class="table-responsive">
                  <table class="table align-items-center table-flush table-bordered table-hover" id="waybills-table">
                    <thead class="thead-light">
                      <tr>
                        <th>S/N</th>
                        <th>Waybill Number</th>
                        <th>Pickup Date</th>
                        <th>Pickup Site</th>
                        <th>Weight</th>
                        <th>Zone</th>
                        <th>Freight</th>
                        <th>COD</th>
                        <th>Transfer Fee</th>
                        <th>Delivery Fee</th>
                        <th>Total D+F</th>
                        <th>Pickup Commission</th>

                        {{-- <th>Commission</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            {{-- <th></th> --}}
                        </tr>
                    </tfoot>
                  </table>
                </div>

            </div>
            <div role="tablist" aria-labelledby="home-tab" class="tab-pane fade" id="bootstrap-about">

                Fixed tabs have equal width, calculated either as the view width divided by the number of tabs, or based on the widest tab label. To navigate between fixed tabs, touch the tab or swipe the content area left or right.

            </div>
            <div role="tablist" aria-labelledby="home-tab" class="tab-pane fade" id="bootstrap-work">To navigate between scrollable tabs, touch the tab or swipe the content area left or right. To scroll the tabs without navigating, swipe the tabs left or right.</div>
        </div>
    </div>
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
<script  src="https://cdn.datatables.net/fixedheader/3.2.1/js/dataTables.fixedHeader.min.js"></script>
 <script src="https://cdn.datatables.net/fixedcolumns/4.0.1/js/dataTables.fixedColumns.min.js"></script>
<script  src="https://cdn.datatables.net/v/bs4/dt-1.11.4/datatables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$(document).ajaxStop(function(){
     $.unblockUI();
 });
 $(document).ajaxStart(function(){
     $.blockUI({
         message:'<div class="backgroundColor:lightorange pt-2"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span><strong>....Loading</strong></span></div>',
         css: { 'border-radius':'20px', border: '1px solid #a00', backgroundColor: 'orange', color: '#fff', height: '80px'}
    });
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
            var start = moment().startOf('month');//.add(10, 'minutes'); //moment().subtract(29, 'days');
            var end = moment();
        function cb(start, end) {
            // $('#search-date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            // console.log(start.format('YYYY-MM-DD'));
            console.log("start_date: " , start.format('YYYY-MM-DD HH:mm:ss'), " end_date: ", end.format('YYYY-MM-DD HH:mm:ss'));
            start_date = start.format('YYYY-MM-DD HH:mm:ss');
            end_date = end.format('YYYY-MM-DD HH:mm:ss');
        }
          $('#search-date').daterangepicker(
            {
                showDropdowns: true,
                // timePicker: true,
                // timePickerSeconds: true,
                startDate: start,
                endDate: end,
                locale: {
                        format: 'YYYY-MMM-D'
                    }
    }, cb
          );
          cb(start, end);
            //Implement Sever Side Rendering soon
          let workingTable =  $('#waybills-table').DataTable(
            {
                "headerCallback": function( thead, data, start, end, display ) {
                console.log('calling ...: ' + data[display]);
                // $(thead).find('th').eq(1).html( 'Displaying '+(end-start)+' records' );
            },
            "footerCallback": function( tfoot, data, start, end, display ) {
                var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
         pickupFeeTotal = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 10 ).footer() ).html(
                '<span>&#8358;</span>'+pickupFeeTotal
            );

                                 // Total over all pages
         pickupFeeGrandTotal = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 11 ).footer() ).html(
                '<span>&#8358;</span>'+pickupFeeGrandTotal
            );
  },

//   'rowCallback': function(row, data, index){
//     $(row).find('td:eq(9)').css('background-color', '#495057');
//     $(row).find('td:eq(8)').css('background-color', '#495057');
//     $(row).find('td:eq(7)').css('background-color', '#495057');
//     $(row).find('td:eq(10)').css('background-color', '#495057');
//     },

                colReorder: true,
                fixedHeader: true,
                "processing": true,
                "language": {
                processing: ''
                 },
                serverSide: true,
                footer: true,
                // responsive: true,
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
                    url:  "{{ route('commission.pickup') }}",

                    data: function(d){
                        d.cod = $('#cod').val(), //$('input[name=start]').val()
                        d.site_id = $("#site_id").val(),
                        d.start_date = start_date,//
                        d.end_date = end_date
                    },
                    error:function(x, error, y){
                console.log(x.responseJSON.message);
                // console.log(error);
                // console.log(y);
                // debugger;
            }
                },
                fixedHeader:{
                header: true,
                footer: true
                },
                fixedColumns:{
               left: 2,
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    { data: "waybill_number", name: "waybill_number" },
                    { data: "pickup_date", name: "pickup_date" },
                    { data: "pickup_site", name: "pickup_site" },
                    { data: "weight", name: "weight" },
                    {
                         data: "zone",
                        name: "zone",
                        // width: '20%',
                         },
                    { data: "freight", name: "freight" },
                    { data: "cod", name: "cod" },
                    { data: "transfer_fee", name: "transfer_fee" },
                    { data: "delivery_fee", name: "delivery_fee" },
                    { data: "delivery_fee_plus_transfer_fee", name: "delivery_fee_plus_transfer_fee" },
                    { data: "pickup_fee", name: "pickup_fee" },
                    // ,
                    // { data: "commission", name: "commission" }

                ]
            }

          );

        //   $('#waybills-table tbody')
        // .on( 'mouseenter', 'td', function () {
        //     var colIdx = table.cell(this).index().column;

        //     $( table.cells().nodes() ).removeClass( 'highlight' );
        //     $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
        // } );


        //   ------------------
    $('#cod').change(function(){
            cod = parseInt($("#cod").val());
            console.log('cod changed: '+ cod);
            workingTable.draw();
    });

    $('#site_id').change(function(){
            workingTable.draw();
    });

    // $('#next_site_id').change(function(){
    //         workingTable.draw();
    // });

    $('#search-date').change(function(){
            workingTable.draw();
    });

    // $('#created_by').change(function(){
    //         workingTable.draw();
    // });




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
