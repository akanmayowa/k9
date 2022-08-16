@extends('layouts.app')


@section('content')
<div>Preview your import here</div>
<form class="form-horizontal" action="{{route('processUserImport')}}" method="post" name="upload_excel">

    @csrf
    <!-- Button -->
          <div class="form-group">
            <div class="col-md-4">
                <button type="submit" id="submit" name="run-checks" class="btn btn-primary button-loading" data-loading-text="Loading...">Import Employees</button>
            </div>
        </div>

</form>
    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Employees</h3>
                <p class="text-sm mb-0">
                    List of all company employees
                </p>
            </div>
            <div class="table-responsive py-4">
                <table class="table table-flush" id="users-datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>S/N</th>
                            <th>K9 Account ID</th>
                            <th>Full Name</th></th>
                            <th>Phone</th>
                            <th>Site</th>
                            <th>Departemnt</th>
                        </tr>
                    </thead>
                    <tfoot>
                        @foreach($result[0] as $employee)
                        <tr>
                            <td>{{$loop->index+1}}</td><td>{{$employee['employee_no']}}</td> <td>{{$employee['employee_english']}} </td> <td>{{ $employee['tel'] }} </td> <td>{{ $employee['related_site'] }} </td>  <td>{{ $employee['department'] }} </td> </td>
                        </tr>
                        @endforeach
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>




@endsection
