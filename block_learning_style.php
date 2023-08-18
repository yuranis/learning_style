<?php

class block_learning_style extends block_base
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_learning_style');
    }

    /*function has_config() {
        return false;
    }*/

    function instance_allow_multiple()
    {
        return false;
    }

    function my_slider($value, $izq_val, $der_val, $izq_title, $der_title)
    {
        global $OUTPUT;

        $slider = '';
        $slider .= '<div class="slider-container" style="text-align:center">';

        if ($value >= 0 ){
            $slider .= "<span title='$izq_title'>$izq_val</span> ⇄ <strong title='$der_title'> $der_val </strong><br>";
            $slider .= '<input type="range" class="alpy" name="LearningStyle" min="-11" max="11" value="' . $value . '" disabled>';
        }else {
            $slider .= "<strong title='$izq_title'>$izq_val</strong> ⇄ <span title='$der_title'> $der_val </span><br>";
            $slider .= '<input type="range" class="alpy" name="LearningStyle" min="-11" max="11" value="' . $value . '" disabled>';
        }
        $slider .= '</div>';
        return $slider;
    }

    /*function instance_allow_config() {
        return false;
    }*/

    function get_content()
    {

        global $OUTPUT, $CFG, $DB, $USER, $COURSE, $SESSION;

        if ($COURSE->id == SITEID) {
            return;
        }

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = "";
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }

        if (!isloggedin()) {
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
                if (isset($this->config->learning_style_content) && isset($this->config->learning_style_content["text"])) {
                    $SESSION->learning_style = $this->config->learning_style_content["text"];
                    $redirect = new moodle_url('/blocks/learning_style/view.php', array('cid' => $COURSE->id));
                    redirect($redirect);
                }
            } else {
                /**
                 * 
                 */

                $final_style = [];

                $izq_title = "Activo: te sugiero utilizar actividades prácticas, resolución de problemas, realizar experimentos, proyectos prácticos, participar en discusiones grupales, trabajar en grupos.";
                $der_title = "Reflexivo: te sugiero desarrollar lecturas reflexivas, tomar notas y reflexionar sobre el material de aprendizaje, crear diagramas y organizar información, tomarse el tiempo para considerar las opciones antes de tomar decisiones, actividades de análisis de casos y actividades de autoevaluación.";
                if ($entry->act_ref[1] == 'a') {

                    $final_style[$entry->act_ref[0] . "ar"] = $this->my_slider($entry->act_ref[0] * -1, get_string("active", 'block_learning_style'), get_string("reflexive", 'block_learning_style'),$izq_title,$der_title);
                    $final_style[$entry->act_ref[0] . "ar"] .= "<p class='explain'>$izq_title</p>";
                } else {
                    $final_style[$entry->act_ref[0] . "ar"] = $this->my_slider($entry->act_ref[0], get_string("active", 'block_learning_style'), get_string("reflexive", 'block_learning_style'),$izq_title,$der_title);
                    $final_style[$entry->act_ref[0] . "ar"] .= "<p class='explain'>$der_title</p>";
                }

                $izq_title = "Sensitivo: te sugiero realizar una observación detallada y aplicación práctica de conceptos, utilizar ejemplos concretos y aplicaciones prácticas del material de aprendizaje, realizar actividades de laboratorio y proyectos. Desarrollar trabajo práctico. ";
                $der_title = "Intuitivo: te sugiero utilizar buscar conexiones y patrones en la información, utilizar analogías e historias para ilustrar los conceptos, hacer preguntas y explorar nuevas ideas. Actividades como la resolución de problemas complejos, actividades creativas y discusiones teóricas.";
                if ($entry->sen_int[1] == 'a') {
                    $final_style[$entry->sen_int[0] . "si"] = $this->my_slider($entry->sen_int[0] * -1, get_string("sensitive", 'block_learning_style'), get_string("intuitive", 'block_learning_style'),$izq_title,$der_title);
                    $final_style[$entry->sen_int[0] . "si"] .= "<p class='explain'>$izq_title</p>";
                } else {
                    $final_style[$entry->sen_int[0] . "si"] = $this->my_slider($entry->sen_int[0], get_string("sensitive", 'block_learning_style'), get_string("intuitive", 'block_learning_style'),$izq_title,$der_title);
                    $final_style[$entry->sen_int[0] . "si"] .= "<p class='explain'>$der_title</p>";
                }

                $izq_title = "Visual: te sugiero utilizar gráficos, diagramas, videos y otros recursos visuales para representar la información, realizar mapas mentales y dibujar imágenes para comprender el material. ";
                $der_title = "Verbal: te sugiero leer y escribir notas, desarrollar resúmenes del material, discutir el material en grupos o con un compañero de estudio, utilizar técnicas de memorización como la repetición verbal, discusiones o explicaciones verbales.";
                if ($entry->vis_vrb[1] == 'a') {
                    $final_style[$entry->vis_vrb[0] . "vv"] = $this->my_slider($entry->vis_vrb[0] * -1, get_string("visual", 'block_learning_style'), get_string("verbal", 'block_learning_style'),$izq_title,$der_title);
                    $final_style[$entry->vis_vrb[0] . "vv"] .= "<p class='explain'>$izq_title</p>";
                } else {
                    $final_style[$entry->vis_vrb[0] . "vv"] = $this->my_slider($entry->vis_vrb[0], get_string("visual", 'block_learning_style'), get_string("verbal", 'block_learning_style'),$izq_title,$der_title);
                    $final_style[$entry->vis_vrb[0] . "vv"] .= "<p class='explain'>$der_title</p>";
                }

                $izq_title = "Secuencial: te sugiero seguir una estructura lógica y organizada para aprender, tomar notas y resumir el material de aprendizaje, trabajar, analizar a través de pasos a pasos para resolver problemas.";
                $der_title = "Global: te sugiero buscar conexiones y patrones en la información, trabajar con el material de aprendizaje en su conjunto antes de enfocarse en los detalles, utilizar analogías y metáforas para ilustrar los conceptos. Trabajar en actividades que permiten la exploración y conexión de conceptos, aprendizaje basado en proyectos y discusión de temas complejos.";
                if ($entry->seq_glo[1] == 'a') {
                    $final_style[$entry->seq_glo[0] . "sg"] = $this->my_slider($entry->seq_glo[0] * -1, get_string("sequential", 'block_learning_style'), get_string("global", 'block_learning_style'),$izq_title,$der_title);
                    $final_style[$entry->seq_glo[0] . "sg"] .= "<p class='explain'>$izq_title</p>";
                } else {
                    $final_style[$entry->seq_glo[0] . "sg"] = $this->my_slider($entry->seq_glo[0], get_string("sequential", 'block_learning_style'), get_string("global", 'block_learning_style'),$izq_title,$der_title);
                    $final_style[$entry->seq_glo[0] . "sg"] .= "<p class='explain'>$der_title</p>";
                }

                krsort($final_style);

                $this->content->text .= "<p class='alpyintro'>Según el modelo de Estilos de Aprendizaje de Felder y Soloman, toda persona tiene mayor inclinación a un estilo u otro. En tu caso, el estilo que más predomina es:</p>";
                $this->content->text .= "<ul class='lsorder'>";
                foreach ($final_style as $key => $val) {
                    $this->content->text .= "<li>$val</li>";
                }
                $this->content->text .= "</ul><p class='lsorder'><i class='explain2'>*Visualiza tus otros estilos pasando el cursor sobre aquellos marcados en negrilla.</i></p>";
            }
        } else {
            if (isset($this->config->learning_style_content) && isset($this->config->learning_style_content["text"])) {
                $this->content->text = "<img src='" . $OUTPUT->pix_url('ok', 'block_learning_style') . "'>" . get_string('learning_style_actived', 'block_learning_style');
            } else {
                $this->content->text = "<img src='" . $OUTPUT->pix_url('warning', 'block_learning_style') . "'>" . get_string('learning_style_configempty', 'block_learning_style');
            }
        }

        return $this->content;
    }
}
