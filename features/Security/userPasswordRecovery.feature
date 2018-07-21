@security
@security_password_recovery

Feature: As an anonymous user, I need to be able to request to change my password.

  Scenario: [Fail] The User is redirected if he is already logged in.
    Given I load following file "/user/01.specific_user.yml"
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/recuperation"
    Then I should be on "/"

  Scenario: [Fail] The User submit the form without data.
    Given I am on "/recuperation"
    When I press "Valider"
    Then I should see "Le nom d'utilisateur doit être renseigné"
    And I should be on "/recuperation"

  Scenario: [Fail] The User submit the form with too short username.
    Given I am on "/recuperation"
    When I fill in "password_recovery_for_username_username" with "j"
    And I press "Valider"
    Then I should see "Le nom d'utilisateur doit avoir au moins 5 caractères."
    And I should be on "/recuperation"

  Scenario: [Fail] The User submit the form with too long username, first name, last name.
    Given I am on "/recuperation"
    When I fill in "password_recovery_for_username_username" with "123456789012345678901234567890123456789012345678901234567890"
    And I press "Valider"
    Then I should see "Le nom d'utilisateur ne doit pas avoir plus de 50 caractères."
    And I should be on "/recuperation"

  Scenario: [Success] The User submit the form with a good username.
    Given I am on "/recuperation"
    And I load following file "/user/01.specific_user.yml"
    When I fill in "password_recovery_for_username_username" with "JohnDoe"
    And I press "Valider"
    Then I should see "Un mail a été envoyé à votre adresse mail, merci de cliquer sur le lien fourni afin de modifier votre mot de passe."
    And I should be on "/recuperation"

  Scenario: [Fail] The User logs in with bad token
    Given I load following file "/user/01.specific_user.yml" with recovery token
    And I am on "/recuperation" with bad token and with prefix "ut"
    When I fill in the following:
      | password_recovery_for_password_password_first  | 12345678 |
      | password_recovery_for_password_password_second | 12345678 |
    And I press "Valider"
    And I should be on "/recuperation"

  Scenario: [Fail] The User submit the form without data.
    Given I load following file "/user/01.specific_user.yml" with recovery token
    And I am on "/recuperation" with token and with prefix "ut"
    When I press "Valider"
    Then I should see "Le mot de passe doit être renseigné"
    And I should be on "/recuperation"

  Scenario: [Fail] The User submit the form with too short username.
    Given I load following file "/user/01.specific_user.yml" with recovery token
    And I am on "/recuperation" with token and with prefix "ut"
    When I fill in the following:
      | password_recovery_for_password_password_first  | j |
      | password_recovery_for_password_password_second | j |
    And I press "Valider"
    Then I should see "Le mot de passe doit avoir au moins 8 caractères."
    And I should be on "/recuperation"

  Scenario: [Success] The User logs in with good token
    Given I load following file "/user/01.specific_user.yml" with recovery token
    And I am on "/recuperation" with token and with prefix "ut"
    When I fill in the following:
      | password_recovery_for_password_password_first  | 12345678 |
      | password_recovery_for_password_password_second | 12345678 |
    And I press "Valider"
    And I should be on "/"
