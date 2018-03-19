@guest
Feature: Positions Endpoints

    @javascript
    Scenario: Can access all positions endpoint
        When I call the API "get_all_positions" endpoint
        Then The response should contain "positionList"

    @javascript
    Scenario: Can access combined positions
        When I call the "get_combined_position" endpoint with ticker "ETH-VEN"
        Then The response should contain "holdings"

    @javascript
    Scenario: Can access individual position v2
        When I call the "get_positions_v2" endpoint with ticker "ETH-VEN"
        Then The response should contain "positionList"

