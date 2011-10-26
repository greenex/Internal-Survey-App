<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />
        <?php $question_group = QuestionGroup::model()->findByPk($data->question_group_id); ?>
	<b><?php echo CHtml::encode($data->getAttributeLabel('question_group_id')); ?>:</b>
	<?php echo CHtml::encode($question_group->content); ?>
	<br />


</div>