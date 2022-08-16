    <div class="modal fade" id="editSiteModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header alert alert-secondary">
            <h3 id="editSiteModelHeading" class="modal-title"><strong>Edit Site</strong></h3>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
          <form id="editSiteForm" name="editSiteForm" action="javascript:void(0)" class="form-horizontal" method="POST">
              <input type="hidden" name="id" id="id">
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Site name</label>
                    <input type="text" class="form-control" id="name" name="name" value="" required>
                    <span class="text-danger">
                        <strong id="name-error"></strong>
                </div>
                  <div class="form-group col-md-4">
                    <label>Site Type</label>
                    <select id="is_a_franchise" name="is_a_franchise" class="form-control" value="" required>
                        <option disabled>Please Choose...</option>
                            @foreach($site_type as $site_types)
                                    <option value="{{ $site_types->id }}"> {{$site_types->name}} </option>
                            @endforeach
                     </select>
                     <span class="text-danger">
                        <strong id="is_a_franchise-error"></strong>
                    </div>
                  <div class="form-group col-md-4">
                    <label>State</label>
                    <select id="state_id" name="state_id" class="form-control" value="" required>
                        <option disabled>Please Choose...</option>
                               @foreach($state as $states)
                                <option value="{{ $states->id }}"> {{$states->name}} </option>
                            @endforeach
                    </select>
                    <span class="text-danger">
                        <strong id="state_id-error"></strong>
                </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea type="text" class="form-control" id="address" name="address" placeholder="" value="" required></textarea>
                    <span class="text-danger">
                        <strong id="address-error"></strong>
                </div>
             </div>
                <div class="modal-footer">
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitData" value="submit" >Update Site</button>
                </div>
        </form>
        </div>
      </div>
    </div>
