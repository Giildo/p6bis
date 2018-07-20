@trick
@trick_home_page

Feature: As an visitor, anonymous or connected, I need to be able to see the list of the tricks.
  If I'm connected with user role, I need to be able to modify or to delete my tricks.
  If I'm connected with administrator role, I need to be able to modify or to delete all tricks.

  Scenario: [Success] No trick in the database.
    Given I am on "/accueil"
    Then I should see "Désolé, aucune figure n'a été crée ou n'a été publiée."

  Scenario: [Success] No trick published.
    Given I am on "/accueil"
    Then I should see "Désolé, aucune figure n'a été crée ou n'a été publiée."

  Scenario: [Success] The list of tricks is displayed.
    Given I load the tricks with category and user
    And I am on "/accueil"
    Then I should see "Mute"
    And I should see "Truck"