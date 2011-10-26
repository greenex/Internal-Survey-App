<?php
$this->breadcrumbs=array(
	'Question Groups'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List QuestionGroup', 'url'=>array('index')),
	array('label'=>'Create QuestionGroup', 'url'=>array('create')),
	array('label'=>'Update QuestionGroup', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete QuestionGroup', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage QuestionGroup', 'url'=>array('admin')),
);
?>

<h1>View QuestionGroup #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'content',
	),
)); ?>
