@extends('layouts.app')


@section('content')
<div>Import Results here</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Incoming Manifest</h3>
                    </div>
                    <div class="col text-right">

                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>From</th>
                            <th>TO</th>
                            <th>Status</th>
                            <th>Manifested By</th>

                        </tr>
                    </thead>
                    <tbody class="list">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
