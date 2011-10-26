<?php if(!empty($averages)):?>
<?php arsort($averages); ?>
<?php
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=".$post['survey'].".csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    // print out the cvs file column heads
?>
<?php echo "Analysis for Survey, ".$post['survey'].", and for Question group, ".$post['questionGroup']."\n"; ?>
<?php echo "Ranking,Department,Score,Average\n";?>
            <?php $i=0; ?>
            <?php foreach($averages as $department=>$average) {?>
            <?php $i++; ?>
            <?php echo $i.",".$department.",".$scores[$department].",".$average."\n"; ?>
            <?php } ?>
<?php endif;?>