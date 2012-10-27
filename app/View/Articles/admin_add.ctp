<?php
	$this->TinyMce->editor();
	$time = date('Y-m-d H:i:s');
	$time_part = explode(" - ", date('Y-m-d - g:i A'));
?>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

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

    <?php if (!$radio_fields): ?>
    	$("#ArticleAdminAddForm").validate();
    <?php else: ?>
    	$("#ArticleAdminAddForm").validate({
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
    $(".field_options").show();

    $.each($(".checkbox"), function(i, val) {
    	if (!$(this).hasClass('input')) {
    		$(this).replaceWith($(this).find('input,label'));
    	}
    });

    $("button").live('click', function(){
    	var btn = $(this).html();
    	if (btn == "Publish Later") {
    		$(".publish_time").show();
    		$("#ArticleStatus").val(1);
    	} else if(btn == "Publish Now") {
    		$(".publish_time").hide();
    		$("#ArticlePublishingDate").val('<?= $time_part[0] ?>');
    		$("#ArticlePublishingTime").val('<?= $time_part[1] ?>');
    		$("#ArticleStatus").val(1);
    	} else if(btn == "Save Draft") {
    		$(".publish_time").hide();
    		$("#ArticlePublishingDate").val('<?= $time_part[0] ?>');
    		$("#ArticlePublishingTime").val('<?= $time_part[1] ?>');
    		$("#ArticleStatus").val(0);
    	}
    });
    $('#ArticlePublishingDate').datepicker();
 });
 </script>

<h1>Add Article</h1>

<?= $this->Form->create('Article', array('type' => 'file', 'class' => 'well')) ?>

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
?>
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

<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.field_id', array('type' => 'hidden', 'value' => $field['Field']['id'])) ?>

<?php
$desc_icon = null;

	if (!empty($field['Field']['description'])) {
		$desc_icon = "<i class='icon icon-question-sign field-desc' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>&nbsp;";
	}
?>

<?php
if ($field['Field']['field_type'] == "textarea"):
?>
	<?= 
		$this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array(
			'label' => $desc_icon.$field['Field']['label'], 
			'rows' => 15, 
			'style' => 'width:500px'
		)) 
	?>
<?php
elseif ($field['Field']['field_type'] == "text"):
?>
	<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text')) ?>
<?php
elseif ($field['Field']['field_type'] == "dropdown"):
	foreach (json_decode($field['Field']['field_options']) as $row) {
		$opt[$row] = $row;
	}
?>
	<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'select', 'empty' => '- Choose -', 'options' => array($opt))) ?>
<?php
unset($opt);
elseif ($field['Field']['field_type'] == "radio"):
	foreach (json_decode($field['Field']['field_options']) as $row) {
		$opt[$row] = $row;
	}
?>
	<div class="input radio">
		<?= $this->Form->label('ArticleValue.'.$field['Field']['id'].'.data', ucfirst($field['Field']['title'])) ?>
		<?= $this->Form->radio('ArticleValue.'.$field['Field']['id'].'.data', $opt, array('legend' => false, 'hiddenField' => false, 'class' => $required)) ?>
	</div>
<?php
unset($opt);
elseif ($field['Field']['field_type'] == "multi-dropdown"):
	foreach (json_decode($field['Field']['field_options']) as $row) {
		$opt[$row] = $row;
	}
?>
	<?= $this->Form->input('ArticleFieldData.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'select', 'multiple' => true, 'options' => $opt)) ?>
<?php
unset($opt);
elseif ($field['Field']['field_type'] == "check"):
	foreach (json_decode($field['Field']['field_options']) as $row) {
		$opt[$row] = $row;
	}
?>
	<div class="input checkbox <?= $required ?>">
		<?= $this->Form->input('ArticleFieldData.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'multiple' => 'checkbox', 'options' => $opt)) ?>
	</div>
<?php
unset($opt);
elseif ($field['Field']['field_type'] == "file"):
?>
	<?= $this->Form->label(ucfirst($field['Field']['title'])) ?>
	<?= $this->Form->file('ArticleValue.'.$field['Field']['id'].'.data') ?>
<?php
elseif ($field['Field']['field_type'] == "img"):
?>
	<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text')) ?>
<?php
elseif ($field['Field']['field_type'] == "url"):
?>
	<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text', 'placeholder' => 'http://')) ?>
<?php
elseif ($field['Field']['field_type'] == "num"):
?>
	<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text', 'class' => 'input-mini')) ?>
<?php
elseif ($field['Field']['field_type'] == "email"):
?>
	<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text')) ?>
<?php
elseif ($field['Field']['field_type'] == "date"):
?>
	<?= $this->Form->input('ArticleValue.'.$field['Field']['id'].'.data', array('label' => $desc_icon.$field['Field']['label'], 'type' => 'text', 'data-date-format' => 'yyyy-mm-dd', 'value' => date("Y-m-d"))) ?>
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

<div class="clear"></div>

<div class="input text publish_time" style="display: none">
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

<?= $this->Form->hidden('created', array('value' => $time)) ?>
<?= $this->Form->hidden('status', array('value' => 0)) ?>

<label><i class='icon icon-question-sign field-desc' data-content='Linking another article to this one will allow you to show its data on this Articles page. Ex. Halo 5 Game linking to your Halo 5 preview, you can then show Halo 5 Game Details on the preview page.' data-title='Related Articles'></i>&nbsp;Relate Articles</label>

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
        'autocomplete'=>'off'
)) ?>

<span class="related-error alert alert-error" style="margin-left:15px;display:none"></span>

<div id="related-articles" style="width: 100%"></div>
</div>

<div class="clearfix"></div>

<div style="margin-top:20px">
	<?= $this->Form->button('Publish Now', array(
		'type' => 'submit',
		'class' => 'btn'
	)) ?>
	<?= $this->Form->button('Save Draft', array(
		'type' => 'submit',
		'style' => 'margin-left:5px;margin-right:5px',
		'class' => 'btn'
	)) ?>
	<?= $this->Form->button('Publish Later', array(
		'type' => 'button',
		'class' => 'btn'
	)) ?>
</div>

<?= $this->Form->end(); ?>