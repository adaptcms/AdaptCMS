<div id="preview-modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h4 class="modal-title">Preview Article</h4>
			</div>
			<div class="modal-body" style="max-height: 600px;">
				<iframe id="iframe" src="<?php echo $this->webroot ?>" width="100%" height="600px"></iframe>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>
			</div>
		</div>
	</div>
</div>