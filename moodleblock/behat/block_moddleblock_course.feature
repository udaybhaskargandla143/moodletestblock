@block @block_moodleblock
Feature: Moodle Test block used in a course
  In order to help particpants know the activities completion status of a course
  As a teacher
  I can add the moodle test block to a course page

  Background:
    Given the following "Activities" exist:
      | cmid | activityname | dateofcreation | completionstatus |
      | 1234 | Assignment      | 20-10-2022 |Completed     |

	Admin or teacher can add block on frontpage:
    And I enable "course_summary" "block" plugin
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add the "Course/moodleblock" block
    And I log out

  Scenario: Student can view course summary
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "moodle test block" "block" should exist
    And I should see "Activities list and status of the activities" in the "Moodle test" "block"
    And I should see "Proved the activities list and status block works!" in the "Moodle test" "block"

  Scenario: Teacher can not see edit icon when edit mode is off
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should see "Activities list and status of the activities" in the "Moodle test" "block"
    And I should see "Activities list and status of the activities" in the "Moodle test" "block"
    And "Edit" "link" should not exist in the "Moodle test" "block"
