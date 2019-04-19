@paying_with_system_pay_for_order
Feature: Paying with Mercanet Bnp Paribas during checkout
    In order to buy products
    As a Customer
    I want to be able to pay with Mercanet Bnp Paribas

    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "developpement@studiowaaz.com" identified by "password123"
        And the store has a payment method "Mercanet Bnp Paribas" with a code "system_pay" and Mercanet Bnp Paribas Checkout gateway
        And the store has a product "PHP T-Shirt" priced at "€19.99"
        And the store ships everywhere for free
        And I am logged in as "developpement@studiowaaz.com"

    @ui
    Scenario: Successful payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Mercanet Bnp Paribas" payment method
        When I confirm my order with Mercanet Bnp Paribas payment
        And I sign in to Mercanet Bnp Paribas and pay successfully
        Then I should be notified that my payment has been completed

    @ui
    Scenario: Cancelling the payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Mercanet Bnp Paribas" payment method
        When I confirm my order with Mercanet Bnp Paribas payment
        And I cancel my Mercanet Bnp Paribas payment
        Then I should be notified that my payment has been cancelled
        And I should be able to pay again

    @ui
    Scenario: Retrying the payment with success
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Mercanet Bnp Paribas" payment method
        And I have confirmed my order with Mercanet Bnp Paribas payment
        But I have cancelled Mercanet Bnp Paribas payment
        When I try to pay again Mercanet Bnp Paribas payment
        And I sign in to Mercanet Bnp Paribas and pay successfully
        Then I should be notified that my payment has been completed
        And I should see the thank you page

    @ui
    Scenario: Retrying the payment and failing
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Mercanet Bnp Paribas" payment method
        And I have confirmed my order with Mercanet Bnp Paribas payment
        But I have cancelled Mercanet Bnp Paribas payment
        When I try to pay again Mercanet Bnp Paribas payment
        And I cancel my Mercanet Bnp Paribas payment
        Then I should be notified that my payment has been cancelled
        And I should be able to pay again
