<?php
require_once(dirname(__FILE__) . '/lib.php');

if( !isloggedin() ){
            return;
}

$courseid = optional_param('cid', 0, PARAM_INT);
$error  = optional_param('error', 0, PARAM_INT);

if ($courseid == SITEID && !$courseid) {
    redirect($CFG->wwwroot);
}

/*if (!isset($SESSION->honorcodetext)) {
    redirect(new moodle_url('/course/view.php', array('id' => $courseid)));
}*/

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$PAGE->set_course($course);
$context = $PAGE->context;
  
$PAGE->set_url('/blocks/learning_style/view.php', array('cid'=>$courseid));

$title = get_string('pluginname', 'block_learning_style');

$PAGE->set_pagelayout('print');
$PAGE->set_title($title." : ".$course->fullname);
$PAGE->set_heading($title." : ".$course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->box_start('generalbox');
echo "<h1 class='title_learning_style'>Primer paso, vamos a conocer tu estilo de aprendizaje</h1>";
echo "
<p>
¡Bienvenido al curso de Refuerzo en Fundamentos de Programación!<br>
<br>
Acá tendrás a disposición herramientas adicionales y los conocimientos fundamentales 
necesarios para que te conviertas en un buen programador, independientemente de la carrera que estudies. 
Nuestro objetivo principal es proporcionarte una experiencia de aprendizaje personalizada y significativa 
que te permita alcanzar tus metas profesionales y académicas.<br>
<br>
Antes de empezar, te invitamos a realizar el siguiente test sobre tu estilo de aprendizaje y preferencia de recursos de aprendizaje. 
Esto nos permitirá conocerte y recomendarte mejor los recursos a los que tendrás acceso en el curso.<br>
<br>
Comencemos!
</p>
";
$action_form = new moodle_url('/blocks/learning_style/save.php');
?>

<form method="POST" action="<?php echo $action_form ?>" >
    <div class="content-accept <?php echo ($error)?"error":"" ?>">
        <?php if($error): ?>
            <p class="error"><?php echo get_string('required_message', 'block_learning_style') ?></p>
        <?php endif; ?>

        <ol class="learning_style_q">
        <?php for ($i=1;$i<=44;$i++){ ?>
        

        <li class="learning_style_item"><?php echo get_string("learning_style:q".$i, 'block_learning_style') ?>
        <select name="learning_style:q<?php echo $i; ?>" required>
            <option value="" disabled selected hidden>Selecciona</option>
            <option value="0"><?php echo get_string('learning_style:q'.$i.'_a', 'block_learning_style') ?></option>
            <option value="1"><?php echo get_string('learning_style:q'.$i.'_b', 'block_learning_style') ?></option>
        </select>
        </li>
        <?php } ?>
        </ol>
        <div class="clearfix"></div>
        <input class="btn" type="submit" value="<?php echo get_string('submit_text', 'block_learning_style') ?>" >
    
    </div>
    
    <input type="hidden" name="cid" value="<?php echo $courseid ?>">
    <div class="clearfix"></div>
    
</form>

<?php

echo $OUTPUT->box_end();
echo $OUTPUT->footer();