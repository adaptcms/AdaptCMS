<?php
	$this->TinyMce->editor();

	$time_part = explode(" - ", date('Y-m-d - g:i A'));
	if ($this->request->data['Article']['status'] == 0) {
		$draft = "Keep As ";
	} else {
		$draft = "Save ";
	}
?>

<?= $this->Html->script('data-tagging.js') ?>
<?= $this->Html->css("data-tagging.css") ?>

<?= $this->Html->css("datepicker.css") ?>
<?= $this->Html->script('bootstrap-datepicker.js') ?>
<?= $this->Html->script('bootstrap-typeahead.js') ?>

<script>
 $(document).ready(function(){
    $('#related-search').typeahead({
        source: function(typeahead, query) {
                $.ajax({
                    url: "<?= $this->webroot ?>admin/articles/ajax_related_search",
                    dataType: "json",
                    type: "POST",
                    data: {search: query, category: $("#category").val(), id: $("#ArticleId").val()},
                    success: function(data) {
                        if (data) {
                            var return_list = [], i = data.length;
                            while (i--) {
                                return_list[i] = {
                                    id: data[i].id, 
                                    value: data[i].title + data[i].category
                                };
                            }
                            typeahead.process(return_list);
                        }
                    }
                });
            },
            onselect: function(obj) {
                if (obj.id) {
                	if ($(".related[value='" + obj.id + "']").length == 0) {
                		$(".related-error").html("").hide();

	                	var html = '<div id="data-' + obj.id + '"><span class="label label-info">' + obj.value + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="RelatedData[]" class="related" name="RelatedData[]" value="' + obj.id + '"></div>';

	                	$("#related-articles").prepend(html);
	                } else {
	                	$(".related-error").html("<strong>Error</strong> Article already linked").show();
	                }

                	$("#related-search").val("").focus();
                }
        }
    });

	$(".field-desc").popover({
		trigger: 'hover',
		placement: 'left'
	});

	$("#related-submit").live('click', function(e) {
		e.preventDefault();

		if ($(".related").length > 0) {
			var values = $(".related").map(function(){
				return $(this).val();
			}).get();

            $.post("<?= $this->webroot ?>admin/articles/ajax_related_add/", 
                {
                    data:{
                        Article:{
                            id: $("#ArticleId").val(),
                            ids: values
                        }
                    }
                }, function(data) {
                if (data) {
                	$("#flashMessageRelated").replaceWith(data);
                	$("#flashMessageRelated").fadeOut(3000);
                }
            });
		}
	});

 	<?php if (!$radio_fields): ?>
    	$("#ArticleEditForm").validate();
    <?php else: ?>
    	$("#ArticleEditForm").validate({
    		focusInvalid: false,
			invalidHandler: function(form, validator) {
				$(this).find(":input.error:first:not(:checkbox):not(:radio)").focus();
			},
    		errorPlacement: function(error, element) {
				if ($(element).attr('type') == 'radio' || $(element).attr('type') == 'checkbox') {
					error.insertAfter( $(element).parent().find('label').last() );
				} else {
					error.insertAfter( element );
				}
			}
    	});
    <?php endif ?>
    $("input[type=file]").live('change', function() {
    	if ($("#" + this.id).val()) {
    		$("#" + this.id.replace('Data', 'Delete')).attr('disabled', true).attr('checked', false);
    	}
    });
    $(".field_options").show();

    $.each($(".checkbox"), function(i, val) {
    	if (!$(this).hasClass('input')) {
    		$(this).replaceWith($(this).find('input,label'));
    	}
    });

    $("button").live('click', function(){
    	var btn = $(this).html();
    	if(btn == "Publish Now") {
    		$(".publish_time").hide();
    		$("#ArticlePublishingDate").val('<?= $time_part[0] ?>');
    		$("#ArticlePublishingTime").val('<?= $time_part[1] ?>');
    		$("#ArticleStatus").val(1);
    	} else if(btn == "Save Draft") {
    		$(".publish_time").hide();
    		$("#ArticlePublishingDate").val('<?= $time_part[0] ?>');
    		$("#ArticlePublishingTime").val('<?= $time_part[1] ?>');
    		$("#ArticleStatus").val(0);
    	} else {
    		$(".publish_time").toggle();
    		$("#ArticleStatus").val(1);
    	}
    });
    $('#ArticlePublishingDate').datepicker();

    <?php if ($this->request->data['Article']['publish_time'] > $this->Time->format('Y-m-d H:i:s', time())): ?>
    	$(".publish_time").show();
    	<?php
    		$time_part = explode(" - ", 
    			$this->Time->format(
    				'Y-m-d - g:i A', 
    				$this->request->data['Article']['publish_time']
    			)
    		);
    	?>
    <?php endif ?>
 });
 </script>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li<?= (empty($this->params['pass'][1]) ? " class='active'" : "") ?>>
		<a href="#main" data-toggle="tab">Edit Article</a>
	</li>
	<li>
		<a href="#relate" data-toggle="tab">Relate Articles</a>
	</li>
	<?php if (!empty($comments)): ?>
		<li<?= (!empty($this->params['pass'][1]) ? " class='active'" : "") ?>>
			<a href="#comments" data-toggle="tab">Comments</a>
		</li>
	<?php endif ?>
</ul>

<div class="right">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Article']['id'], $this->request->data['Article']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this article?')"));
    ?>
</div>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane<?= (empty($this->params['pass'][1]) ? " fade active in" : "") ?>" id="main">
		<?= $this->Form->create('Article', array('type' => 'file', 'action' => 'edit', 'class' => 'well')) ?>

			<h2>Edit Article</h2>

			<?= $this->Form->input('title', array(
					'type' => 'text', 
					'class' => 'required',
					'label' => "<i class='icon icon-question-sign field-desc' data-content='This is the Title of your article, the name is also what its called.' data-title='Title'></i>&nbsp;Title"
			)) ?>
			<?= $this->Form->input('category_id', array('type' => 'hidden', 'value' => $category_id)) ?>

			<?php
			$jqueryFunction = 0;
			$required = null;

			if (count($fields) > 0):
				foreach ($fields as $field):
				$value = array();
				$dataId = array();
					foreach($this->request->data['ArticleValue'] as $key => $data) {
						if ($data['field_id'] == $field['Field']['id'] && !empty($data['data'])) {
							$dataId[] = $data['id'];

							if (json_decode($data['data'])) {
								
								if (!empty($data['File']['id'])) {
									$value[] = json_decode($data['data']);
									$value['File'] = $data['File'];
								} else {
									$value = json_decode($data['data']);
								}
							} else {
								$value[] = $data['data'];
							}

							unset($this->request->data['ArticleValue'][$key]);
						}
					}
			?>

			<?php if ($field['Field']['field_type'] != "check" && 
					  $field['Field']['field_type'] != "multi-dropdown" && 
					  !empty($dataId)): 
			?>
				<?= $this->Form->input('ArticleValue.'.$dataId[0].'.id', array('type' => 'hidden', 'value' => $dataId[0])) ?>
				<?= $this->Form->input('ArticleValue.'.$dataId[0].'.field_id', array('type' => 'hidden', 'value' => $field['Field']['id'])) ?>
				<?php $field['Field']['id'] = $dataId[0] ?>
			<?php elseif ($field['Field']['field_type'] != "check" &&
						  $field['Field']['field_type'] != "multi-dropdown"
			):  ?>
				<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.field_id', array('type' => 'hidden', 'value' => $field['Field']['id'])) ?>
			<?php else: ?>
				<?php foreach ($dataId as $id): ?>
					<?= $this->Form->input('ArticleFieldData.'.$field['Field']['id'].'.id', array('type' => 'hidden', 'value' => $id)) ?>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if (!empty($field['Field']['rules'])):
			$rules = json_decode($field['Field']['rules']);
			$rules_count = count($rules);
			$addClass = 0;
			?>
				<?php if (!empty($rules)): ?>
					<?php if($field['Field']['field_type'] != "radio"): ?>
						<script type="text/javascript">
							$(document).ready(function(){
								<?php if ($field['Field']['field_type'] == "date"): ?>
									$("#ArticleValue<?= $field['Field']['id'] ?>Data").datepicker();
								<?php endif ?>
								<?php if ($field['Field']['field_type'] == "multi-dropdown"): ?>
									$("#<?php echo 'ArticleFieldData'.$field['Field']['id'].'Data'; ?>").rules("add", {
								<?php elseif ($field['Field']['field_type'] == "check"): ?>
									$("input[name='data[ArticleFieldData][<?= $field['Field']['id'] ?>][data][]']").rules("add", {
								<?php else: ?>
									$("#<?php echo 'ArticleValue'.$field['Field']['id'].'Data'; ?>").rules("add", {
								<?php endif ?>
									<?php $i = 0 ?>
									<?php foreach($rules as $row): $i++; ?>
										<?php if (!empty($row)): ?>
											<?php if ($i == $rules_count): ?>
												<?= str_replace(",","",$row) ?>
											<?php else: ?>
												<?= $row ?>
											<?php endif; ?>
											<?php
												if (strstr($row, "required: true")) { 
													$addClass = 1;
												}
											?>	
										<?php endif; ?>
									<?php endforeach; ?>
						    	});
						    	<?php if ($addClass == 1): ?>
						    		<?php if ($field['Field']['field_type'] == "multi-dropdown"): ?>
						    			$("#<?php echo 'ArticleFieldData'.$field['Field']['id'].'Data'; ?>").addClass('required');
						    		<?php elseif ($field['Field']['field_type'] == "check"): ?>
						    			$("input[name='data[ArticleFieldData][<?= $field['Field']['id'] ?>][data][]']").addClass('required');
						    		<?php else: ?>
						    			$("#<?php echo 'ArticleValue'.$field['Field']['id'].'Data'; ?>").addClass('required');
						    		<?php endif ?>
						    		<?php 
						    			if ($jqueryFunction == 0) {
						    				$jqueryFunction = 1;
						    			}
						    		?>
						    	<?php endif ?>
						   });
						</script>
					<?php else: ?>
						<?php
							if (is_numeric(
									array_search(
										"required: true,", 
										json_decode($field['Field']['rules']
										)
									)
								)) {
								$required = "required";
							}
						?>
					<?php endif ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if (end($fields) == $field && $jqueryFunction == 1): ?>
				<script type="text/javascript">
					$(document).ready(function(){   	
						changeRequiredFields();
					});
				</script>
			<?php endif ?>

			<?php
			$desc_icon = null;

				if (!empty($field['Field']['description'])) {
					$desc_icon = "<i class='icon icon-question-sign field-desc' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>&nbsp;";
				}
			?>

			<?php
			if ($field['Field']['field_type'] == "textarea"):
				if (empty($value)) {
					$value[0] = null;
				}
			?>
				<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'rows' => 15, 'style' => 'width:500px', 'value' => $value[0])) ?>
			<?php
			elseif ($field['Field']['field_type'] == "text"):
			?>
				<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text', 'value' => $value)) ?>
			<?php
			elseif ($field['Field']['field_type'] == "dropdown"):
				foreach (json_decode($field['Field']['field_options']) as $row) {
					$opt[$row] = $row;
				}
			?>
				<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'select', 'empty' => '- Choose -', 'options' => array($opt), 'value' => $value)) ?>
			<?php
			unset($opt);
			elseif ($field['Field']['field_type'] == "radio"):
				foreach (json_decode($field['Field']['field_options']) as $row) {
					$opt[$row] = $row;
				}
			?>
				<div class="input radio">
					<?= $this->Form->label('ArticleValue.'.$field['Field']['id'].'.data', ucfirst($field['Field']['title'])) ?>
					<?= $this->Form->radio('ArticleValue.'.$field['Field']['id'].'.data', $opt, array('legend' => false, 'hiddenField' => false, 'class' => $required, 'value' => $value[0])) ?>
				</div>
			<?php
			unset($opt);
			elseif ($field['Field']['field_type'] == "multi-dropdown"):
				foreach (json_decode($field['Field']['field_options']) as $row) {
					$opt[$row] = $row;
				}
			?>
				<?= $this->Form->input('ArticleFieldData.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'multiple' => true, 'options' => $opt, 'value' => $value)) ?>

			<?php
			unset($opt);
			elseif ($field['Field']['field_type'] == "check"):
				foreach (json_decode($field['Field']['field_options']) as $row) {
					$opt[$row] = $row;
				}
			?>
				<div class="input checkbox <?= $required ?>">
					<?= $this->Form->input('ArticleFieldData.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'multiple' => 'checkbox', 'options' => $opt, 'value' => $value)) ?>
				</div>
			<?php
			unset($opt);
			elseif ($field['Field']['field_type'] == "file"):
			?>
				<?= $this->Form->label(ucfirst($field['Field']['title'])) ?>
				<?= $this->Form->file('ArticleValue.'.$field['Field']['id'].'.data') ?>
				<?php if (!empty($value[0])): ?>
					<?= $this->Form->hidden('ArticleValue.'.$field['Field']['id'].'.filename', array(
						'value' => $value
					)) ?>
					<br />
					Current File: <?= $value[0] ?> 
					<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.delete', array(
						'type' => 'checkbox',
						'label' => 'Unlink?'
					)) ?>
				<?php endif; ?>
			<?php
			elseif ($field['Field']['field_type'] == "img"):
				if (empty($value[0])) {
					$value[0] = "";
				}
			?>
				<div>
					<?= $this->Form->hidden('ArticleValue.'.$field['Field']['id'].'.data', array('value' => $value[0])) ?>
					<?= $this->Form->hidden('ArticleValue.'.$field['Field']['id'].'.file_id', array('value' => $value[0])) ?>
					<?= $this->Form->label($desc_icon.$field['Field']['label']) ?>

					<?= $this->Html->link('Attach Image <i class="icon icon-white icon-upload"></i>', '#media-modal'.$field['Field']['id'], array('class' => 'btn btn-primary media-modal', 'escape' => false, 'data-toggle' => 'modal')) ?>

					<p>&nbsp;</p>
					<div class="selected-images span12 row">
						<?php if (!empty($value['File'])): ?>
							<?= $this->element('media_modal_image', array(
								'image' => $value['File'], 
								'key' => 0, 
								'check' => true
							)) ?>
						<?php endif ?>
					</div>
				</div>

				<?= $this->element('media_modal', array('limit' => 1, 'ids' => 'ArticleValue.'.$field['Field']['id'].'.data', 'id' => $field['Field']['id'])) ?>
			<?php
			elseif ($field['Field']['field_type'] == "url"):
			?>
				<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text', 'placeholder' => 'http://', 'value' => $value)) ?>
			<?php
			elseif ($field['Field']['field_type'] == "num"):
			?>
				<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text', 'class' => 'input-mini', 'value' => $value)) ?>
			<?php
			elseif ($field['Field']['field_type'] == "email"):
			?>
				<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text', 'value' => $value)) ?>
			<?php
			elseif ($field['Field']['field_type'] == "date"):
			?>
				<?php (empty($value) ? $value = date("Y-m-d") : "") ?>
				<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text', 'value' => $value, 'data-date-format' => 'yyyy-mm-dd')) ?>
			<?php
			endif;
				endforeach;
			endif;
			?>

			<div id="text"></div>
			<div class="field_options" style="margin-bottom: 9px">
					<?= $this->Form->input('field_options', array(
						'div' => false, 
						'style' => 'margin-bottom: 0',
						'type' => 'text',
						'label' => "<i class='icon icon-question-sign field-desc' data-content='Tagging an article with a keyword, will let you see a list of those articles. So if you tag 3 articles with <strong>xbox</strong>, you can then go to site.com/tag/xbox and see all articles with the xbox tag.' data-title='Tags'></i>&nbsp;Tags"
					)) ?>
					<?= $this->Form->button('Add', array(
						'class' => 'btn btn-info', 
						'type' => 'button',
						'id' => 'add-data'
					)) ?>
			</div>
			<div id="field_data" style="width: 30%"></div>
			<div id="field_existing_data" style="display: none">
				<?php
					$field_options = json_decode($this->request->data['Article']['tags']);
					if (count($field_options) == 0) {
						$field_style = "padding-top: 0px";
					} else {
						$field_style = "padding-top: 9px";
					}
				?>
				<?php if (count($field_options) > 0): ?>
					<?php foreach($field_options as $row): ?>
						<span><?= $row ?></span>
					<?php endforeach ?>
				<?php endif ?>
			</div>

			<div class="clear"></div>

			<?= $this->Form->input('Article.settings.comments_status', array(
				'options' => array(
					'open' => 'Open',
					'closed' => 'Closed'
				),
				'value' => $this->request->data['Article']['settings']->comments_status
			)) ?>

			<br />
			<label>Current Status</label>

			<?php if ($this->request->data['Article']['status'] == 1): ?>
				<div class="alert alert-success" style="width:36%;padding:20px;text-align:center">
					Article is Live
				</div>
			<?php elseif ($this->request->data['Article']['status'] == 0 &&
						  $this->request->data['Article']['publish_time'] == "0000-00-00 00:00:00"): ?>
				<div class="alert alert-block" style="width:36%;padding:20px;text-align:center">
					Article is Saved as a Draft, NOT live
				</div>
			<?php else: ?>
				<div class="alert alert-info" style="width:36%;padding:20px;text-align:center">
					Article will go Live - 
					<?= $this->Time->format("F d, Y h:i a", $this->request->data['Article']['publish_time']) ?>
				</div>
			<?php endif ?>
			<br />

			<div class="input text publish_time" style="display: none;margin-top:9px;margin-bottom: 20px">
				<?= $this->Form->input('publishing_date', array(
						'type' => 'text',
						'label' => 'Publish Time',
						'div' => false,
						'style' => 'width:70px',
						'value' => $time_part[0],
						'data-date-format' => 'yyyy-mm-dd'
				)) ?>

				<?= $this->Form->input('publishing_time', array(
						'type' => 'text',
						'label' => false,
						'div' => false,
						'style' => 'width:60px',
						'value' => $time_part[1]
				)) ?>

				<?= $this->Form->button('Submit', array(
					'type' => 'submit',
					'class' => 'btn'
				)) ?>
			</div>

			<?= $this->Form->hidden('id') ?>
			<?= $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time()))) ?>

			<?= $this->Form->hidden('status', array('value' => $this->request->data['Article']['status'])) ?>

			<?= $this->Form->button('Publish Now', array(
				'type' => 'submit',
				'class' => 'btn'
			)) ?>
			<?= $this->Form->button($draft.'Draft', array(
				'type' => 'submit',
				'style' => 'margin-left:5px;margin-right:5px',
				'class' => 'btn'
			)) ?>
			<?= $this->Form->button('<i class="icon-calendar"></i> Publish Later', array(
				'type' => 'button',
				'class' => 'btn',
				'escape' => false
			)) ?>

		<?= $this->Form->end(); ?>
	</div>

	<div class="tab-pane" id="relate">
		<?= $this->Form->create('RelatedArticle', array('action' => 'ajax_add', 'class' => 'well')) ?>
			<h2>Relate Articles <i class='icon icon-question-sign field-desc' data-content='Linking another article to this one will allow you to show its data on this Articles page. Ex. Halo 5 Game linking to your Halo 5 preview, you can then show Halo 5 Game Details on the preview page.' data-title='Related Articles' data-placement="right"></i></h2>

			<div class="pull-left">

			<?= $this->Form->input('category', array(
					'id' => 'category',
			        'div' => false,
			        'label' => false,
			        'empty' => '- Category -',
			        'options' => $categories,
			        'style' => 'width: 150px;margin-right: 10px'
			)) ?>
			<?= $this->Form->input('related-search', array(
					'id' => 'related-search',
			        'div' => false,
			        'label' => false,
			        'data-provide' => 'typeahead', 
			        'data-source' => '[]', 
			        'autocomplete'=>'off',
			        'style' => 'margin-bottom: 9px'
			)) ?>

			<span class="related-error alert alert-error" style="margin-left:15px;display:none"></span>

			<div id="related-articles" style="width: 100%">
				<?php if (!empty($related_articles)): ?>
					<?php foreach($related_articles as $row): ?>
						<div id="data-<?= $row['Article']['id'] ?>"><span class="label label-info"><?= $row['Article']['title'] ?> (<?= $row['Category']['title'] ?>) <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="RelatedData[]" class="related" name="RelatedData[]" value="<?= $row['Article']['id'] ?>"></div>
					<?php endforeach ?>
				<?php endif ?>
			</div>

			</div>

			<p>&nbsp;</p>
			<div class="clearfix"></div>

			<?= $this->Form->button('Submit', array(
				'type' => 'submit',
				'class' => 'btn',
				'style' => 'margin-top: 20px;margin-bottom: 10px',
				'id' => 'related-submit'
			)) ?>

			<div id="flashMessageRelated" class="alert alert-success" style="display:none"></div>
		<?= $this->Form->end() ?>
	</div>

	<div class="tab-pane<?= (!empty($this->params['pass'][1]) ? " fade active in" : "") ?>" id="comments">
		<?= $this->Form->create('Comment', array(
			'url' => array(
				'controller' => 'comments',
				'action' => 'edit', 
				$this->request->data['Article']['id']
			),
			'class' => 'well'
		)) ?>

			<h2>Comments</h2>

			<?= $this->Form->input('all', array('div' => array('class' => 'pull-right'), 'class' => 'check-all', 'label' => 'Check All', 'type' => 'checkbox', 'checked' => false)) ?>
			<div class="clearfix"></div>

			<?php foreach($comments as $i => $comment): ?>
				<?php $id = $comment['Comment']['id'] ?>

				<?php if ($i % 2 === 0 && $i != 0): ?>
					<div class="clearfix"></div><br />
				<?php endif ?>

				<div class="pull-left" style="margin-left: 50px">
					<h4>
						@ <?= $this->Time->format('F jS, Y h:i A', $comment['Comment']['created']) ?>
						by 
						<?php if (!empty($comment['User']['id'])): ?>
							<?= $this->Html->link($comment['User']['username'], array(
								'controller' => 'users',
								'action' => 'edit',
								$comment['User']['id']
								), array('target' => '_blank')
						) ?>
						<?php else: ?>
							<span class="field-desc" data-content="
							IP: <?= $comment['Comment']['author_ip'] ?><br />
							<?php if (!empty($comment['Comment']['author_name'])): ?>
								<?= $comment['Comment']['author_name'] ?><br />
							<?php endif ?>	
							<?php if (!empty($comment['Comment']['author_email'])): ?>
								<?= $comment['Comment']['author_email'] ?><br />
							<?php endif ?>	
							<?php if (!empty($comment['Comment']['author_website'])): ?>
								<?= $this->Html->link($comment['Comment']['author_website'], $comment['Comment']['author_website'], array('target' => '_blank')) ?><br />
							<?php endif ?>
							" data-title="Comment Info">
								Guest
							</span>
						<?php endif ?>
					</h4>

					<?= $this->Form->input($i.'.Comment.comment_text', array(
						'class' => 'wysiwyg required', 
						'style' => 'width:45%;height:120px',
						'value' => $comment['Comment']['comment_text'],
						'label' => false
					)) ?>

					<?= $this->Form->input($i.'.Comment.active', array(
							'type' => 'checkbox', 
							'value' => 1,
							'checked' => ($comment['Comment']['active'] == 1 ? 'checked' : '')
					)) ?>

					<?= $this->Form->hidden($i.'.Comment.id', array('value' => $id)) ?>
				</div>
			<?php endforeach ?>

			<div class="clearfix"></div>

			<?= $this->Form->submit('Update Comments', array('class' => 'btn')) ?>
		<?= $this->Form->end() ?>

		<?= $this->element('admin_pagination') ?>
	</div>
</div>