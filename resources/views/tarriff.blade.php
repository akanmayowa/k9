@extends('layouts.app')


@section('content')

    <div class="card">
        <div class="card-header  bg-dark border-0">
            <h3 class="mb-0"><span  style="color:white">  EXPRESS TARRIFF</span></h3>
        </div>
        {{-- <div class="col"> --}}

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                   <p class="display-4 text-green"> Last Updated :11th of Jan 2022</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table align-items-center table-flush table-dark table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Weight</th>
                                <th>Zone 1</th>
                                <th>Zone 2</th>
                                <th>zone 3</th>
                                <th>Zone 4</th>
                            </tr>
                            <tbody  id="express-tarriffs-table">

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
                        url: `/tarriffs/express/list`,
                        type: "GET",
                        dataType: "json",
                    })
                    .done((record) => {

                console.log(record);
                // console.log(record.data.express_tarriffs);
                let tableRow = "";
                record.data.forEach((tarriff, index) => {
                    tableRow += `'<tr data-info="${tarriff.id}"><td>${parseFloat(tarriff.weight_start)}</td><td>${numberWithCommas(tarriff.zone_1_cost_in_cents * 100)}</td><td>${numberWithCommas(tarriff.zone_2_cost_in_cents * 100)}</td><td>${numberWithCommas(tarriff.zone_3_cost_in_cents * 100)}</td><td> ${numberWithCommas(tarriff.zone_4_cost_in_cents * 100)}</td></tr>`;

                        });

                        $("#express-tarriffs-table").html(tableRow);
                    })
                    .fail(function() {
                        console.log("! Error, Could not get scan records");
                    });

            });



            function numberWithCommas(x) {
    return `#${x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")}`;
}


        </script>


    @endpush
