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

namespace datafield_regex;

/**
 * PHPUnit data generator testcase.
 *
 * @package    datafield_regex
 * @category   phpunit
 * @copyright  2024 Stephan Robotta <stephan.robotta@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @coversDefaultClass \data_field_regex
 */
class data_field_regex_test extends \advanced_testcase
{
    /**
     * @var \mod_data_generator
     */
    protected $generator;

    /**
     * @var \stdClass
     */
    protected $data;

    /**
     * Test validation of regular expressions when submitting database entry values.
     * @covers \data_field_regex::validate
     */
    public function test_field_validation() {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();

        $this->generator = $this->getDataGenerator()->get_plugin_generator('mod_data');
        $this->data = $this->generator->create_instance(['course' => $course->id]);

        $nomatch = get_string('err_input', 'datafield_regex');

        $field1 = $this->create_field_regex('tes(t|x)+');
        $this->assertEmpty($field1->field_validation([(object)['value' => 'tesxxx']]));
        $this->assertEmpty($field1->field_validation([(object)['value' => 'teStT']]));
        $this->assertEmpty($field1->field_validation([(object)['value' => '']]));
        $this->assertEquals($nomatch, $field1->field_validation([(object)['value' => 'tesxxx!']]));
        $this->assertEquals($nomatch, $field1->field_validation([(object)['value' => 'fetesxx']]));

        $field2 = $this->create_field_regex('tes(t|x)+', true);
        $this->assertEmpty($field2->field_validation([(object)['value' => 'tesxxx']]));
        $this->assertEquals($nomatch, $field2->field_validation([(object)['value' => 'teStT']]));
        $this->assertEmpty($field2->field_validation([(object)['value' => '']]));
        $this->assertEquals($nomatch, $field1->field_validation([(object)['value' => 'tesxxx!']]));
        $this->assertEquals($nomatch, $field1->field_validation([(object)['value' => 'fetesxx']]));

        $field3 = $this->create_field_regex('tes(t|x)+', false, true);
        $this->assertEmpty($field3->field_validation([(object)['value' => 'tesxxx']]));
        $this->assertEmpty($field3->field_validation([(object)['value' => 'teStT']]));
        $this->assertEmpty($field3->field_validation([(object)['value' => '']]));
        $this->assertEmpty($field3->field_validation([(object)['value' => 'tesxxx!']]));
        $this->assertEmpty($field3->field_validation([(object)['value' => 'fetesxx']]));

        $field4 = $this->create_field_regex('^(/home|~)/([a-z]+/?)+', false, true);
        $this->assertEmpty($field4->field_validation([(object)['value' => '/home/server']]));
        $this->assertEmpty($field4->field_validation([(object)['value' => '~/foo']]));
        $this->assertEmpty($field4->field_validation([(object)['value' => '~/foo/bar']]));
        $this->assertEmpty($field4->field_validation([(object)['value' => '~/foo/bar/baz']]));

        $field5 = $this->create_field_regex('^(\/home|~)\/([a-z]+/?)+', false, true);
        $this->assertEmpty($field5->field_validation([(object)['value' => '/home/server']]));
        $this->assertEmpty($field5->field_validation([(object)['value' => '~/foo']]));
        $this->assertEmpty($field5->field_validation([(object)['value' => '~/foo/bar']]));
        $this->assertEmpty($field5->field_validation([(object)['value' => '~/foo/bar/baz']]));
    }

    /**
     * Test validation of regex when a new database field is created.
     * @return void
     * @throws \coding_exception
     * @throws \moodle_exception
     * @covers \data_field_regex::validate()
     */
    public function test_validate() {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();

        $this->generator = $this->getDataGenerator()->get_plugin_generator('mod_data');
        $this->data = $this->generator->create_instance(['course' => $course->id]);
        $field = new \data_field_regex(0, $this->data);
        $errors = $field->validate((object)['param3' => '^(/home|~)/([a-z]+/?)+']);
        $this->assertEmpty($errors);
        $errors = $field->validate((object)['']);
        $this->assertArrayHasKey('param3', $errors);
        $this->assertEquals(get_string('regex_empty', 'datafield_regex'), $errors['param3']);
        $errors = $field->validate((object)['param3' => 'test(foo']);
        $this->assertArrayHasKey('param3', $errors);
        $this->assertTrue(str_starts_with($errors['param3'], get_string('regex_invalid', 'datafield_regex')));

    }

    /**
     * Creates a data field of type regex.
     *
     * @param string $regex The regular expression pattern to be used for validation.
     * @param bool|null $casesensitive (Optional) Indicates whether the regular expression should be case-sensitive.
     *                                Default is false.
     * @param bool|null $partialmatch (Optional) Indicates whether the partial matching should be enabled.
     *                                Default is false.
     * @return \data_field_regex The created data field with the specified regex validation.
     * @throws \coding_exception If there is an error while creating the data field.
     */
    protected function create_field_regex(string $regex,
                                          ?bool $casesensitive = false,
                                          ?bool $partialmatch = false): \data_field_regex {
        $record = (object)[
            'type' => 'regex',
            'required' => true,
            'param1' => 0,
            'param2' => $casesensitive,
            'param3' => $regex,
            'param4' => $partialmatch,
        ];
        $field = $this->generator->create_field($record, $this->data);
        return $field;
    }
}