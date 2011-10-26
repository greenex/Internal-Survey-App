<h1>Attendance sheet for survey dated <?php echo $survey->date; ?></h1>
<?php if(Yii::app()->user->name == "admin") : ?>
<?php
    $departments = Department::model()->findAll();
    foreach($departments as $department)
    {?>
    <div class="view">
        <?php echo "<h4>".$department->id.".".$department->name."</h4>"; ?>
        <?php $users = User::model()->findAll('department_id = :did', array(':did'=>$department->id)); ?>
        <ul style="list-style-type:circle">
        <?php foreach($users as $user) { ?>
            <?php if($user->username != "admin") {?>
            <li style="font-size: 1.3em"> <?php echo $user->username; ?>
            <?php $state = State::model()->find('survey_id=:sid AND user_id=:uid',array(':sid' => $survey->id, ':uid' => $user->id)); ?> 
            <?php if(isset($state) && $state->question_group_id == -1) echo "<img style='vertical-align:middle;padding-left:40px' src='".Yii::app()->request->baseUrl."/images/yes.jpg'/>";
            else echo "<img style='vertical-align:middle;padding-left:40px' src='".Yii::app()->request->baseUrl."/images/no.jpg'/>"; ?>
            </li>
            <?php }?>
        <?php } ?>
        </ul>
    </div>
    
<?php } ?>
<?php else: ?>
    <div class="view">
        <?php $user = User::model()->findByPk(Yii::app()->user->id); ?>
        <?php $department = Department::model()->findByPk($user->department_id); ?>
        <?php echo "<h4>".$department->id.".".$department->name."</h4>"; ?>
        <?php $users = User::model()->findAll('department_id = :did', array(':did'=>$department->id)); ?>
        <ul style="list-style-type:circle">
        <?php foreach($users as $user) { ?>
            <?php if($user->username != "admin") {?>
            <li style="font-size: 1.3em"> <?php echo $user->username; ?>
            <?php $state = State::model()->find('survey_id=:sid AND user_id=:uid',array(':sid' => $survey->id, ':uid' => $user->id)); ?> 
            <?php if(isset($state) && $state->question_group_id == -1) echo "<img style='vertical-align:middle;padding-left:40px' src='".Yii::app()->request->baseUrl."/images/yes.jpg'/>";
            else echo "<img style='vertical-align:middle;padding-left:40px' src='".Yii::app()->request->baseUrl."/images/no.jpg'/>"; ?>
            </li>
            <?php }?>
        <?php } ?>
        </ul>
    </div>

<?php endif; ?>
