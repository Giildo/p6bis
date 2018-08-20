@comment
@comment_comment_deletion

Feature: As an visitor connected, I need to be able to delete a comment.
  If I'm connected with user role, I need to be able to delete my comments.
  If I'm connected with administrator role, I need to be able to delete all comments.

  Scenario: [Success] The user submits form and add a comment.
    Given I load the tricks with category and user
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on the deletion page
    Then I should not see "Commentaire simulé."
