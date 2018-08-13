@trick
@trick_show_trick

Feature: As an visitor, anonymous or connected, I need to be able to see the details of a trick.

  Scenario: [Fail] The user is redirected if the slug in URI is wrong
    Given I load the tricks with category and user
    And I am on "/trick/badSlug"
    Then I should be on "/accueil"

