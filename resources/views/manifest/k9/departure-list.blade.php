@extends('layouts.app')


@section('content')
<div class="card">
    <!-- Card header -->
    <div class="card-header border-0">
      <div class="row">
        <div class="col-6">
          <h3 class="mb-0"><span  style="color:coral">DEPARTED WAYBILLS</span></h3>
        </div>
        <div class="col-6 text-right">
          {{-- <a href="#" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Edit Manifest">
            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
            <span class="btn-inner--text">Export</span>
          </a> --}}
        </div>
      </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        <form action="" id="filtersForm">
            <div class="input-group">
            <input type="text" name="from-to" class="form-control mr-2" id="date_filter">
            <span class="input-group-btn">
                <input type="submit" class="btn btn-primary" value="Filter">
            </span>
            </div>
        </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 m-3">
            <div class="form-group">
                <label class="form-control-label" for="time">Departure DATE</label>
                {{ Form::text('start_date', null, [
                    'id' => 'start_date',
                    'class'=>'form-control datepicker',
                    'placeholder'=>'Select Start Date',
                 ]) }}

                <button type="button" class="btn btn-info">Search</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label><strong>Scan Site :</strong></label>
                <select id='dropdown-scan-site' class="form-control" style="width: 200px">
                    <option value="2341">DC-LOS</option>
                    <option value="234124">LOS-CN1</option>
                </select>
            </div>
            <div class="form-group">
                <label><strong>Next Site :</strong></label>
                <select id='dropdown-next-site' class="form-control" style="width: 200px">
                    <option value="234111">ABV-NIPOST</option>
                    <option value="234103">LOS-CV</option>
                </select>
            </div>
            <div class="form-group col-lg-5">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control datepicker-autoclose" placeholder="Please select start date">
              </div>
              <div class="form-group col-lg-5">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control datepicker-autoclose" placeholder="Please select end date">
              </div>
        </div>
    </div>

    <!-- Light table -->
    <div class="table-responsive">
        <table class="table align-items-center table-flush table-striped table-dark" id="waybills-table">
            <thead class="thead-light">

              <tr>
                <th>BILL CODE</th>
                <th>SCAN SITE NAME</th>
                <th>SCAN DATE</th>
                <th>NEXT SITE NAME</th>
                <th>SCANNER</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
    </div>
  </div>


  <div class="modal fade" id="modal-waybill-track">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" >WAYBILL ( <span id="modal-waybill-id"></span>)</h4>
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
                      <th>DATE</th>
                      <th>SCAN</th>
                      <th>SCAN SITE</th>
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
{{--   BILL_CODE, SCAN_DATE,
            SCAN_TYPE_CODE,
            T3.EMPLOYEE_NAME,
            T2.SITE_NAME AS NEXT_SITE_NAME, T1.SITE_NAME AS SCAN_SITE_NAME, EMPLOYEE_NAME --}}

@push('scripts')
    <script>
        $('document').ready(function () {

//             let start = moment().subtract(29, 'days');
//   let end = moment();

            let start = moment();
            let end = moment();


            $('#date_filter').daterangepicker(
                {
      "showDropdowns": true,
      "showWeekNumbers": true,
      "alwaysShowCalendars": true,
      startDate: start,
      endDate: end,
      locale: {
          format: 'YYYY-MM-DD', //MMMM Do YYYY April 25th 2021 - May 24th 2021
          firstDay: 1,
      },
      ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
          'This Year': [moment().startOf('year'), moment().endOf('year')],
          'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
          'All time': [moment().subtract(30, 'year').startOf('month'), moment().endOf('month')],
      }
  }
            );


            //Implement Sever Side Rendering soon
          let workingTable =  $('#waybills-table').DataTable({

                processing: true,
                serverSide: true,
                ajax: {
                    url :  "{{route("waybills.k9_getDepartedWaybills")}}",
                    data: function (data) {

                        // Read values
                        var next_site = $('#dropdown-next-site').val();
                        var scan_site = $('#dropdown-scan-site').val();

                        // Append to data
                        data.next_site = next_site;
                        data.scan_site = scan_site,
                        data.search = $('input[type="search"]').val()
                        }
                },
                columns: [
            {data: 'BILL_CODE'},
            {data: 'SCAN_SITE_CODE', name: 'SCAN SITE'},
            {data: 'SCAN_DATE'},
            {data: 'PRE_OR_NEXT_STATION_CODE'},
            {data: 'SCAN_MAN_CODE'}

        ]
        ,
        pageLength : 100,
        dom: 'lBfrtip',
                buttons: [
                    // 'colvis',
                    'excel',
                    'print',
                    'pdf'
                ]

    });


    $('#dropdown-next-site').change(function(){
        workingTable.draw();
  });
        });
    </script>
@endpush


@push('scripts')
    <script>
$("#waybills-table").on('click', ".track-waybill", function(event){
    event.preventDefault();

    let site_id = event.target.closest("tr").dataset.destinationSite;
    console.log(site_id);
    // $.ajax({
    //     url: `/sites/${site_id}`,
    //     type: "GET",
    //     dataType: "json",
    // })
    //     .done((result) => {

    //          console.log(result);
    //          $("#modal-destination-site").text(result['data'][0].name);
    //          let site_supervisors = result['data'][0].users;
    //          let tempTd = "";

    //         //  console.log(site_supervisors);
    //         site_supervisors.forEach((employee, index) => {
    //             tempTd += `'<tr><td>${++index}</td><td>${employee.id}</td><td>${employee.first_name}</td><td>${employee.phone_number}</td></tr>`;

    //         });

    //         $("#site-employees-table").html(tempTd);

    //          $("#modal-view-destination-site").modal('show');

    //     })
    //     .fail(function () {
    //         console.log("! Error, Could not retrieve site Information");
    //     });


});

</script>
@endpush
