<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.container').css('width','auto');
    });
</script>
<h2> Responses for <?php echo $user->username; ?> for survey dated <?php echo $survey->date; ?></h2>
<link href="/icss/assets/5eff0a31/gridview/styles.css" type="text/css" rel="stylesheet">
<div class="grid-view">
<table class="items">
    <thead>
        <tr class="odd">
            <th><b>Department</b></th>
            <?php foreach ($departments as $department) { ?>
            <?php if($user->department_id != $department->id) echo '<th><b>'.$department->name.'</th></b>'; ?>
            <?php } ?>
        </tr>
    <thead>
    <tbody>
        
        <?php foreach ($questions as $question) { ?>
            <tr class="even">
                <td><?php echo "<b>".$question->content."</b>"; ?></td>
                <?php foreach ($answers as $answer) { if($question->id == $answer->question_id ) {?>
                <td style="text-align: center">
                    <?php  if($answer->content=='0') echo 'n/a';
                           else echo $answer->content; ?>
                </td>
                <?php }} ?>
            </tr>
        <?php } ?>

    </tbody>
</table>
</div>