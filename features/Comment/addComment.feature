@comment
@comment_add_comment

Feature: As an visitor connected, I need to be able to add a comment when I'm on the show trick page.

  Scenario: [Fail] The user submits a too short comment.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/trick/mute/ComPage"
    When I fill in "add_comment_comment" with "j"
    And I press "Ajouter un commentaire"
    Then I should be on "/trick/mute/ComPage"
    And I should see "Les commentaires doivent avoir au moins 5 caractères."

  Scenario: [Fail] The user submits the form without data.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/trick/mute/ComPage"
    When I press "Ajouter un commentaire"
    Then I should be on "/trick/mute/ComPage"
    And I should see "Un commentaire doit être renseigné."

  Scenario: [Success] The user submits form and add a comment.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/trick/mute/ComPage"
    When I fill in "add_comment_comment" with "Un nouveau commentaire ajouté."
    And I press "Ajouter un commentaire"
    Then I should be on "/trick/mute/ComPage"
    And I should see "Un nouveau commentaire ajouté."
