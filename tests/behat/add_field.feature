@mod @mod_data @datafield @datafield_regex
Feature: Teachers can create a new datatype field regex

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher1 | One | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role           |
      | teacher1 | C1 | editingteacher |
    And the following "activities" exist:
      | activity | name               | intro | course | idnumber |
      | data     | Test database name | n     | C1     | data1    |

  @javascript
  Scenario: Teacher can create fields, one of the type regex
    When I am on the "Course 1" course page logged in as teacher1
    And I add a "Regex" field to "Test database name" database and I fill the form with:
      | Field name         | testfield        |
      | Field description  | Test Regex Field |
      | Regular expression | tes(t\|x)+       |
    Then I should see "testfield"
    And I should see "Test Regex Field"
    And I should see "Regular expression"
    When I am on the "Course 1" course page logged in as teacher1
    And I add an entry to "Test database name" database with:
      | testfield | tesxxx! |
    And I press "Save"
    Then I should see "tesxxx!"
