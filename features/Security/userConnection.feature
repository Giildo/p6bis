@security
@security_connection

Feature: As an anonymous user, I need to be able to logged in on application.

  Scenario: [Fail] The User is redirected if he is already logged in.
    Given I load a specific user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/connexion"
    Then I should be on "/accueil"

  Scenario: [Fail] The User submit the form without data.
    Given I am on "/connexion"
    When I press "Se connecter"
    Then I should see "Le nom d'utilisateur doit être renseigné"
    And I should see "Le mot de passe doit être renseigné"
    And I should be on "/connexion"

  Scenario: [Fail] The User submit the form with too short username and password.
    Given I am on "/connexion"
    When I fill in the following:
      | user_connection_username | j |
      | user_connection_password | j |
    And I press "Se connecter"
    Then I should see "Le nom d'utilisateur doit avoir au moins 5 caractères."
    And I should see "Le mot de passe doit avoir au moins 8 caractères."
    And I should be on "/connexion"

  Scenario: [Fail] The User submit the form with too long username,.
    Given I am on "/connexion"
    When I fill in "user_connection_username" with "123456789012345678901234567890123456789012345678901234567890"
    And I press "Se connecter"
    Then I should see "Le nom d'utilisateur ne doit pas avoir plus de 50 caractères."
    And I should be on "/connexion"

  Scenario: [Fail] The User submit the form while there is no user in the database.
    Given I am on "/connexion"
    When I fill in the following:
      | user_connection_username | JohnDoe |
      | user_connection_password | 123456789 |
    And I press "Se connecter"
    Then I should see "Le nom d'utilisateur n'existe pas."
    And I should be on "/connexion"

  Scenario: [Fail] The User submit the form with a wrong password.
    Given I am on "/connexion"
    And I load a specific user
    When I fill in the following:
      | user_connection_username | JohnDoe |
      | user_connection_password | 123456789 |
    And I press "Se connecter"
    Then I should see "Le mot de passe est invalide."
    And I should be on "/connexion"

  Scenario: [Success] The User is logged in as successfully
    Given I am on "/connexion"
    And I load a specific user
    When I fill in the following:
      | user_connection_username | JohnDoe  |
      | user_connection_password | 12345678 |
    And I press "Se connecter"
    Then I should be on "/accueil"
