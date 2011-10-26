<?php
$this->breadcrumbs=array(
	'Question Groups',
);

$this->menu=array(
	array('label'=>'Create QuestionGroup', 'url'=>array('create')),
	array('label'=>'Manage QuestionGroup', 'url'=>array('admin')),
);
?>

<h1>Question Groups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
