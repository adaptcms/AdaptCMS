<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Create a Plugin', null) ?>

<?php $this->AdaptHtml->script('vendor/angular.min') ?>
<?php $this->AdaptHtml->script('vendor/angular-route.min') ?>
<?php $this->AdaptHtml->script('admin.tools.create_addon') ?>

<h1>Create a Plugin</h1>

<div id="AddonCreator" class="well" ng-app="AddonCreator">
	<div ng-controller="PluginCtrl" ng-cloak>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#basic_info" data-toggle="tab">Basic Info</a></li>
			<li><a href="#skeleton" data-toggle="tab">Startup Files</a></li>
			<li><a href="#versions" data-toggle="tab">Versions</a></li>
			<li><a href="#overview" data-toggle="tab">Overview</a></li>
		</ul>

		<div ng-view></div>
	</div>
</div>