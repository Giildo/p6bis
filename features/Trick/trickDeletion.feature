@trick
@trick_trick_deletion

Feature: As an visitor connected, I need to be able to update the details of a trick.
  If I'm connected with user role, I need to be able to delete my trick.
  If I'm connected with administrator role, I need to be able to delete all tricks.

  Scenario: [Fail] The user is redirected to the login page if he isn't connected.
    Given I am on "/espace-utilisateur/trick/suppression/badSlug"
    Then I should be on "/connexion"

  Scenario: [Fail] The user is redirected if the slug in URI is wrong.
    Given I load a specific user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/trick/suppression/badSlug"
    Then I should be on "/accueil"

  Scenario: [Success] The user is connected, he doesn't submit data in the trick description.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/trick/suppression/mute"
    Then I should be on "/accueil"