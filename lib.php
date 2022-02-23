<?php 

require_once(dirname(__FILE__) . '/../../config.php');

function save_learning_style($course,$act_ref,$sen_int,$vis_vrb,$seq_glo) {
    GLOBAL $DB, $USER, $CFG;
    if (!$entry = $DB->get_record('learning_style', array('user' => $USER->id, 'course' => $course))) {
        $entry = new stdClass();
        $entry->user = $USER->id;
        $entry->course = $course;
        $entry->state = "1";
        $entry->act_ref = $act_ref;
        $entry->sen_int = $sen_int;
        $entry->vis_vrb = $vis_vrb;
        $entry->seq_glo = $seq_glo;
        $entry->created_at = time();
        $entry->updated_at = time();
        $entry->id = $DB->insert_record('learning_style', $entry);
        return true;
    }else{
        return false;
    }
}

