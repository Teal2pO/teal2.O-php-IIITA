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
require_once($CFG->dirroot . '/local/teal/class/form/update_course.php');
require_once($CFG->dirroot . '/local/teal/helpers/github_actions.php');
require_login();

global $DB;

$PAGE->set_pagelayout('incourse');
$PAGE->requires->js('/local/teal/assets/update_course.js');
$PAGE->set_url(new moodle_url('/local/teal/update_course.php'));
$PAGE->set_title('Update Course');
$PAGE->set_heading('Update Course');

$githubActionHelper = new \GithubActions();
$mform = new UpdateCourse($_GET["id"]);

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/teal/dashboard/home.php', 'Oops course creation cancelled!');
} else if ($fromform = $mform->get_data()) {
    $course = new stdClass();
    $course->id = (int)$fromform->id;
    $course->course_category = $fromform->course_category;
    $course->course_type = $fromform->course_type;
    $course->course_credits = (int)$fromform->course_credits;
    $course->course_level = $fromform->course_level;
    $course->course_id = $fromform->course_id;
    if ($fromform->course_learning_outcomes and $fromform->course_learning_outcomes != '')
        $course->course_learning_outcomes = $fromform->course_learning_outcomes;
    $course->course_name = get_course($fromform->course_id)->fullname;
    $DB->update_record('teal_course_metadata', $course);
    $githubActionHelper->updateCourseDataInDatabases($course->id, $fromform->commit_message);
    $tealCourseId = $course->id;
    redirect($CFG->wwwroot . "/local/teal/course.php?id=" . $fromform->id, 'Course has been updated!');
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
