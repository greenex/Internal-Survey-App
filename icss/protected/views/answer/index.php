<?php
$this->breadcrumbs=array(
	'Answers',
);

$this->menu=array(
	array('label'=>'Analyse Overall', 'url'=>array('analyseoverall')),
);
?>

<h1>Answers</h1>
<?php foreach($users as $user) { ?>
    <?php $checkuser = Answer::model()->find('user_id=:user_id',array(':user_id'=>$user->id)); ?>
    <?php if(isset($checkuser)) : ?>
        <div class="view">
        <?php echo "<b>Username: </b>".$user->username."<br/>"."<b>Surveys: </b><br/>"; ?>
        <?php foreach($surveys as $survey) { ?>
            
            <?php $check = Answer::model()->find('survey_id=:survey_id AND user_id=:user_id', array(':survey_id'=>$survey->id,':user_id'=>$user->id));?>
            
            <?php if(isset($check)) : ?>
                <li style="list-style-type: none"><?php echo CHtml::link("<b>".$survey->date."</b>", array('view', 'survey_id'=>$survey->id,'user_id'=>$user->id)); ?></li>
            <?php endif; ?>
            
        <?php } ?>
        </div>
    <?php endif; ?>
<?php } ?>