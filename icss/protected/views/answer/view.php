<link href="/icss/assets/5eff0a31/detailview/styles.css" type="text/css" rel="stylesheet">
<?php
$this->breadcrumbs=array(
	'Answers'=>array('index'),
	//$model->id,
);

$this->menu=array(
	array('label'=>'Display Responses', 'url'=>array('display','survey_id'=>$survey->id,'user_id'=>$user->id)),
	array('label'=>'Export as csv', 'url'=>array('csv','survey_id'=>$survey->id,'user_id'=>$user->id)),
	array('label'=>'Delete Responses', 'url'=>'#', 'linkOptions'=>array('submit'=>array('deleteall','survey_id'=>$survey->id,'user_id'=>$user->id),'confirm'=>'Are you sure you want to delete this response?')),
);
?>

<h1>View Response</h1>
<table class="detail-view">
    <tbody>
        <tr class="odd">
            <th>Survey Date</th>
            <td><?php echo $survey->date; ?></td>
        </tr>
        <tr class="even">
            <th>User</th>
            <td><?php echo $user->username; ?></td>
        </tr>
    </tbody>
</table>
<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'question_id',
		'content',
		'survey_id',
		'user_id',
		'department_id',
	),
));*/ ?>
