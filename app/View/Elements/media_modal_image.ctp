<ul class="selected-images thumbnails" data-id="<?php echo !empty($id) ? $id : 'primary' ?>" data-limit="<?php echo !empty($limit) ? $limit : '' ?>">
	<li class="img-container file_info col-lg-4 center" ng-repeat="image in getSelectedImages('<?php echo !empty($id) ? $id : 'primary' ?>')">
		<img ng-src="{{ path }}{{ image.dir }}{{ image.filename }}" ng-show="image.exists" class="thumbnail" ng-click="addImage('<?php echo !empty($id) ? $id : 'primary' ?>', image)">
		<img ng-src="{{ path }}img/no_file.png" ng-show="!image.exists" class="thumbnail" ng-click="addImage(image)">

		<h4>
			{{ image.label }}

			<input name="<?php echo !empty($name) ? $name : 'data[File][{{ $index }}]' ?>" type="checkbox" ng-checked="isSelectedImage('<?php echo !empty($id) ? $id : 'primary' ?>', image.id)" ng-click="addImage('<?php echo !empty($id) ? $id : 'primary' ?>', image)" class="file" ng-value="image.id">
		</h4>

		<em>
			Uploaded {{ image.timestamp | date:'MMMM d yyyy' }}
		</em>
	</li>
</ul>
<div class="clearfix"></div>