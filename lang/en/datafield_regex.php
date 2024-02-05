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
 * Strings for component 'datafield_regex', language 'en', branch 'master'
 *
 * @package    datafield_regex
 * @copyright  2024 Stephan Robotta <stephan.robotta@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Regex';
$string['casesensitive'] = 'Case-sensitive';
$string['casesensitive_note'] = 'Check this if you want to match case-sensitive strings. By default all cases are matched.';
$string['custom_err'] = 'Custom error message';
$string['custom_err_note'] = 'Show this custom error message instead of a generic one, when the input does not match the regular expression. This can be a hint to explain briefly what input is expected. Language tags can be used here.';
$string['err_input'] = 'Your input didn\'t match the expected pattern.';
$string['fieldtypelabel'] = 'Regular expression';
$string['partialmatch'] = 'Only partial match';
$string['partialmatch_note'] = 'By default the complete input must match the regular expression. The line anchors ^ and $ are used to match from start to end. By checking this, the match would be partial only, without the line anchors.';
$string['regex_empty'] = 'Regular expression is empty';
$string['regex_invalid'] = 'Regular expression is invalid';
$string['regex_note'] = 'Please enter the regex without any delimiter char (e.g. /) at the beginning and end of the expression.';
$string['privacy:metadata'] = 'The datafield regex plugin doesn\'t store any personal data; it uses tables defined in mod_data.';
