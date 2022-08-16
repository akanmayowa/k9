@extends('layouts.app')
@section('title', 'SpeedafUtility - Dispatch Manifest');


@section('content')

<?php
// $siteList = [];

?>
<div class="row justify-content-center">
    <div class="col">
        <div class="card-wrapper">
            <!-- Custom form validation -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0 text-warning">GROUP WAYBILLS<span class="text-right text-green" id="ajax-loading-indicator" style="display:none">Test Loading <i class="fas fa-spinner text-dark fa-pulse fa-2x"></i> </span> </h3>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    {!! Form::open(['route' => 'storeManifest', 'id' => 'manifest-create', 'class' => 'needs-validation', 'novalidate' => true]) !!}
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-control-label" for="waybill_number"><i class="fas fa-truck ml-1 text-warning"></i> Means of Movement</label>
                                {{ Form::select('means_of_movement_id', ['Air', 'Shuttle', 'Third-Party', 'Unscpecified'], null, [
                                    'id' => 'means_of_movement_id',
                                    'class' => 'form-control',
                                    'data-toggle'=>"select",
                                    'placeholder' => 'Select Transportation Means',
                                    'required' => true
                              ]) }}
                            </div>

                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-white bg-gray mt-3 p-2">Toal Parcels in Bag ( <span id="numberofparcels" class="text-white">0</span> )
                                <a href="#!" class="text-white"  data-toggle="modal" data-target="#modal-addparcels" class="" data-toggle="tooltip" data-original-title="Add Parcels">
                                    <i class="fas fa-paste text-warning ml-6"></i> Paste
                                  </a>
                                  {{-- <a href="#!" class="text-white" data-toggle="modal" data-target="#modal-importparcels" class="" data-toggle="tooltip" data-original-title="import Parcels">
                                    <i class="fas fa-file-excel text-success ml-6"> </i> Import
                                  </a> --}}

                            </div>


                            <div class="table-responsive"  style="max-height: 250px; min-height:250px" >
                                <table class="table-light table align-items-center table-flush table-striped">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>S/N</th>
                                      <th>Waybill Number</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                  <tbody id="addtobagbody">
                               </tbody>
                                </table>
                              </div>
                        </div>
                    </div>

                    <input type="hidden" value="" id="waybills" name="waybills">
                    <div class="col text-center">
                        <button class="btn btn-warning" id= "dispatch-manifest" type="submit">Dispatch Manifest</button>
                        <button class="btn btn-secondary">Save For Later</button>
                      </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>



	<!-- The Modal -->
    <div class="modal fade" id="modal-addparcels">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add Parcels to Bag</h4>
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

                <div class="alert alert-warning" role="alert">
                    <strong class="text-uppercase">Warning!</strong> Have you done K9 DEPARTURE SCAN on these ways Yet ?
                    if No, Kindly depart the Waybills on K9 before preceeding with manifest departure.
                </div>

                <form id="addtobag-form">
                <div class="form-group">
                    <label for="parcels">Parcels</label>
                    <textarea class="form-control" id="parcels" rows="3" required></textarea>
                  </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <input type="submit"  id="addtobag" class="btn btn-primary" value="Add To Bag">
            </div>
        </form>
          </div>
        </div>
      </div>

      <!-- The Modal -->
    <div class="modal fade" id="modal-importparcels">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Import Parcels</h4>
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

                <div class="alert alert-warning" role="alert">
                    <strong class="text-uppercase">Warning!</strong> Have you done K9 DEPARTURE SCAN on these ways Yet ?
                    if No, Kindly depart the Waybills on K9 before preceeding with manifest departure.
                </div>

                <form class="form-horizontal" action="" id="importExcelForm" method="post" name="upload_excel" enctype="multipart/form-data">
                    @csrf
                     <fieldset>
                         <!-- Form Name -->
                         {{-- <legend>Import Sites</legend> --}}
                         <!-- File Button -->
                         {{-- <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" lang="en">
                                <label class="custom-file-label" for="file">Select file</label>
                            </div>
                         </div> --}}

                         <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Select File</label>
                            <div class="col-md-4">
                                <input type="file" name="excelFile" id="importWaybillfile" class="input-large">
                            </div>
                        </div>
                         <!-- Button -->
                         {{-- <div class="form-group">
                             <label class="col-md-4 control-label" for="singlebutton">Import data</label>

                                 <button type="submit" id="submit" name="run-checks" class="btn btn-primary button-loading" data-loading-text="Loading...">Start Process</button>

                         </div> --}}

                     </fieldset>
                 </form>
            </div>
 <!-- Preview-->
 <div id='preview'></div>
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <input type="submit"  id="import" class="btn btn-primary" value="Add To Bag">
            </div>

          </div>
        </div>
      </div>


      	<!-- The Modal -->
    <div class="modal fade" id="add-virtual-seal-number">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Virtual Seal Number</h4>
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

                <div class="alert alert-warning" role="alert">
                    <strong class="text-uppercase">Warning!</strong> This option should only be used if the
                    physical seal is not available
                </div>

                {{-- <form id="addtobag-form">
                <div class="form-group">
                    <label for="parcels">Parcels</label>
                    <textarea class="form-control" id="parcels" rows="3" required></textarea>
                  </div> --}}
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary"  id="continue">Continue</button>
              {{-- <button type="submit"  id="continue" class="btn btn-primary" value="Continue"> --}}
            </div>
        {{-- </form> --}}
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

    let lines = [];
    let linesToSend = [];
$("#addtobag-form").submit(function (event) {
    console.log(this);

//          //Stop funny behaviour jor
//          event.preventDefault();
//   event.stopPropagation();
    let tempTd ="";
	var val = $.trim($("#parcels").val());

		if (val != "") {
            let isError = false;
            let raw_input = val.replace(/\r\n/g,"\n").split("\n");
            lines2 = raw_input.filter(function(line)
            {
                //I intentionally used if use in the code below in other to be more explicit
                //A return would have been sufficient
                if(line.length === 14 && /^\d+$/.test(line)) //is waybill length not 14 lol?
                {
                    return true;
                }
                else
                {
                    alert("Error ti de o");
                    isError = true;
                    return false;
                }
            });

            if(isError){
                console.log("Error ti de");
                return false;
            }
            lines = Array.from(new Set(lines.concat(lines2)));
            // console.log(lines);
            lines.forEach((waybill_number, index) => {
            linesToSend.push(parseInt(waybill_number));
                // console.log(waybill_number);
                tempTd += `'<tr data-info="${waybill_number}"><td>${++index}</td><td>${waybill_number}</td><td><a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="Delete product">
                                        <i class="fas fa-trash text-red remove-item"></i>
                                      </a></td></tr>`;

                // console.log(tempTd);

            });

            $("#addtobagbody").html(tempTd);
            $("#numberofparcels").text(lines.length);
            $("#waybills").val(JSON.stringify(linesToSend));
            // $("#waybills").val(JSON.stringify(lines));
            $('#modal-addparcels').modal('hide');
            return false;
		}
});


$("#addtobagbody").on('click', '.remove-item', function(event){
            //Stop funny behaviour jor
            event.preventDefault();
            //Do we even have a waybill to work  on ? check now o
            let waybill = event.target.closest("tr").dataset.info;
            // //Remove passenger from passenger-List
            // list2 = list2.filter(p => p.seat_number !== passenger.seat_number);

            //This line behaves differently in paste & impo
            lines = lines.filter(waybill_number => waybill_number !== waybill);

            let tempTd ="";
            lines.forEach((waybill_number, index) => {
                tempTd += `'<tr data-info="${waybill_number}"><td>${++index}</td><td>${waybill_number}</td><td><a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="Delete product">
                                        <i class="fas fa-trash text-red remove-item"></i>
                                      </a></td></tr>`;

                // console.log(tempTd);

            });


            $("#addtobagbody").html(tempTd);
            $("#numberofparcels").text(lines.length);
            $("#waybills").val(JSON.stringify(lines));
            // //free up the passenger seat
            // selectedSeats = selectedSeats.filter(s => s !== passenger.seat_number);
            // //Refresh the display
            // displayPassengerList();

            console.log(waybill, lines);
        });



        function populateParcelsTable()
        {
            let tempTd ="";
            lines.forEach((waybill_number, index) => {
                tempTd += `'<tr data-info="${waybill_number}"><td>${++index}</td><td>${waybill_number}</td><td><a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="Delete product">
                                        <i class="fas fa-trash text-red remove-item"></i>
                                      </a></td></tr>`;

                // console.log(tempTd);

            });


            $("#addtobagbody").html(tempTd);
        }


        $("#modal-addparcels").on('show.bs.modal', function(){
//             Swal.fire({
//   title: 'Error!',
//   text: 'Do you want to continue',
//   icon: 'error',
//   confirmButtonText: 'Cool'
// })
            $("#parcels").val(""); // empty the text edit
        //   console.log("Edit mode" , editMode);

        // populateSeatCombox();
});



$("#dispatch-manifest").click(function(event)
{


    // console.log($("#destination_site_id").val());
    destination_site_id = parseInt($("#destination_site_id").val());
//<option selected="selected" value="">Select Destination Site</option>
// $('#destination_site_id option:first-child').attr("selected", "selected");

// $('#destination_site_id')[0].selectedIndex = 0;
//console.log(parseInt($("#destination_site_id").val()));

//return false;
    if(isNaN(destination_site_id))
    {
        Swal.fire({
            icon: 'Error',
            title: 'Validation Error !',
            text: 'Please select a destination site.',
            footer: '<a href>Why do I have this issue?</a>'
            });

            return false;
    }

    seal_number = parseInt($("#seal-number").val());
    if(isNaN(seal_number))
    {
        Swal.fire({
            icon: 'Error',
            title: 'Validation Error !',
            text: 'Please enter a seal number.',
            footer: '<a href>Why do I have this issue?</a>'
            });

            return false;
    }


    means_of_movement_id = parseInt($("#means_of_movement_id").val());
    if(isNaN(means_of_movement_id))
    {
        Swal.fire({
            icon: 'Error',
            title: 'Validation Error !',
            text: 'Please select a mean of movement ',
            footer: '<a href>Why do I have this issue?</a>'
            });

            return false;
    }

    // means_of_movement_id = parseInt($("#means_of_movement_id").val());
    // if(isNaN(means_of_movement_id))
    // {
    //     Swal.fire({
    //         icon: 'Error',
    //         title: 'Validation Error !',
    //         text: 'Please select a mean of movement ',
    //         footer: '<a href>Why do I have this issue?</a>'
    //         });

    //         return false;
    // }


    if(linesToSend.length == 0)
    {
        Swal.fire({
            icon: 'Error',
            title: 'Validation Error !',
            text: 'Please add waybills to the parcel bag ',
            footer: '<a href>Why do I have this issue?</a>'
            });

            return false;
    }


//     Swal.fire({
//   title: 'Are you sure?',
//   text: "You won't be able to revert this!",
//   icon: 'warning',
//   showCancelButton: true,
//   confirmButtonColor: '#3085d6',
//   cancelButtonColor: '#d33',
//   confirmButtonText: 'Yes, Dispatch this manifest!'
// }).then((result) => {
//     console.log(result);
//   if (result) {
//     Swal.fire(
//       'Dispatched!',
//       'Manifest has been Succesfully dispatched.',
//       'success'
//     )
//   }
// })

// $.ajax({
//                 url: `/manifest`,
//                 type: "POST",
//                 data: {
//                     destination_site_id : destination_site_id,
//                     seal_number : seal_number,
//                     means_of_movement_id : means_of_movement_id,
//                     waybills : linesToSend


//                     },
//                 dataType: "json",
//             })
//                 .done((result) => {
//                     console.log("Ajax Response : ", result);
//                     if(result.success == true)
//                     {
//                             Swal.fire(
//                             'Dispatched!',
//                             result.message,
//                             'success'
//                             );

//                             // $("#destination_site_id").val("")
//                             // $("destination_site_id").text("");
//                     }
//                     else {
//                         Swal.fire({
//             icon: 'Error',
//             title: 'Dispatched Failed',
//             text: result.message,
//             footer: '<a href>Why do I have this issue?</a>'
//             });

//                     }
//                     // $("#seal-number").fadeIn().val(virtualSealnumber);
//                 })
//                 .fail(function () {
//                     console.log("!Oops, Could not connect to server");
//                 });
// event.preventDefault();
// event.stopPropagation();
// return false;
});


$("#import").click(function (event)
 {
    var form = $('#importExcelForm')[0];
    var data = new FormData(form);
    data.append('file', "Testing");
    //  form = document.getElementById("importExcelForm");
//      for(var pair of data.entries()) {
//    console.log(pair[0]+ ', '+ pair[1]);
// }

// return false;
    //  console.log();
    // var file_data = $('#importWaybillfile').prop('file');
    // var form_data = new FormData();
    // form_data.append('file', file_data);
    // console.log(form_data);
    // alert(form_data);

    //  return false;

     console.log(this);
         $.ajax({
                url: `/manifest/importDispatchedWaybills`,
                type: "POST",
                dataType: "json",
                enctype: 'multipart/form-data',
                data: data,
                contentType: false,
                cache: false,
                processData:false,
            })
                .done((result) => {
                    lines = Array.from(new Set(lines.concat(result['waybills'])));
                    let tempTd ="";
            // console.log(lines);
            lines.forEach((waybill_number, index) => {
            //linesToSend.push(parseInt(waybill_number));
                // console.log(waybill_number);
                tempTd += `'<tr data-info="${waybill_number}"><td>${++index}</td><td>${waybill_number}</td><td><a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="Delete product">
                                        <i class="fas fa-trash text-red remove-item"></i>
                                      </a></td></tr>`;

                // console.log(tempTd);
                console.log(result['formData']);

            });

            $("#addtobagbody").html(tempTd);
            $("#numberofparcels").text(lines.length);
            // $("#waybills").val(JSON.stringify(linesToSend));
            // $("#waybills").val(JSON.stringify(lines));
            $('#modal-importparcels').modal('hide');
                    console.log("File Contents: ", result);

                })
                .fail(function () {
                    console.log("! Error, Could not import waybills");
                });


    // $.ajax({
    //             url: `/manifest/virtual-sealnumber/new`,
    //             type: "GET",
    //             dataType: "json",
    //         })
    //             .done((virtualSealnumber) => {
    //                 console.log("Seal Number : ", virtualSealnumber);
    //                 $("#seal-number").fadeIn().val(virtualSealnumber);
    //             })
    //             .fail(function () {
    //                 console.log("! Error, Could not get virtual Seal Number");
    //             });


    // $('#add-virtual-seal-number').modal('hide');

});

$("#continue").click(function (event)
 {
    $.ajax({
                url: `/manifest/virtual-sealnumber/new`,
                type: "GET",
                dataType: "json",
            })
                .done((virtualSealnumber) => {
                    console.log("Seal Number : ", virtualSealnumber);
                    $("#seal-number").fadeIn().val(virtualSealnumber);
                })
                .fail(function () {
                    console.log("! Error, Could not get virtual Seal Number");
                });


    $('#add-virtual-seal-number').modal('hide');

});

$('#ajax-loading-indicator')
    .hide()  // Hide it initially
    .ajaxStart(function() {
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    })
;


</script>
@endpush
