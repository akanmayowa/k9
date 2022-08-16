@extends('layouts.app')


@section('content')

    <div class="card">
        <div class="card-header">
            SITE MANIFEST COMPLIANCE PAGE
        </div>
        {{-- <div class="col"> --}}

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table align-items-center table-flush table-dark table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>S/N</th>
                                <th>Site Name</th>
                                <th>Departed / Dispatched</th>
                                <th>Departure Discrepancy</th>
                                <th>Arrival / Acknowledged</th>
                                {{-- <th>Percentage</th> --}}
                                <th>Arrival Discrepancy</th>
                            </tr>
                            <tbody  id="manifest-compliance-table">

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
                        url: `/get-manifest-compliance`,
                        type: "GET",
                        dataType: "json",
                    })
                    .done((record) => {

                                // foreach($sites as $site)
        // {
        //     $current_site_dispatch_record = $dispatch_record->where('scan_site_id', $site->id)->first();
        //     $current_site_departure_record = $k9_departure_record->where('SCAN_SITE_CODE', $site->id)->first();

        //     // echo "Name: $site->name, K9X: ".optional($current_site_dispatch_record)->total.", K9: ".optional($current_site_departure_record)->total ."\n";
        // }
                        //if success ?
                console.log(record);
                console.log(record.data.sites);
                        let sites = record.data.sites;
                        let dispatch_record = record.data.dispatch_record;
                        let k9_departure_record = record.data.k9_departure_record;
                        let acknowledged_record = record.data.acknowledged_record;
                        let k9_arrival_record = record.data.k9_arrival_record;
                        let tableRow = "";
                        sites.forEach((site, index) => {
                            console.log('site id: ', site.id);
                            // $current_site_dispatch_record = $dispatch_record->where('scan_site_id', $site->id)->first();
                        let current_site_dispatch_record = dispatch_record.find(x => x.scan_site_id === site.id);
                        let current_site_departure_record = k9_departure_record.find(x => parseInt(x.SCAN_SITE_CODE) === site.id);


                        //The accknwoledged count is not correct
                        let current_site_acknowledged_record = acknowledged_record.find(x => x.next_site_id === site.id);
                        let current_site_arrival_record = k9_arrival_record.find(x => parseInt(x.SCAN_SITE_CODE) === site.id);

                        let percentage = getPercentage(getProp(current_site_arrival_record), getProp(current_site_acknowledged_record));
                        let arrival_discrepancy = getDiscrepancy(getProp(current_site_arrival_record), getProp(current_site_acknowledged_record));
                        let departure_discrepancy = getDiscrepancy(getProp(current_site_departure_record), getProp(current_site_dispatch_record));
                        //     $current_site_departure_record = $k9_departure_record->where('SCAN_SITE_CODE', $site->id)->first();
                            tableRow +=
                                `'<tr data-info="${site.id}"><td>${++index}</td><td>${site.name}</td><td>${getProp(current_site_departure_record)} / ${getProp(current_site_dispatch_record)}</td><td>${departure_discrepancy}</td><td> ${getProp(current_site_arrival_record)}/ ${getProp(current_site_acknowledged_record)}</td><td>${arrival_discrepancy}</td></tr>`;

                            // console.log(tableRow);

                        });

                        $("#manifest-compliance-table").html(tableRow);
                    })
                    .fail(function() {
                        console.log("! Error, Could not get scan records");
                    });

            });

            function getProp(val) {
   if (val == null) {
     return 0;
   }
   return parseInt(val.total);
}


function getDiscrepancy(number1, number2)
{

    return (number1 - number2);
}

function getPercentage(number1, number2)
{
    if(number2 < 1)
    {
        console.log("number", number2);
        return 0;
    }

    console.log("numnbers are " , number1," " ,number2);
    return ((number1/number2) * 100);
}
        </script>


    @endpush
