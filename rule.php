<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Implementaton of the quizaccess_qrsubaccess plugin.
 *
 * @package   quizaccess_qrsubaccess
 * @copyright 2011 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');

use local_qrsub\local\qrsub;

/**
 * A rule requiring the student to promise not to cheat.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_qrsubaccess extends quiz_access_rule_base {

    /**
     * It is possible for one rule to override other rules.
     *
     * The aim is that third-party rules should be able to replace sandard rules
     * if they want. See, for example MDL-13592.
     *
     * @return array plugin names of other rules that this one replaces.
     *      For example array('ipaddress', 'password').
     */
    public function get_superceded_rules() {
        global $USER;


        //////////////////////////////////
        // QRMOOD-33 -As a student, I don't want quiz timer when uploading my files.
        //
        // If the exam is proctored, don't supercede any rules.
        // If all questions state are todo we assume that it is the first attempt, we keep the timer.
        // If at least one question has an answer, we assume it is the second attempt, we remove the timer.

        // Containes the rules to supercede.
        $supercededrules = array();

        // Make sure we keep the timer if we are in proctored attempt.
        $userattempts = quiz_get_user_attempts($this->quizobj->get_quizid(), $USER->id, 'all', true);
        $userattempt = end($userattempts);
        if ($userattempt) {
            $attemptobj = quiz_attempt::create($userattempt->id);
            list($is_proctored, $upload_exam) = qrsub::is_exam_protored($attemptobj);
            if ($is_proctored) {
                return $supercededrules;
            }
        }

        // Check if at least one question has an answer if so, superced the timer.
        $question_has_answer = qrsub::question_attempt_has_answers($this->quizobj);
        if ($question_has_answer) {
            $supercededrules[] = 'timelimit';
        }
        // QRMOOD-33

        return $supercededrules;
    }

    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {

        if (empty($quizobj->get_quiz()->qrsubaccessrequired)) {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    /**
     * Adds the select menu in the quiz settings.
     */
    public static function add_settings_form_fields(
        mod_quiz_mod_form $quizform, MoodleQuickForm $mform
    ) {
        $mform->insertElementBefore($mform->createElement(
            'select',
            'qrsubaccessrequired',
            get_string('qrsubaccessrequired', 'quizaccess_qrsubaccess'),
            array(
                0 => get_string('nokeeptimer', 'quizaccess_qrsubaccess'),
                1 => get_string('yesdisabletimer', 'quizaccess_qrsubaccess'),
            )
        ), 'quizpassword');

        $mform->addHelpButton(
            'qrsubaccessrequired', 'qrsubaccessrequired', 'quizaccess_qrsubaccess'
        );
    }

    /**
     * Saves the settings in the quizaccess_qrsubaccess table.
     */
    public static function save_settings($quiz) {
        global $DB;
        if (empty($quiz->qrsubaccessrequired)) {
            $DB->delete_records('quizaccess_qrsubaccess', array('quizid' => $quiz->id));
        } else {
            if (!$DB->record_exists('quizaccess_qrsubaccess', array('quizid' => $quiz->id))) {
                $record = new stdClass();
                $record->quizid = $quiz->id;
                $record->qrsubaccessrequired = 1;
                $DB->insert_record('quizaccess_qrsubaccess', $record);
            }
        }
    }

    public static function delete_settings($quiz) {
        global $DB;
        $DB->delete_records('quizaccess_qrsubaccess', array('quizid' => $quiz->id));
    }

    public static function get_settings_sql($quizid) {
        return array(
            'qrsubaccessrequired',
            'LEFT JOIN {quizaccess_qrsubaccess} qrsubaccess ON qrsubaccess.quizid = quiz.id',
            array());
    }
}
