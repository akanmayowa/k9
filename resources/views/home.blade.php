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

// if(Auth::user()->hasanyrole(['Quality Control Personnel']))
// {
// //  $from_attributes['placeholder'] = 'All Sites';

// // $to_attributes[ 'placeholder'] = 'All Sites';

// }
@endphp

@section('content')
<div class="col-12 ptb-4">
     <div class="card">
         <div class="card-body">
          <div class="container-fluid">
           <h2 class="">SITE NAME--><small>ABA ABA</small> </h2>
            <p>pls use the date filter to ............................................. </p>
        <div class="row justify-content-md-center">
          <div class="col col-lg-2 m-2">
            <input type="date" name="start_date" class="form-control mr-5">
          </div>
          <div class="col col-lg-2 m-2">
            <input type="date" name="end_date" class="form-control ml-5">
          </div>
        </div>
    </div>

           <div class="row">
            <div class="col-md-4">
                <div class="card bg-gradient-success border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class=" text-uppercase font-weight-bold mb-0 text-white">number of pickup waybills</span>
                      </div>
                      <div class="col-auto">
                      </div>
                    </div>
                      <label class="mt-3 mb-0 text-uppercase text-white font-weight-bold">Total Pickup Fee: #22</label>
                  </div>
                </div>
              </div>



              <div class="col-md-4">
                <div class="card bg-gradient-primary border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class=" text-uppercase font-weight-bold mb-0 text-white">number of delivered waybills</span>
                      </div>
                      <div class="col-auto">
                      </div>
                    </div>
                      <label class="mt-3 mb-0 text-uppercase text-white font-weight-bold">total delivered amount: #22</label>
                  </div>
                </div>
              </div>


              <div class=" col-md-4">
                <div class="card bg-gradient-default border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class=" text-uppercase font-weight-bold mb-0 text-white">number of cod waybills</span>
                      </div>
                      <div class="col-auto">
                      </div>
                    </div>
                      <label class="mt-3 mb-0 text-uppercase text-white font-weight-bold"> total cod amount: #22</label>
                  </div>
                </div>
              </div>

          </div>



          <div class="row">
            <div class="col-md-4">
                <div class="card bg-gradient-danger border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class=" text-uppercase font-weight-bold mb-0 text-white">TOTAL RETURN</span>
                      </div>
                      <div class="col-auto">
                      </div>
                    </div>
                      <label class="mt-3 mb-0 text-uppercase text-white font-weight-bold">42</label>
                  </div>
                </div>
              </div>



              <div class="col-md-4">
                <div class="card bg-gradient-warning border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class=" text-uppercase font-weight-bold mb-0 text-white">TOTAL DUPLICATE</span>
                      </div>
                      <div class="col-auto">
                      </div>
                    </div>
                      <label class="mt-3 mb-0 text-uppercase text-white font-weight-bold">22</label>
                  </div>
                </div>
              </div>


              <div class="col-md-4">
                <div class="card bg-gradient-info border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class=" text-uppercase font-weight-bold mb-0 text-white">TOTAL INVALID</span>
                      </div>
                      <div class="col-auto">
                      </div>
                    </div>
                      <label class="mt-3 mb-0 text-uppercase text-white font-weight-bold">19</label>
                  </div>
                </div>
              </div>

          </div>

</div>

@endsection


@push('scripts')


<script type="text/javascript">
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
</script>

<script>


    //     $('document').ready(function () {

    //         var start_date;
    //         var end_date;

    //         var start = moment();//moment().subtract(29, 'days');
    //         var end = moment();
    //     function cb(start, end) {
    //         // $('#search-date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    //         // console.log(start.format('YYYY-MM-DD'));
    //         console.log("start_date: " , start.format('YYYY-MM-DD'), " end_date: ", end.format('YYYY-MM-DD'));

    //         start_date = start.format('YYYY-MM-DD');
    //         end_date = end.format('YYYY-MM-DD');
    //     }


    //       $('#search-date').daterangepicker(
    //         {
    //             showDropdowns: true,
    //             timePicker: true,
    //             startDate: start,
    //             endDate: end,
    //             ranges: {
    //             'Today': [moment(), moment()],
    //             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //             'This Month': [moment().startOf('month'), moment().endOf('month')],
    //             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //             }
    // }, cb
    //       );

    //       cb(start, end);

    //       updateDashBoard();
    //     //   ------------------
    //     $('#status').on('change', function(){
    //         updateDashBoard()
    // });

    // $('#scan_site_id').change(function(){
    //     updateDashBoard()
    // });

    // $('#next_site_id').change(function(){
    //     updateDashBoard()
    // });

    // $('#search-date').change(function(){
    //     updateDashBoard()
    // });

    // var intervalId = window.setInterval(function(){
    //     console.log("update dash board");
    //     updateDashBoard(); //change this too one hour later
    //             }, 300000);

    // function updateDashBoard()
    // {
    //     console.log("Update DashBoard");

    //     $.ajax({
    //                 url: `/dashboard`,
    //                 type: "GET",
    //                 data: {
    //                     status : $('#status').val(), //$('input[name=start]').val()
    //                     scan_site_id : $("#scan_site_id").val(),
    //                     next_site_id : $("#next_site_id").val(),
    //                     start_date : start_date,//
    //                     end_date : end_date
    //                     },
    //                 dataType: "json",
    //             })
    //                 .done((result) => {
    //                     console.log(result);
    //                     if(result['success'] === true)
    //                     {
    //                         // console.log(result.data);
    //                         // console.log(result.data.acknowleged_manifest_count);
    //                         // console.log(result.data.dispatched_manifest_count);
    //                         $("#acknowleged_manifest_count").html(result.data.acknowleged_manifest_count);
    //                         $("#partially_acknowleged_manifest_count").html(result.data.partially_acknowleged_manifest_count);
    //                         $("#dispatched_manifest_count").html(result.data.dispatched_manifest_count);
    //                         $("#incoming_manifest_count").html(result.data.incoming_manifest_count);
    //                         $("#partially_acknowleged_waybills_count").html(result.data.partially_acknowleged_waybills_count);
    //                         $("#acknowleged_waybills_count").html(result.data.acknowleged_waybills_count);
    //                         $("#dispatched_waybills_count").html(result.data.dispatched_waybills_count);
    //                         $("#incoming_waybills_count").html(result.data.incoming_waybills_count)
    //                     }
    //                     })
    //                 .fail(function () {
    //                     console.log("! Error, connect to server to get waybills");
    //                 });
    // }


    //     });
    </script>
@endpush
