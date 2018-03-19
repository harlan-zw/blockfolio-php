@guest
Feature: Order Book Endpoints

    @javascript
    Scenario: Can the marketdetails_v2 endpoint
        When I call the "marketdetails_v2" endpoint with exchange "binance" and ticker "ETH-VEN"
        Then The response should contain "high"
        And The response should contain "low"
        And The response should contain "bid"

    @javascript
    Scenario: Can access all positions endpoint
        When I call the "candlestick" endpoint with exchange "binance", ticker "ETH-VEN" and duration "year"
        Then The response should contain "trades"
        And The response should contain "max"
        And The response should contain "min"

    @javascript
    Scenario: Can access order book
        When I call the "orderbook" endpoint with exchange "binance" and ticker "ETH-VEN"
        Then The response should contain "bids"

