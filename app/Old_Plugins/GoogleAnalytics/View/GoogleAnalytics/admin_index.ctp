<?php if (!empty($text)): ?>
	<h2>
		Google Analytics Plugin Setup
	</h2>

	<p>Welcome to the Google Analytics Plugin! Setup is easy and fast. First, be sure you have setup a google analytics account and if not - 
		<?= $this->Html->link('sign up here', 'http://www.google.com/analytics/', array(
			'target' => '_blank'
		)) ?>
	</p>
	<p>If you do have an account, then head over to the 
		<?= $this->Html->link('plugin settings', array(
			'plugin' => null,
			'controller' => 'plugins',
			'action' => 'settings',
			'GoogleAnalytics'
		)) ?>. Here you can enter your google analytics email, password and profile ID. The third part is not clear, so take a look at the image below:
	</p>

	<p>
		<?= $this->Html->image('GoogleAnalytics.google-analytics-example-url.png') ?>
	</p>

	<p>
		You will see a similar URL after logging in and selecting your website. An 'a' followed by some numbers, a 'w' followed by numbers and 'p' followed by numbers. The numbers after the 'p', as shown above, is the profile ID for your website. Once you enter that in, feel free to chmod the configuration file (located at <?= APP . '/Plugin/GoogleAnalytics/Config/config.php' ?>) to 644 for security purposes.
	</p>
	<p>
		The last step is to insert the Google Analytics code into your website. We recommend inserting it in the layout - 
		<?= $this->Html->link('go here', array(
			'plugin' => null,
			'controller' => 'templates',
			'action' => 'index'
		)) ?> and do a search for 'Layouts/default.ctp' and by default, that's the file you can enter it into. After that, no action is necessary! This text will be replaced by the plugins reporting tool. Enjoy!
	</p>
<?php else: ?>
	<?= $this->Html->script('bootstrap-datepicker.js') ?>
	<?= $this->Html->script('jquery.blockui.min.js') ?>
	<?= $this->Html->css('datepicker.css') ?>

	<?= $this->Html->script('https://www.google.com/jsapi') ?>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		
		$(function() {
			drawChart();
	    });
	</script>

	<div class="google-analytics-container">
		<div class="inner">
			<div id="chart" style="height:250px"></div>

			<div id="chart-data" class="hidden">
				<?php foreach($views as $view): ?>
					<div class="set">
						<?= $view['date'] ?>,<?= $view['views'] ?>,<?= $view['uniques'] ?>
					</div>
				<?php endforeach ?>
			</div>

			<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
				<li class="active">
					<a href="#main" data-toggle="tab">General Stats</a>
				</li>
				<li>
					<a href="#os" data-toggle="tab">Top Operating Systems</a>
				</li>
				<li>
					<a href="#browsers" data-toggle="tab">Top Used Browsers</a>
				</li>
			</ul>

			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="main">
					<div class="span11 no-marg-left clearfix">
						<h2 class="pull-left">
							Overview
						</h2>

						<?= $this->Form->create('GoogleAnalytics', array(
							'action' => 'admin_index',
							'class' => 'form-inline pull-right update-stats'
						)) ?>
					        <?= $this->Form->input('start_date', array(
					            'label' => false,
					            'div' => false,
					            'class' => 'input-small datepicker',
					            'value' => date('m-d-Y', strtotime($start_date)),
					            'data-date-format' => 'mm-dd-yyyy'
					        )) ?> 
					        to 
					        <?= $this->Form->input('end_date', array(
					            'label' => false,
					            'div' => false,
					            'class' => 'input-small datepicker',
					            'value' => date('m-d-Y', strtotime($end_date)),
					            'data-date-format' => 'mm-dd-yyyy'
					        )) ?> 
					        <?= $this->Form->button('Update', array(
					        	'type' => 'submit',
					        	'class' => 'btn btn-info',
					        	'id' => 'update-stats'
					        )) ?>
					    <?= $this->Form->end() ?>
					    <div class="clearfix"></div>

						<dl class="dl-horizontal pull-left span3 no-marg-left">
							<dt>Total Visitors</dt>
							<dd>
								<?= $stats['visitors'] ?>
							</dd>

							<dt>Total Unique Views</dt>
							<dd>
								<?= $stats['uniquePageviews'] ?>
							</dd>

							<dt>Total Page Views</dt>
							<dd>
								<?= $stats['pageViews'] ?>
							</dd>

						</dl>
						<dl class="dl-horizontal pull-left span5">
							<dt>Page Views / Visit</dt>
							<dd>
								<?= $stats['pageViewsPerVisit'] ?>
							</dd>

							<dt>Time Spent on Site</dt>
							<dd>
								<?= $stats['avgTimeOnPage'] ?>
							</dd>

							<dt>Avg Page Load Time</dt>
							<dd>
								<?= $stats['pageLoadTime'] ?> seconds
							</dd>
						</dl>
						<dl class="dl-horizontal pull-left span3">
							<dt>New Visitors</dt>
							<dd>
								<?= $stats['percentNewVisits'] ?>%
							</dd>

							<dt>Bounce Rate</dt>
							<dd>
								<?= $stats['entranceBounceRate'] ?>%
							</dd>

							<dt>Exit Rate</dt>
							<dd>
								<?= $stats['exitRate'] ?>%
							</dd>
						</dl>
						<div class="clearfix"></div>
					</div>

					<div class="span6 pull-left no-marg-left">
						<legend>Top Referrals</legend>

						<ol>
							<?php foreach($sources as $source): ?>
								<li>
									<?php if ($source['link']): ?>
										<a href="http://<?= $source['host'] ?>" target="_blank">
									<?php endif ?>

									<?= $source['host'] ?> - <?= $source['visits'] ?> visits

									<?php if ($source['link']): ?>
										</a>
									<?php endif ?>
								</li>
							<?php endforeach ?>
						</ol>
					</div>

					<div class="span6 pull-right">
						<legend>Top Searches</legend>

						<ol>
							<?php foreach($searches as $search): ?>
								<li>
									<?= $search['keyword'] ?> - <?= $search['views'] ?> visits
								</li>
							<?php endforeach ?>
						</ol>
					</div>
					<div class="clearfix"></div>
				</div>

				<div class="tab-pane" id="os">
					<legend>Top Operating Systems</legend>
				
					<ol>
						<?php foreach($operating_systems as $os): ?>
							<li>
								<?= $os['operatingSystem'] ?> <?= $os['operatingSystemVersion'] ?> - <?= $os['views'] ?> visits
							</li>
						<?php endforeach ?>
					</ol>
				</div>

				<div class="tab-pane" id="browsers">
					<legend>Top Browsers</legend>

					<ol>
						<?php foreach($browsers as $browser): ?>
							<li>
								<?= $browser['browser'] ?> <?= $browser['browserVersion'] ?> - <?= $browser['views'] ?> visits
							</li>
						<?php endforeach ?>
					</ol>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>