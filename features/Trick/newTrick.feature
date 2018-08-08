@trick
@trick_new_trick

Feature: As an visitor connected, I need to be able to add a new trick in the database.

  Scenario: [Fail] The user is redirected if ins't connected.
    Given I am on "/espace-utilisateur/nouvelle-figure"
    Then I should be on "/connexion"


  Scenario: [Fail] The user is connected, he doesn't submit data in the form.
    Given I load following file "/user/01.specific_user.yml"
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/nouvelle-figure"
    When I press "Valider"
    Then I should see "Le nom de la figure doit être renseigné."
    And I should see "La description de la figure doit être renseignée."
    And I should be on "/espace-utilisateur/nouvelle-figure"


  Scenario: [Fail] The user is connected, he doesn't submit data in the form.
    Given I load following file "/user/01.specific_user.yml"
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/nouvelle-figure"
    When I press "Valider"
    Then I should see "Le nom de la figure doit être renseigné."
    And I should see "La description de la figure doit être renseignée."
    And I should be on "/espace-utilisateur/nouvelle-figure"


  Scenario: [Fail] The user is connected, he submits too short data in the form.
    Given I load following file "/user/01.specific_user.yml"
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/nouvelle-figure"
    When I fill in the following:
      | new_trick_name        | j |
      | new_trick_description | j |
    And I press "Valider"
    Then I should see "Le nom de la figure doit avoir au moins 3 caractères."
    And I should see "La description doit contenir au moins 5 caractères."
    And I should be on "/espace-utilisateur/nouvelle-figure"


  Scenario: [Fail] The user is connected, he submits too long data in the form.
    Given I load following file "/user/01.specific_user.yml"
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/nouvelle-figure"
    When I fill in "new_trick_name" with "012345678901234567890123456789012345678901234567891"
    And I press "Valider"
    Then I should see "Le nom de la figure ne doit pas avoir plus de 50 caractères."
    And I should be on "/espace-utilisateur/nouvelle-figure"

  Scenario: [Success] The user is connected, he submits good data in the form.
    Given I load following file "/user/01.specific_user.yml"
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/espace-utilisateur/nouvelle-figure"
    When I fill in the following:
      | new_trick_name        | Indy |
      | new_trick_description | Description de la figure |
    And I check "new_trick_published"
    And I press "Valider"
    Then I should be on "/trick/mute"
