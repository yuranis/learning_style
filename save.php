<?php

require_once(dirname(__FILE__) . '/lib.php');

require_login();
$courseid = optional_param('cid', 0, PARAM_INT);
$learning_style_a = array();

$act_ref_eval = [1,5,9,13,17,21,25,29,33,37,41];
$act_ref["a"] = 0;
$act_ref["b"] = 0;
$act_ref["result"] = "";

$sen_int_eval = [2,6,10,14,18,22,26,30,34,38,42];
$sen_int["a"] = 0;
$sen_int["b"] = 0;
$sen_int["result"] = "";

$vis_vrb_eval = [3,7,11,15,19,23,27,31,35,39,43];
$vis_vrb["a"] = 0;
$vis_vrb["b"] = 0;
$vis_vrb["result"] = "";

$seq_glo_eval = [4,8,12,16,20,24,28,32,36,40,44];
$seq_glo["a"] = 0;
$seq_glo["b"] = 0;
$seq_glo["result"] = "";

for ($i=1;$i<=44;$i++){
    $learning_style_a[$i] = optional_param("learning_style:q".$i, 0, PARAM_INT);
}

//var_dump($learning_style_a);

if ($courseid == SITEID && !$courseid) {
    redirect($CFG->wwwroot);
}

/**
* Evaluando Activo Reflexivo
*/
foreach($act_ref_eval as $item){
    //echo "Evaluando item $item";
    if ($learning_style_a[$item] == 0){
        $act_ref["a"]++;
    }else{
        $act_ref["b"]++;
    }
}

if ($act_ref["a"]>$act_ref["b"]) {
    $act_ref["result"] = ($act_ref["a"]-$act_ref["b"])."a";
}else{
    $act_ref["result"] = ($act_ref["b"]-$act_ref["a"])."b";
}

/**
* Evaluando Sensitivo Intuitivo
*/
foreach($sen_int_eval as $item){
    //echo "Evaluando item $item";
    if ($learning_style_a[$item] == 0){
        $sen_int["a"]++;
    }else{
        $sen_int["b"]++;
    }
}

if ($sen_int["a"]>$sen_int["b"]) {
    $sen_int["result"] = ($sen_int["a"]-$sen_int["b"])."a";
}else{
    $sen_int["result"] = ($sen_int["b"]-$sen_int["a"])."b";
}

/**
* Evaluando Visual Verbal
*/
foreach($vis_vrb_eval as $item){
    //echo "Evaluando item $item";
    if ($learning_style_a[$item] == 0){
        $vis_vrb["a"]++;
    }else{
        $vis_vrb["b"]++;
    }
}

if ($vis_vrb["a"]>$vis_vrb["b"]) {
    $vis_vrb["result"] = ($vis_vrb["a"]-$vis_vrb["b"])."a";
}else{
    $vis_vrb["result"] = ($vis_vrb["b"]-$vis_vrb["a"])."b";
}

/**
* Evaluando Secuencial Global
*/
foreach($seq_glo_eval as $item){
    //echo "Evaluando item $item";
    if ($learning_style_a[$item] == 0){
        $seq_glo["a"]++;
    }else{
        $seq_glo["b"]++;
    }
}

if ($seq_glo["a"]>$seq_glo["b"]) {
    $seq_glo["result"] = ($seq_glo["a"]-$seq_glo["b"])."a";
}else{
    $seq_glo["result"] = ($seq_glo["b"]-$seq_glo["a"])."b";
}

/*
echo "----- ".$act_ref["result"]." -----";
echo "----- ".$sen_int["result"]." -----";
echo "----- ".$vis_vrb["result"]." -----";
echo "----- ".$seq_glo["result"]." -----";
*/

/*if( $accept != 1 ){
    //No accept, redirect
    $redirect = new moodle_url('/blocks/learning_style/view.php', array('cid'=>$courseid,'error'=>'1'));
    redirect($redirect);
}
*/
$redirect = new moodle_url('/course/view.php', array('id'=>$courseid));

if(save_learning_style($courseid,$act_ref["result"],$sen_int["result"],$vis_vrb["result"],$seq_glo["result"],$act_ref["a"],$act_ref["b"],$sen_int["a"],$sen_int["b"],$vis_vrb["a"],$vis_vrb["b"],$seq_glo["a"],$seq_glo["b"])){
    redirect($redirect, get_string('redirect_accept_success', 'block_learning_style') );
}else{
    redirect($redirect, get_string('redirect_accept_exist', 'block_learning_style') );
}
?>
