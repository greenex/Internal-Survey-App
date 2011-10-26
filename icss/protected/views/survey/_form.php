<!--<script src="/icss/assets/1c31031d/jui/js/jquery-ui.min.js" type="text/javascript"/>-->
<div class="form">
<script type="text/javascript">
	$(function() {
		$( "#datepicker" ).datepicker();
	});
</script>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'survey-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',
                        array(
                                'model' => $model,
                                'attribute' => 'date',
                                'language' => '',
                                'options' => array(
                                        'showAnim' => 'fold',
                                        'dateFormat' => 'yy-mm-dd',
                                        'defaultDate' => date('yy-mm-dd'),
                                        'changeYear' => true,
                                        'changeMonth' => true,
                                        'yearRange' => '1900',
                                ),
                )); ?>

		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->