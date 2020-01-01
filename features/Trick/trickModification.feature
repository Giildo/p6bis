@trick
@trick_trick_modification

Feature: As an visitor connected, I need to be able to update the details of a trick.
  If I'm connected with user role, I need to be able to modify my trick.
  If I'm connected with administrator role, I need to be able to modify all tricks.

  Scenario: [Fail] The user is redirected to the login page if he isn't connected.
    Given I am on "/espace-utilisateur/trick/modification/badSlug"
    Then I should be on "/connexion"

  Scenario: [Fail] The user is redirected if the slug in URI is wrong.
    Given I load a specific user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/trick/modification/badSlug"
    Then I should be on "/accueil"

  Scenario: [Fail] The user is connected, he doesn't submit data in the trick description.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/trick/modification/mute"
    When I fill in "trick_modification_description" with ""
    And I press "Modifier la trick"
    Then I should be on "/espace-utilisateur/trick/modification/mute"
    And I should see "La description de la figure doit être renseignée."

  Scenario: [Fail] The user is connected, he submits a too short data.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/trick/modification/mute"
    When I fill in "trick_modification_description" with "1"
    And I press "Modifier la trick"
    Then I should be on "/espace-utilisateur/trick/modification/mute"
    And I should see "La description doit contenir au moins 5 caractères."

  Scenario: [Success] The user is connected, he modifies the trick.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/trick/modification/mute"
    When I fill in "trick_modification_description" with "Nouvelle description de la figure."
    And I press "Modifier la trick"
    Then I should be on "/trick/mute/ComPage"
    And I should see "Nouvelle description de la figure."