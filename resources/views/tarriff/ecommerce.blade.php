@extends('layouts.app')
@section('content')
<div class="card-header">
    <h3 class="mb-0"><span style="color:black"> ECOMMERCE TARRIFF</span></h3>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-control-label">
                <select type="number" name="weight_start" id="weight_start" data-toggle="select" required tabindex="-1"
                    aria-hidden="true">
                    <option value="" selected >All</option>
                    @foreach ($ecommerce_tariff as $ecommerce_tariffs)
                    <option value="{{floatval($ecommerce_tariffs->weight_start)}}">
                        {{floatval($ecommerce_tariffs->weight_start)}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <table class="table align-items-center table-flush table-striped ecommerce-tarriffs">
                <thead class="thead-light">
                    <tr>
                        <th>Weight</th>
                        <th>Zone 1</th>
                        <th>Zone 2</th>
                        <th>zone 3</th>
                        <th>Zone 4</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="card-footer text-muted"> </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.ecommerce-tarriffs').DataTable({
            processing: true,
            serverSide: true,
            bPaginate: true,
            bFilter: false,
            ajax: {
                url: '{{ url("/tarriffs/ecommerce/listV2") }}',
                data: function (d) {
                    d.weight_start = $('#weight_start').val();
                }
            },
            columns: [{
                    data: 'weight_start',
                    name: 'weight_start'
                },
                {
                    data: 'zone_1_cost',
                    name: 'zone_1_cost'
                },
                {
                    data: 'zone_2_cost',
                    name: 'zone_2_cost'
                },
                {
                    data: 'zone_3_cost',
                    name: 'zone_3_cost'
                },
                {
                    data: 'zone_4_cost',
                    name: 'zone_4_cost'
                },
            ]
        });
    });
    $('#weight_start').on('change', function () {
        $('.ecommerce-tarriffs').DataTable().draw(true);
    });
</script>
@endpush
