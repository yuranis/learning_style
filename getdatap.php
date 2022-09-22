<?php

require(__DIR__.'/../../config.php');

$learning_style_data = $DB->get_records("personality_test");

#header('Content-Type: text/csv; charset=utf-8');
#header('Content-Disposition: attachment; filename=personality_test.csv');

$data = "id,user,state,course,extraversion,introversion,sensing,intuition,thinking,feeling,judging,perceptive,created_at,updated_at\n";
foreach($learning_style_data as $key => $value){
    foreach($value as $k => $v){
        $data .= "$v,";
    }
    $data .= "\n";
}

print($data);
