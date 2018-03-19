@guest
Feature: Global Endpoints
    Check the common global endpoints are working

    @javascript
    Scenario: API version can be retrieved
        When I call the API "version" endpoint
        Then The response should contain "version"

    @javascript
    Scenario: API system status can be retrieved
        When I call the API "system_status" endpoint
        Then The response should contain "status"


    @javascript
    Scenario: Currency list can be retrieved
        When I call the API "currency" endpoint
        Then The response should contain "currencyList"

    @javascript
    Scenario: Coin list can be retrieved
        When I call the API "coinlist_v6" endpoint
        Then The response should contain "coins"

