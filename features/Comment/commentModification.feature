@comment
@comment_comment_modification

Feature: As an visitor connected, I need to be able to update a comment.
  If I'm connected with user role, I need to be able to modify my comments.
  If I'm connected with administrator role, I need to be able to modify all comments.

  Scenario: [Success] The user submits form and add a comment.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/trick/mute" with get datas information
    Then I should see "Commentaire simulé."
