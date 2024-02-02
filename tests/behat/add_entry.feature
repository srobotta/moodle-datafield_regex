@mod @mod_data @datafield @datafield_regex
Feature: Users can use the datatype field regex and add an entry with that type.

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |
    And the following "activities" exist:
      | activity | name               | intro | course | idnumber |
      | data     | Test database name | n     | C1     | data1    |

  @javascript
  Scenario: Student can add an entry to a database with a valid value for a regex field.
    Given the following "mod_data > fields" exist:
      | database | type  | name        | description  | param3     | required |
      | data1    | regex | field regex | Descr. regex | foss(bar)? | 1        |
    When I am on the "Course 1" course page logged in as student1
    And I add an entry to "Test database name" database with:
      | field regex | |
    And I press "Save"
    Then I should see "You must supply a value here."
    And I should see "You did not fill out any fields!"
    And I set the field "field regex" to "invalid"
    And I press "Save"
    Then I should see "Your input didn't match the expected pattern."
    And I set the field "field regex" to "fossbar"
    And I press "Save"
    Then I should see "field regex"
    And I should see "Last edited:"
    And I should not see "Your input didn't match the expected pattern."

