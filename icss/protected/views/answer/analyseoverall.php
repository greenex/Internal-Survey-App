<h1>Analysis center</h1>
<?php echo $this->renderPartial('_analyseform', array('model'=>$model, 'surveys'=>$surveys, 'questionGroups'=>$questionGroups, 'post'=>$post)); ?>
<?php if(!empty($averages)):?>
<?php arsort($averages); ?>
<?php if(!empty($post)): ?><h2>Analysis for Survey <?php echo $post['survey']; ?> and for Question group <?php echo $post['questionGroup']; ?> </h2> <?php endif; ?>
<link href="/icss/assets/5eff0a31/gridview/styles.css" type="text/css" rel="stylesheet">
<div class="grid-view">
    <table class="items">
        <thead>
            <tr class="odd">
                <th>Ranking</th>
                <th>Department</th>
                <th>Score</th>
                <th>Average</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; ?>
            <?php foreach($averages as $department=>$average) {?>
            <?php $i++; ?>
            <tr class="even">
                <td><?php echo $i; ?></td>
                <td><?php echo $department; ?></td>
                <td><?php echo $scores[$department]; ?></td>
                <td><?php echo $average; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php endif;?>
