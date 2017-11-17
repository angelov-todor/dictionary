## Stripe Account Response
+ country: `ES` (required, string) - The country abbreviation
+ legal_entity_type: `individual` (optional, string) - individual or company
+ first_name: `John` (optional, string) - First name
+ last_name: `Doe` (optional, string) - Last name
+ dob: `2000-12-12` (optional, string) - Date of birth \DateTime::ATOM
+ city: `Madrid`  (optional, string) - City address
+ street: `Gran Via` (optional, string) - Street address
+ postal_code: `18001` (optional, string) - Postal code
+ personal_id_provided: `true` (required, boolean) - whether we have provided personal ID
+ tos_accepted: `1497970457`  (optional, number) - TOS accepted timestamp
+ tos_ip: `127.0.0.1` (optional, string) - TOS accepted from IP
+ bank_account_bank_name: `STRIPE TEST BANK` (optional, string) - The bank account name
+ bank_account_country: `DE` (optional, string) - The bank account country
+ bank_account_currency: `eur` (optional, string) - The bank account currency
+ business_name: `Business name` (optional, string) - The company name
+ business_tax_id_provided: `true` (required, boolean) - Whether company TAX ID is provided
+ payouts_enabled: `true` (required, boolean) - Whether payouts are enabled
+ charges_enabled: `true` (required, boolean) - Whether charges are enabled

## Stripe Account Request
+ legal_entity_type: `individual` (string, required) - individual or company
+ first_name: `John` (string, required) - First name
+ last_name: `Smith` (string, required) - Last name
+ dob: `2000-12-12` (string, required) - Date of birth
+ city: `Madrid` (string, required) - City address
+ street: `Gran Via` (string, required) - Street address
+ postal_code: `18001` (string, required) - Postal code
+ personal_id_number: `123123123Y` (string, required) - Personal ID number
+ external_account: `btok_1B2LrFLztS2EJQLLZTEOMKtc` (string, required) - Bank account token id
+ business_name: `Business name here` (string, required) - The company name
+ business_tax_id: `123123123A` (string, required) - The company tax id

## Stripe Account Create Request (Stripe Account Request)
+ tos_accepted: `true` (boolean, required) - TOS accepted flag
+ country: `ES` (string, required) - The country abbreviation
