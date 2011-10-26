<?php
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=".$user->username."_".$survey->date."_answers".".csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    // print out the cvs file column heads
   echo "Department,";
   foreach ($departments as $department) {
        if($user->department_id != $department->id) echo '"'.$department->name.'",';
    }
    echo "\n";
    // get that data!
   foreach ($questions as $question) {
            echo '"'.$question->content.'",';
            foreach ($answers as $answer) {
                if($question->id == $answer->question_id ) {
                    if($answer->content=='0')
                        echo '"'.'n/a'.'",';
                    else
                        echo '"'.$answer->content.'",';
                }

            }
            echo "\n";

         }
?>