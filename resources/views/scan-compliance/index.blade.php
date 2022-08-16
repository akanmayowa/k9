@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col">
        <div class="card-wrapper">
            <!-- Custom form validation -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">IMPORT WAYBILL(s) FOR COMPLIANCE CHECK</h3>
                </div>
                <!-- Card body -->
                <div class="card-body">
                            <form class="form-horizontal" action="/scan-compliance" method="post" name="upload_excel" enctype="multipart/form-data">
               @csrf
                <fieldset>
                    <!-- Form Name -->
                    {{-- <legend>Import Waybills</legend> --}}
                    <!-- File Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="filebutton">Select File</label>
                        <div class="col-md-4">
                            <input type="file" name="file" id="file" class="input-large">
                        </div>
                    </div>

                        {{-- <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFileLang" lang="en">
                            <label class="custom-file-label" for="customFileLang">Select file</label>
                        </div> --}}

                    <!-- Button -->
                    <div class="form-group">
                        {{-- <label class="col-md-4 control-label" for="singlebutton">Import data</label> --}}
                        <div class="col-md-4">
                            <button type="submit" id="submit" name="run-checks" class="btn btn-primary button-loading" data-loading-text="Loading...">Preview</button>
                        </div>
                    </div>

                </fieldset>
            </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
