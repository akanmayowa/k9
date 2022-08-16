{{-- modal-group-form --}}
  <!-- Modal -->
  <div class="modal fade" id="modal-group-form" tabindex="-1" role="dialog" aria-labelledby="modal-group-form" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-errorLabel">Create Manifest</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-errorBody">
            {{--   <div class="modal-body" id="modal-errorBody"> --}}
         <div class="form-group text-center">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="group_types" id="auto" value="auto" checked>
                <label class="form-check-label" for="auto">Auto</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="group_types" id="custom" value="custom">
                <label class="form-check-label" for="custom">Custom</label>
              </div>
         </div>
            {{-- <div class="form-group">
                <label for="search-date">Date</label>
                <input type="text" class="form-control" id="search-date" name="search-date">
            </div> --}}

            <div class="row">            {{-- Start Row --}}
                <div class="col-sm-6">
                <div class="form-group">
                    <label class="form-control-label" for="next_site_id"> Next Site</label>
                    {{ Form::select('create_next_site_id', $site_list, null, [
                        'id' => 'next_site_id',
                        'class' => 'form-control next_site_id',
                        'data-toggle' => 'select',
                        'placeholder' => 'Select Next Site',
                        'required' => true,
                    ]) }}
                    <div class="text-danger">
                        @error('next_site_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                </div>
                <div class="col-sm-6">
                <div class="form-group">
                    <label class="form-control-label" for="scanner_id"> Scanner ID</label>
                    {{ Form::select('scanner_id', $site_users, null, [
                        'id' => 'scanner_id',
                        'class' => 'form-control scanner_id',
                        'data-toggle' => 'select',
                        'placeholder' => 'Select Scanner',
                        'required' => true,
                    ]) }}
                    <div class="text-danger">
                        @error('scanner_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                </div>
            </div>
            <div class="form-group">
                <label for="driver-name">Seal Number <span
                        class="optional-field badge badge-warning">unique</span></label>
                <input type="text" class="form-control" id="seal-number" name="seal-number">
            </div>
            <div class="form-group">
                <label for="group-tag">Add remark ? </label> <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="add-group-tag" value="true" checked>
                    {{-- <label class="form-check-label" for="inlineCheckbox1">1</label> --}}
                  </div>
                <textarea class="form-control" id="group-tag" rows="3"></textarea>
              </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="scanning-save-group">Save</button>
      </div>
    </div>
  </div>
  </div>
