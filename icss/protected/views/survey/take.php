<?php
$this->breadcrumbs=array(
	'Surveys'=>array('index'),
	'Take',
);
$error = 0;
?>
<?php if(Yii::app()->user->hasFlash('answered')): ?>
<div class="flash-notice">
    <?php echo Yii::app()->user->getFlash('answered'); ?>
</div>
<?php else: ?>
    <?php if(Yii::app()->user->hasFlash('Successful')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('Successful'); ?>
    </div>
    <?php else: ?>

        <h1>Survey dated <?php echo $survey->date;?></h1>
        
        <?php if(Yii::app()->user->hasFlash('Error')): ?>
            <div class="flash-error">
                <?php echo Yii::app()->user->getFlash('Error');
                $error = 1; ?>
            </div>
        <?php endif; ?>
        <div class="flash-notice">
            <?php echo "<h2>".$question_group->content."</h2>"; ?>
        </div>

        <div class="form">

        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'answer-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php $i = 0;?>
        <?php foreach($questions as $question):?>

            <div class="view">
                <?php echo "<h3 style='line-height:1.5'>".$question->id.". ".$question->content.":</h3>"; ?>
                <table>
                    <tr style="font-size: 1.15em">
                        <td></td>
                        <td style="text-align: right">n/a 1&nbsp; 2&nbsp;  3&nbsp; 4&nbsp; 5&nbsp;</td>
                    </tr>
                    <?php foreach($departments as $department)
                    {?>

                        <?php if($department->id != $user->department_id) :?>
                            <tr style="font-size: 1.15em">

                                <td><?php echo $form->label($answer,$department->name,array('style'=>'display:inline')); ?></td>
                                <?php if($error) {
                                    $answer->content = $sticky_answers[$i]['content'];
                                }?>
                                <td style="text-align: right"><?php echo $form->radioButtonList($answer,'['.$i.']content',array('n/a','1','2','3','4','5'),array('separator'=>' ', 'labelOptions'=>array('style'=>'display:none'))); ?></td>
                                <?php echo $form->error($answer,'['.$i.']content'); ?>
                                <?php echo $form->hiddenField($answer,'['.$i.']department_id',array('value'=>$department->id)) ?>
                                <?php echo $form->hiddenField($answer,'['.$i.']survey_id',array('value'=>$survey->id)) ?>
                                <?php echo $form->hiddenField($answer,'['.$i.']user_id',array('value'=>Yii::app()->user->id)) ?>
                                <?php echo $form->hiddenField($answer,'['.$i.']question_id',array('value'=>$question->id)) ?>
                                <?php $i++;?>
                            </tr>
                        <?php endif; ?>
                    <?php }?>
                </table>
            </div>

        <?php endforeach;?>

        <div class="row buttons">
                <div style="float:left"><?php echo CHtml::submitButton('Submit and Continue'); ?></div>
            	<div style="float:right"><?php echo CHtml::button('Exit', array('submit' => array('index'))); ?></div>
                <div style="clear: both"/>
        </div>

        <?php $this->endWidget(); ?>

        </div><!-- form -->
    <?php endif; ?>
<?php endif; ?>