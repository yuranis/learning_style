<?php

require(__DIR__.'/../../config.php');

#$DB->delete_records("learning_style");
#$DB->delete_records("personality_test");

$learning_style_data = $DB->get_records("learning_style");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=learning_style_data.csv');

$data = "id,user,state,course,act_ref,sen_int,vis_vrb,seq_glo,created_at,updated_at\n";
foreach($learning_style_data as $key => $value){
    foreach($value as $k => $v){
        $data .= "$v,";
    }
    $data .= "\n";
}

print($data);
