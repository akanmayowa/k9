@extends('layouts.app')


@section('content')
<div id="wrap">
    <div class="container">
        <div class="row">
            <form class="form-horizontal" action="{{route('previewUserImport')}}" method="post" name="upload_excel" enctype="multipart/form-data">
               @csrf
                <fieldset>
                    <!-- Form Name -->
                    <legend>Import Users</legend>
                    <!-- File Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="filebutton">Select File</label>
                        <div class="col-md-4">
                            <input type="file" name="file" id="file" class="input-large">
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="singlebutton">Import data</label>
                        <div class="col-md-4">
                            <button type="submit" id="submit" name="run-checks" class="btn btn-primary button-loading" data-loading-text="Loading...">Start Process</button>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>
        <?php

        ?>
    </div>
</div>
@endsection
