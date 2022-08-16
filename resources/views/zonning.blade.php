@extends('layouts.app')


@section('content')

    <div class="card">
        <div class="card-header  bg-dark border-0">
           <h3 class="mb-0"><span  style="color:white">LOCATION ZONNINGS</span></h3>
        </div>
        {{-- <div class="col"> --}}

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table align-items-center table-flush table-dark table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>From</th>
                                <th>TO</th>
                                <th>Zone</th>
                            </tr>
                            <tbody  id="location-zonnings-table">

                            </tbody>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    {{-- </div> --}}
</div>
    @endsection


    @push('scripts')
        <script>
  $('document').ready(function () {


                $.ajax({
                        url: `/tarriff/zonnings/list`,
                        type: "GET",
                        dataType: "json",
                    })
                    .done((record) => {

                console.log(record);
                // console.log(record.data.express_tarriffs);
                let tableRow = "";
                record.data.forEach((zonning, index) => {
                            tableRow += `'<tr data-info="${zonning.id}">
                            <td>${zonning.from}</td>
                            <td>${zonning.to}</td>
                            <td>${zonning.zone_id}</td>
                            </tr>`;

                        });

                        $("#location-zonnings-table").html(tableRow);
                    })
                    .fail(function() {
                        console.log("! Error, Could not get location zonning");
                    });

            });




        </script>


    @endpush
