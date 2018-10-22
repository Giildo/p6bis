@comment
@comment_comment_modification

Feature: As an visitor connected, I need to be able to update a comment.
  If I'm connected with user role, I need to be able to modify my comments.
  If I'm connected with administrator role, I need to be able to modify all comments.

  Scenario: [Fail] The user submits a too short comment.
    Given I load the tricks with category and user with simulated comment
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/trick/mute/ComPage" with get datas information
    When I fill in "comment_modification_comment" with "j"
    And I press "Ajouter un commentaire"
    Then I should be on "/trick/mute/ComPage"
    And I should see "Les commentaires doivent avoir au moins 5 caractères."

  Scenario: [Fail] The user submits the form without data.
    Given I load the tricks with category and user with simulated comment
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/trick/mute/ComPage" with get datas information
    When I fill in "comment_modification_comment" with ""
    And I press "Ajouter un commentaire"
    Then I should be on "/trick/mute/ComPage"
    And I should see "Un commentaire doit être renseigné."

  Scenario: [Success] The user submits form and modify a comment.
    Given I load the tricks with category and user with simulated comment
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/trick/mute/ComPage" with get datas information
    When I fill in "comment_modification_comment" with "Commentaire simulé modifié"
    And I press "Ajouter un commentaire"
    Then I should see "Commentaire simulé modifié"
