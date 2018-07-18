@security
@security_registration

Feature: As an anonymous user, I need to be able to registration on application.

  Scenario: [Fail] The User is redirected if he is already logged in.
    Given I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/enregistrement"
    Then I should be on "/"

  Scenario: [Fail] The User submit the form without data.
    Given I am on "/enregistrement"
    When I press "Valider"
    Then I should see "Le nom d'utilisateur doit être renseigné"
    And I should see "Le prénom doit être renseigné"
    And I should see "Le nom doit être renseigné"
    And I should see "L'eMail doit être renseigné"
    And I should see "Le mot de passe doit être renseigné"
    And I should be on "/enregistrement"

  Scenario: [Fail] The User submit the form with too short username, first name, last name and password.
    Given I am on "/enregistrement"
    When I fill in the following:
      | user_registration_username        | j |
      | user_registration_firstName       | j |
      | user_registration_lastName        | j |
      | user_registration_password_first  | j |
      | user_registration_password_second | j |
    And I press "Valider"
    Then I should see "Le nom d'utilisateur doit avoir au moins 5 caractères."
    And I should see "Le prénom doit avoir au moins 2 caractères."
    And I should see "Le nom doit avoir au moins 2 caractères."
    And I should see "Le mot de passe doit avoir au moins 8 caractères."
    And I should be on "/enregistrement"

  Scenario: [Fail] The User submit the form with too long username, first name, last name.
    Given I am on "/enregistrement"
    When I fill in the following:
      | user_registration_username  | 123456789012345678901234567890123456789012345678901234567890 |
      | user_registration_firstName | 123456789012345678901234567890123456789012345678901234567890 |
      | user_registration_lastName  | 123456789012345678901234567890123456789012345678901234567890 |
    And I press "Valider"
    Then I should see "Le nom d'utilisateur ne doit pas avoir plus de 50 caractères."
    And I should see "Le prénom ne doit pas avoir plus de 50 caractères."
    And I should see "Le nom ne doit pas avoir plus de 50 caractères."
    And I should be on "/enregistrement"

  Scenario: [Fail] The user submit the form with bad value in "mail" field.
    Given I am on "/enregistrement"
    When I fill in the following:
      | user_registration_mail_first  | johndoe.fr |
      | user_registration_mail_second | johndoe.fr |
    And I press "Valider"
    Then I should see "n'est pas une adresse mail valide."
    And I should be on "/enregistrement"

  Scenario: [Success] The User is registered as successfully
    Given I am on "/enregistrement"
    When I fill in the following:
      | user_registration_username        | JohnDoe     |
      | user_registration_firstName       | John        |
      | user_registration_lastName        | Doe         |
      | user_registration_mail_first      | john@doe.fr |
      | user_registration_mail_second     | john@doe.fr |
      | user_registration_password_first  | 12345678    |
      | user_registration_password_second | 12345678    |
    And I press "Valider"
    Then I should be on "/"