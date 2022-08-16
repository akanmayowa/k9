@extends('layouts.app')


@section('content')

    <div class="card">
        <div class="card-header  bg-dark border-0">
            <h3 class="mb-0"><span  style="color:white">K9 TRACK</span></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-control-label text-darker" for="waybill_number">WAYBILL NUMBER(s)</label>
                        {{ Form::text('waybill_number', null, [
'id' => 'waybill_number',
'class' => 'form-control',
'placeholder' => 'Enter Waybill(s) to track here',
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

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-control-label text-danger" for="waybills_to_view">Filter Your Tracked Results Here </label>
                        {{ Form::select('waybills_to_view',[], null, [
                                'id' => 'waybills_to_view',
                                'class' => 'form-control',
                                'data-toggle'=>"select",
                                // 'placeholder' => 'Select Waybill to view',
                                'required' => true
                        ]) }}
                        <div class="text-danger">
                            @error('waybills_to_view')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <table class="table align-items-center table-flush table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>SCAN SITE</th>
                        <th>SCAN TYPE</th>
                        <th>Desciption</th>
                        <th>Scan Date</th>
                        <th>Scan Time</th>
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


$scan_records = [];
$site_records = [];
scanner_records = [];

$('#waybills_to_view').change(function(){

console.log('test this', $(this).val());



let scans_to_show = scan_records.filter(x => x.BILL_CODE == $(this).val());
console.log(scans_to_show);
displayScans(scans_to_show);

});

function displayScans(scans_to_show)
{

let tableRow = "";
scans_to_show.forEach((waybill, index) => {




    let scan_type_name = "";
    let description = "";
    let courier = "";


    let scan_site = $site_records.find(x => x.id == waybill.SCAN_SITE_CODE);
    // console.log('Scan_man_code', waybill.SCAN_MAN_CODE);
    let scanner = scanner_records.find(x => x.id == waybill.SCAN_MAN_CODE);
    courier =  scanner_records.find(x => x.id == waybill.DISPATCH_OR_SEND_MAN_CODE);
    console.log('precord',  waybill.PRE_OR_NEXT_STATION_CODE);
    let $next_site =  $site_records.find(x => x.id == waybill.PRE_OR_NEXT_STATION_CODE);
    let next_site_name = null;
    if($next_site != null)
    {
        $next_site_name = $next_site.name;
    }
    if(waybill.SCAN_TYPE_CODE == 1)
    {
        scan_type_name = "<span class='badge badge-light'>PICKED UP</span>";
        description = `<span class='text-red'><b>【${scan_site.name}】</b></span> has done pickup scan,<br>
operator is <span class='text-primary'><b>【${scanner.name}】</b></span>,<br/>
courier is  <span class='text-success'><b>【${courier.name}】`;
    }
    else if(waybill.SCAN_TYPE_CODE==2)
    {
        scan_type_name = "<span class='badge badge-primary'>DEPARTED</span>";
        description = `
        Waybill is keeping at <span class='text-red'><b>【${scan_site.name}】</b></span> ,<br/> is departing for<span class='text-success'><b>【${$next_site_name}】</b></span>,<br>
operator is <span class='text-primary'><b>【${scanner.name}】</b></span>
        `;
    }
    else if(waybill.SCAN_TYPE_CODE== 3)
    {
        scan_type_name = "<span class='badge badge-dark text-white'>ARRIVED</span>";
        description = `
        Arrived at <span class='text-red'><b>【${scan_site.name}】</b></span>,Last site is <span class='text-success'><b>【${$next_site_name}】</b></span>,<br/>Operator is <span class='text-primary'><b>【${scanner.name}】</b></span>
        `;
    }
    else if(waybill.SCAN_TYPE_CODE==4)
    {
        scan_type_name = "<span class='badge badge-info text-white'>DELIVERED</span>";
        description = `
        【${scan_site.name}】has done delivery scan，<br/> Operator is <span class='text-primary'><b>【${scanner.name}】</b></span> ，Courier is <span class='text-success'><b>【${courier.name}】</span>
        `;
    }
    else if(waybill.SCAN_TYPE_CODE==5)
    {
        scan_type_name = "<span class='badge badge-default text-white'>RETURNED</span>";
        description = `Waybill returned by <span class='text-primary'><b>【${scanner.name}】</b></span> <br/> Reason is <span class='badge badge-default text-white'><b>${waybill.RETURN_REASON}</b></span>`;
    }
    else if(waybill.SCAN_TYPE_CODE==6)
    {
        scan_type_name = "<span class='badge badge-success'>COLLECTED</span>";
        description = `
        has been【Signed】,<br/>Recipient is【${waybill.SIGN_MAN}】,<br/>Delivery site is  <span class='text-red'><b>【${scan_site.name}】</b></span> ,<br/>

Entry time【${waybill.SCAN_DATE}】,<br/>Courier is <span class='text-primary'><b>【${waybill.RECORD_MAN}】</b></span>
        `;
    }
    else if(waybill.SCAN_TYPE_CODE==7)
    {
        scan_type_name = "<span class='badge badge-danger'>ISSUE PARCEL</span>";
        description = `Done by <span class='text-primary'><b>【${scanner.name}】</b></span> <br/> Reason is <span class='badge badge-warning'><b>${waybill.PROBLEM_CAUSE}</b></span>`;
    }
    else
    {
        scan_type_name = "Name not assigned";

    }



    // console.log('site', $scan_site_name);
    // console.log('log', )

    tableRow +=  `<tr data-info="${waybill.BILL_CODE}"><td><span class='text-darker'>${ scan_site.name}</span></td><td>${scan_type_name}</td><td class="text-darker">${description}</td><td><div class="text-darker">${moment(waybill.SCAN_DATE).format('YYYY-MM-DD')}</div></td><td><div class="text-darker">${moment(waybill.SCAN_DATE).format('hh:mm:ss A')}</div></td></tr>`;
                  });
// console.log(scan_records);
       $("#track-waybill-table").html(tableRow);
}

            $("#track_waybill").on('click', function(event) {
                event.preventDefault();

                let waybill_number = $("#waybill_number").val();

                // console.log(waybill_number);


                if (waybill_number == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Validation Error !',
                        text: 'Please enter waybill number to track.',
                    });

                    return false;
                }


                $("#waybill_number").val('');

                $.ajax({
                        url: `/trackOnK9`,
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
                        scan_records = result.data.scans;
                        $site_records = result.data.sites;
                        scanner_records = result.data.scanners;

                         displayScans(result.data.scans);
                        // return;

                        let options = null;
                        result.data.tracked_waybills.forEach((waybill, index) => {
                             options += `<option value='${waybill}'>${waybill}</option>`;
                        });

                        $("#waybills_to_view").html(options);



                    })
                    .fail(function() {
                        console.log("! Error, Could not get scan records");
                    });

            });
        </script>


    @endpush
