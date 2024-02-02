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
 * The class for a database activity field type regular expression.
 *
 * @package    datafield_regex
 * @copyright  2024 Stephan Robotta <stephan.robotta@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class data_field_regex extends data_field_base {
    /**
     * Name of the field type.
     * @var string
     */
    public $type = 'regex';

    /**
     * Priority for globalsearch indexing.
     *
     * @var int
     */
    protected static $priority = self::MIN_PRIORITY;

    /**
     * Preview is supported.
     * @return bool
     */
    public function supports_preview(): bool {
        return true;
    }

    /**
     * Prints the respective type icon for the field type (when managing the fields).
     *
     * @return string
     */
    public function image() {
        global $OUTPUT;

        // When the cli/install.php was executed, the icon was linked/copied at this location.
        return $OUTPUT->pix_icon('field/regex', 'regex', 'data');
    }

    /**
     * Get the field name to be displayed in the list when adding/editing a field type.
     *
     * @return string
     * @throws coding_exception
     */
    public function get_name(): string
    {
        return get_string('fieldtypelabel', 'datafield_regex');
    }

    /**
     * Sample object for the preview.
     * @param int $recordid
     * @return stdClass
     */
    public function get_data_content_preview(int $recordid): stdClass {
        return (object)[
            'id' => 0,
            'fieldid' => $this->field->id,
            'recordid' => $recordid,
            'content' => '',
            'content1' => null,
            'content2' => null,
            'content3' => null,
            'content4' => null,
        ];
    }

    /**
     * This field just sets up a default field object
     *
     * @return bool
     */
    function define_default_field() {
        global $OUTPUT;
        if (empty($this->data->id)) {
            echo $OUTPUT->notification('Programmer error: dataid not defined in field class');
        }
        $this->field = new stdClass();
        $this->field->id = 0;
        $this->field->dataid = $this->data->id;
        $this->field->type = $this->type;
        $this->field->param1 = false; // Autolink.
        $this->field->param2 = false; // Case-sensitive.
        $this->field->param3 = '';    // The regex term.
        $this->field->param4 = false; // Partial match.
        $this->field->name = '';
        $this->field->description = '';
        $this->field->required = false;

        return true;
    }

    /**
     * Set up the field object according to data in an object.  Now is the time to clean it!
     *
     * @return bool
     */
    function define_field($data) {
        $this->field->type = $this->type;
        $this->field->dataid = $this->data->id;

        $this->field->name = trim($data->name);
        $this->field->description = trim($data->description);
        $this->field->required = !empty($data->required) ? 1 : 0;
        $this->field->param1 = !empty($data->param1) ? 1 : 0; // Autolink.
        $this->field->param2 = !empty($data->param2) ? 1 : 0; // Case-sensitive.
        if (isset($data->param3)) { // The regular expression term.
            $this->field->param3 = trim($data->param3);
        }
        $this->field->param4 = !empty($data->param4) ? 1 : 0; // Partial match.

        return true;
    }

    /**
     * The input field that is displayed in the advanced search.
     * @param string $value
     * @return string
     * @throws coding_exception
     */
    public function display_search_field($value = '') {
        return '<label class="accesshide" for="f_' . $this->field->id . '">' . get_string('fieldname', 'data') . '</label>' .
               '<input type="text" size="16" id="f_' . $this->field->id . '" '.
               ' name="f_' . $this->field->id . '" value="' . s($value) . '" class="form-control d-inline"/>';
    }

    /**
     * The search field parameter value, derived from the request.
     * @param array|null $defaults
     * @return mixed
     * @throws coding_exception
     */
    public function parse_search_field($defaults = null) {
        $param = 'f_'.$this->field->id;
        if (empty($defaults[$param])) {
            $defaults = [$param => ''];
        }
        return optional_param($param, $defaults[$param], PARAM_NOTAGS);
    }

    /**
     * Return the partial search sql when in advanced search the email field is filled with a search term.
     * @param string $tablealias
     * @param string $value
     * @return array
     */
    public function generate_sql($tablealias, $value) {
        global $DB;

        static $i = 0;
        $i++;
        $name = "df_email_$i";
        return [
            " ({$tablealias}.fieldid = {$this->field->id} AND "
                . $DB->sql_like("{$tablealias}.content", ":$name", false)
            . ') ',
            [$name => "%$value%"],
        ];
    }

    /**
     * Validate the submitted data against the regex pattern. The pattern must match the value
     * then the input is accepted.
     * If the value is empty, then it's not checked. This allows to submit fields that have the
     * regex field set not to be required. The non-empty check for required fields is done later.
     *
     * @param array $value
     * @return lang_string|string
     * @throws coding_exception
     */
    public function field_validation($value) {
        $val = '';
        if (\is_array($value) && isset($value[0]->value)) {
            $val = trim($value[0]->value);
        }
        if ($val !== '' && !preg_match($this->getPattern($this->field->param3), $val)) {
            return get_string('err_input', 'datafield_regex');
        }
        return '';
    }

    /**
     * Validates params of fieldinput data. Overwrite to validate fieldtype specific data.
     * Here we need to check whether the submitted regex is valid.
     *
     * @param stdClass $fieldinput The field input data to check
     * @return array $errors if empty validation was fine, otherwise contains one or more error messages
     */
    public function validate(stdClass $fieldinput): array {
        if (empty($fieldinput->param3)) {
            return ['param3' => get_string('regex_empty', 'datafield_regex')];
        }
        try {
            preg_match($this->getPattern($fieldinput->param3), '');
        } catch (\Exception $e) {
            return ['param3' => get_string('regex_invalid', 'datafield_regex')
                . ' - ' . $e->getMessage()];
        }
        return [];
    }

    /**
     * Create a valid pattern that can be used in the preg_match() function.
     * The user should enter the pattern without the delimiter. Depending on the
     * content we can use ~, |, or / as the delimiter. For the latter we need to check
     * whether any slashes need to be escaped.
     * @param string $pattern
     * @return string
     */
    protected function getPattern(string $pattern): string {
        $delimiter = '/';
        if (!str_contains($pattern, '~')) {
            $delimiter = '~';
        } elseif (!str_contains($pattern, '|')) {
            $delimiter = '|';
        } else {
            if (str_contains($pattern, '/')) {
                $newpattern = '';
                while (true) {
                    // Search for every /.
                    $p = strpos($pattern, '/');
                    if ($p === false) {
                        $newpattern .= $pattern;
                        break;
                    }
                    if ($p > 0) {
                        // Check the char preceeding the /, if this is a backslash it is already escaped.
                        // Otherwise, we need to escape it.
                        $newpattern .= substr($pattern, 0, $p);
                        if (substr($pattern, $p - 1, 1) !== '\\') {
                            $newpattern .= '\\';
                        }
                    } else { // Slash was at the beginning of the pattern, and the backslash to escape.
                        $newpattern .= '\\';
                    }
                    // Add the slash that we found before.
                    $newpattern .= '/';
                    // And reduce the pattern from what we have handled so far.
                    $pattern = substr($pattern, $p + 1);
                }
                $pattern = $newpattern;
            }
        }
        // If we match the whole string we need to add the line anchors ^ and $.
        if (!$this->field->param4) {
            $pattern = '^' . $pattern . '$';
        }
        // Add the delimiter.
        $pattern = $delimiter . $pattern . $delimiter;
        // If we do not match case-sensitive then add the i flag.
        if (!$this->field->param2) {
            $pattern .= 'i';
        }
        return $pattern;
    }
}
