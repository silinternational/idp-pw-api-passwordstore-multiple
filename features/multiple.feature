Feature: Multiple backends

  Scenario: Calling getMeta()
    Given 3 passwordstores are configured
    When I get metadata about a user
    Then I should receive the response from the first passwordstore
  
  Scenario: Successfully setting a password on all passwordstores
    Given 3 passwordstores are configured
    When I set a user's password
    Then an exception should NOT have been thrown
      And I should receive the response from the first passwordstore
  
  Scenario: Only managing to set a password on some passwordstores
    Given 3 passwordstores are configured
      But passwordstore 2 will fail when I try to set a user's password
    When I set a user's password
    Then an exception SHOULD have been thrown
      And the exception should say passwordstore 1 now has the new password
      And the exception should say passwordstore 2 had an error
      And the exception should say passwordstore 3 was not changed
  
  Scenario: Not trying because a passwordstore is down
    Given 3 passwordstores are configured
      But passwordstore 2 is not responding to our status precheck
    When I set a user's password
    Then an exception SHOULD have been thrown
      And the exception should say passwordstore 1 was not changed
      And the exception should say passwordstore 2 was not changed
      And the exception should say passwordstore 3 was not changed
      And the exception should say passwordstore 2 is down
