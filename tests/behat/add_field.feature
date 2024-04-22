@mod @mod_data @datafield @datafield_regex
Feature: Teachers can create a new datatype regex field

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | One      | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following "activities" exist:
      | activity | name               | intro | course | idnumber |
      | data     | Test database name | n     | C1     | data1    |

  @javascript
  Scenario: Teacher can create fields, one of the type regex
    When I am on the "Course 1" course page logged in as teacher1
    And I am on the "Test database name" "data activity" page logged in as teacher1
    And I navigate to "Fields" in current page administration
    And I click on "Create a field" "button"
    And I click on "Regex" "link" in the "#action_bar" "css_element"
    And I set the following fields to these values:
      | Field name         | testfield        |
      | Field description  | Test Regex Field |
      | Regular expression | tes(t\|x)+       |
      | Only partial match | 1                |
    And I press "Save"
    Then I should see "testfield"
    And I should see "Test Regex Field"
    And I should see "Regular expression"
    When I am on the "Test database name" "mod_data > add entry" page logged in as teacher1
    And I set the following fields to these values:
      | testfield | tesxxx! |
    And I press "Save"
    Then I should see "tesxxx!"
