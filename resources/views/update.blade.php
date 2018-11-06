<div class="modal fade bd-example-modal-lg" id="update-task-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<div class="modal-header">
        <h5 class="modal-title">Update Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<div class="modal-body">
				<form id="update-task">
					<div class="form-group">
						<label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter title">
					</div>
					<div class="form-group">
						<label for="description">Description</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="10" placeholder="Add some description"></textarea>
           </div>
                    <input type="hidden" name="id" id="task-id">
				</form>
			</div>
			<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-changes">Save Changes</button>
      </div>
    </div>
  </div>
</div>
