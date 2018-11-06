<div class="modal fade bd-example-modal-lg" id="new-task-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<div class="modal-header">
        <h5 class="modal-title">Create New Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<div class="modal-body">
				<form id="new-task">
					<div class="form-group">
						<label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter title">
                        <small class="title-error text-danger"></small>
					</div>
					<div class="form-group">
						<label for="description">Description</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="10" placeholder="Add some description"></textarea>
                        <small class="description-error text-danger"></small>
           </div>
				</form>
			</div>
			<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-new-task">Save New Task</button>
      </div>
    </div>
  </div>
</div>
