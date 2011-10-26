<?php if(Yii::app()->user->name=="admin" || $data->active==TRUE) : ?>
<div class="view">
        <div style="float:left">
	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br/>

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
        </div>
        <div style="float:right">
	<?php if(Yii::app()->user->name!="admin"): echo CHtml::link("Take this survey", array('take', 'survey_id'=>$data->id)); ?>
	<br /><?php endif; ?>
        <?php if(Yii::app()->user->name=="admin") : ?>
        <b><?php echo "State"; ?>:</b>
	<?php if (!$data->active) echo "Not Active";
              else echo "Active"; ?>
	<br />
	<?php echo CHtml::link((!$data->active)?"Activate":"Deactivate", array('toggle', 'survey_id'=>$data->id)); ?>
	<br />
        <?php endif; ?>
        <?php echo CHtml::link("Attendance Sheet", array('attendance', 'survey_id'=>$data->id)); ?>
	<br />
        </div>
        <div style="clear:both"></div>

</div>
<?php endif; ?>