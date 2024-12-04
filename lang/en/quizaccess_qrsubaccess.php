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
 * Strings for the quizaccess_qrsubaccess plugin.
 *
 * @package   quizaccess_qrsubaccess
 * @copyright 2011 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

$string['qrsubaccessheader'] = 'Please read the following message';
$string['qrsubaccesslabel'] = 'I have read and agree to the above statement.';
$string['qrsubaccessrequired'] = 'Disable the time limit during file submission if the exam contains hybrid question?';
$string['qrsubaccessrequired_help'] = '<p>This option disable the time limit during the file submission. Please note that the timer is not reseted during the file submission.</p><p>If the time limit is set to 1h and the student takes 55 minutes to complete the first attempt then there will be only 5 minutes left to submit their file.</p><p>If the timer runs out during the submission, the exam will be automatically submitted and closed.</p>';
$string['yesdisabletimer'] = 'Yes, disable the timer during file submission';
$string['nokeeptimer'] = 'No, keep the timer running during file submission';
$string['qrsubaccessstatement'] = 'I understand that it is important that the attempt I am about to make is all my own work. I understand what constitutes plagiarism or cheating, and I will not undertake such activities.';
$string['pluginname'] = 'QR Code file submission access rule';
$string['youmustagree'] = 'You must agree to this statement before you start the quiz.';
$string['privacy:metadata'] = 'The Acknowledge plagiarism statement access rule plugin does not store any personal data.';
