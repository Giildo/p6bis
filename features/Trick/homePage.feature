@trick
@trick_home_page

Feature: As an visitor, anonymous or connected, I need to be able to see the list of the tricks.
  If I'm connected with user role, I need to be able to modify or to delete my trick.
  If I'm connected with administrator role, I need to be able to modify or to delete all tricks.

  Scenario: [Success] No trick in the database.
    Given I am on "/accueil"
    Then I should see "Désolé, aucune figure n'a été crée ou n'a été publiée."
    And I should be on "/accueil"

  Scenario: [Success] No trick published.
    Given I am on "/accueil"
    Then I should see "Désolé, aucune figure n'a été crée ou n'a été publiée."
    And I should be on "/accueil"

  Scenario: [Success] The list of tricks is displayed.
    Given I load the tricks with category and user
    And I am on "/accueil"
    Then I should see "Mute"
    And I should see "Truck"
    And I should be on "/accueil"

  Scenario: [Success] The user is connected with user role, he can edit and delete their tricks.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/accueil"
    Then I should see "Supprimer" "2" times
    And I should see "Modifier" "2" times
    And I should be on "/accueil"

  Scenario: [Success] The user is connected with admin role, he can edit and delete all tricks.
    Given I load the tricks with category and user
    And I am logged with username "JaneDoe" and with password "12345678"
    And I am on "/accueil"
    Then I should see "Supprimer" "10" times
    And I should see "Modifier" "10" times
    And I should be on "/accueil"
