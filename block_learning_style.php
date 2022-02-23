<?php

class block_learning_style extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_learning_style');
    }

    /*function has_config() {
        return false;
    }*/

    function instance_allow_multiple() {
        return false;
    }

    /*function instance_allow_config() {
        return false;
    }*/

    function get_content() {

        global $OUTPUT,$CFG, $DB, $USER, $COURSE,$SESSION;

        if ($COURSE->id == SITEID) {
            return;
        }

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }

        if( !isloggedin() ){
            return;
        }
        
        /*$redirect = new moodle_url('/blocks/learning_style/view.php', array('cid' => $COURSE->id));
        redirect($redirect);*/


        $COURSE_ROLED_AS_STUDENT = $DB->get_record_sql("  SELECT m.id
                FROM {user} m 
                LEFT JOIN {role_assignments} m2 ON m.id = m2.userid 
                LEFT JOIN {context} m3 ON m2.contextid = m3.id 
                LEFT JOIN {course} m4 ON m3.instanceid = m4.id 
                WHERE (m3.contextlevel = 50 AND m2.roleid IN (5) AND m.id IN ( {$USER->id} )) AND m4.id = {$COURSE->id} ");

        //Check if user is student
        if (isset($COURSE_ROLED_AS_STUDENT->id) && $COURSE_ROLED_AS_STUDENT->id) {
            //check if user already have the learning style
            $entry = $DB->get_record('learning_style', array('user' => $USER->id, 'course' => $COURSE->id));
            if (!$entry) {
                if( isset($this->config->learning_style_content) && isset($this->config->learning_style_content["text"]) ){
                    $SESSION->learning_style = $this->config->learning_style_content["text"];
                    $redirect = new moodle_url('/blocks/learning_style/view.php', array('cid' => $COURSE->id));
                    redirect($redirect);
                }
            }else{
                $this->content->text = "<br><img src='".$OUTPUT->pix_url('ok', 'block_learning_style')."'>".get_string('accept_message', 'block_learning_style');
                $this->content->text .= "<br><br>Activo Reflexivo: ".$entry->act_ref;
                $this->content->text .= "<br>Sensitivo Intuitivo: ".$entry->sen_int;
                $this->content->text .= "<br>Visual Verbal: ".$entry->vis_vrb;
                $this->content->text .= "<br>Secuencial Global: ".$entry->seq_glo;
            }
        }else{
            if( isset($this->config->learning_style_content) && isset($this->config->learning_style_content["text"]) ){
                $this->content->text = "<img src='".$OUTPUT->pix_url('ok', 'block_learning_style')."'>".get_string('learning_style_actived', 'block_learning_style');
            }else{
                $this->content->text = "<img src='".$OUTPUT->pix_url('warning', 'block_learning_style')."'>".get_string('learning_style_configempty', 'block_learning_style');
            }
        }
        return $this->content;
        
    }

}

