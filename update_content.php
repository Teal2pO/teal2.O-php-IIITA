<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @package     local_teal
 * @author      abhiandthetruth
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * 
 * 
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/teal/class/form/update_content.php');
require_once($CFG->dirroot . '/local/teal/helpers/github_actions.php');
require_login();

global $DB;

$PAGE->set_pagelayout('incourse');
// $PAGE->requires->js('/local/teal/assets/update_content.js');
$PAGE->set_url(new moodle_url('/local/teal/update_content.php'));
$PAGE->set_title('Update Content');
$PAGE->set_heading('Update Content');

$githubActionHelper = new \GithubActions();
$mform = new UpdateContent($_GET["id"]);

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/teal/dashboard/home.php', 'Oops content updation cancelled!');
} else if ($fromform = $mform->get_data()) {
    $content = $DB->get_record('teal_course_content_metadata', ["id" => $fromform->id]);
    $githubActionHelper->updateCourseContent($content, $fromform->message);
    redirect($CFG->wwwroot . "/local/teal/content.php?id=" . $fromform->id, 'Content has been updated!');
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
