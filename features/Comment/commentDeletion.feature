@comment
@comment_comment_deletion

Feature: As an visitor connected, I need to be able to delete a comment.
  If I'm connected with user role, I need to be able to delete my comments.
  If I'm connected with administrator role, I need to be able to delete all comments.

  Scenario: [Success] The user submits form and add a comment.
    Given I load the tricks with category and user with simulated comment
    And I am logged with username "JohnDoe" and with password "12345678"
    And I am on "/trick/mute/ComPage"
    And I should see "Commentaire simulé."
    When I am on the deletion page
    Then I am on "/trick/mute/comPage"
    And I should not see "Commentaire simulé."
