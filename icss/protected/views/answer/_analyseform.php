<?php $surveyunits=array();
        foreach($surveys as $survey)
        {
            $surveyunits[$survey->date]=$survey->date;
        }
        $questionGroupunits=array();
        foreach($questionGroups as $questionGroup)
        {
            $questionGroupunits[$questionGroup->content]=$questionGroup->content;
        }
        $surveyunits['All']="All";
        $questionGroupunits['All']="All";
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'answer-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row" style="float:left;padding-right: 25px">
		<?php echo $form->labelEx($model,'survey'); ?>
		<?php echo $form->DropDownList($model,'survey',$surveyunits); ?>
		<?php echo $form->error($model,'survey'); ?>
	</div>

        <div class="row" style="float:left">
		<?php echo $form->labelEx($model,'questionGroup'); ?>
		<?php echo $form->DropDownList($model,'questionGroup',$questionGroupunits); ?>
		<?php echo $form->error($model,'questionGroup'); ?>
	</div>

	<div class="row buttons" style="clear: both">
		<?php echo CHtml::button('Analyse', array('submit' => array('analyseoverall'))); ?>
                <?php echo CHtml::button('Analyse in csv', array('submit' => array('analysecsv'))); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
