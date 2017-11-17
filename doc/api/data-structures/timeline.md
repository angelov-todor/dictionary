## Timeline DryRun Request
+ start: `2005-08-15T00:00:00+00:00` (string, required) - Date in ISO 8601
+ end: `2005-08-15T00:00:00+00:00` (string, required) - Date in ISO 8601
+ timezone: `Europe\Madrid` (string, required) - Valid timezone of the property
+ amount: 50000 (number, required) - In cents
+ currency: EUR (string, required)
+ payment_frequency: weekly (enum[string], required)
    + `weekly`
    + `forthnightly`
    + `monthly`
+ property_name: `Main Street` (string, required)
+ unit_name: `Beb one` (string, required)
+ tenant_name: John Smith (string, optional)
+ tenant_email: john@domain.com (string, optional)

## Timeline Send Notifications Request
+ lease_id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string, optional) - The lease ID
+ date: `2017-03-15` (string, required) - Date in `Y-m-d` format

## Timeline Base DryRun
+ expected_at (string, optional, nullable) - Moment when something is planned to happen (ISO 8601)
+ executed_at: `2005-08-15T00:00:00+00:00` (string, optional) - Real moment when something happens (ISO 8601)
+ type: email (enum[string])
    + `welcome_email`
    + `change_price`
    + `charge`
    + `payment_notification`
    + `reminder`
+ actor: bot (string) - Who perform the action

## Timeline Base (Timeline Base DryRun)
+ id: 1 (number) - The timeline ID

## Timeline welcomeEmail item DryRun 
+ property: `Main Street 50` (string) - Label of property
+ unit: `Bed 1` (string) - Label of unit
+ email: `tenant@domain.com` (string) - The recipient
+ name: `John Smith` (string) - The name of the recipient
+ has_failed: `false` (boolean) - If is sent/received successfully

## Timeline welcomeEmail item (Timeline welcomeEmail item DryRun)
+ lease_id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string) - uuid

## Timeline changePrice item DryRun
+ name: `John Smith` (string) - The name of payer
+ email: `tenant@domain.com` (string) - The recipient
+ property: `Main Street 50` (string) - Label of property
+ unit: `Bed 1` (string) - Label of unit
+ payment_notification_at: `2005-08-15T00:00:00+00:00` (string, optional) - Moment when payment will be notify (ISO 8601)
+ serie: 2 (number)
+ amount_before: 500 (number) - Amount before the change in cents
+ amount_after: 600 (number) - Amount after the change in cents
+ currency: EUR (string, required)

## Timeline changePrice item (Timeline changePrice item DryRun)
+ lease_id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string) - uuid

## Timeline charge item DryRun
+ name: `John Smith` (string) - The name of payer
+ email: `john@domain.com` - The email
+ property: `Main Street 50` (string) - Label of property
+ unit: `Bed 1` (string) - Label of unit
+ amount: 600 (number) - Amount successfully charged in cents
+ currency: EUR (string, required)
+ serie: 2 (number)
+ total_series: 5 (number)

## Timeline charge item (Timeline charge item DryRun)
+ lease_id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string) - uuid

## Timeline paymentNotification item DryRun
+ name: `John Smith` (string) - The name of payer
+ email: `john@domain.com` - The email
+ property: `Main Street 50` (string) - Label of property
+ unit: `Bed 1` (string) - Label of unit
+ amount: 600 (number) - Amount to be charged in cents
+ currency: EUR (string, required)
+ serie: 2 (number)
+ total_series: 5 (number)
+ note: `some note here` (string) - Additional note to sent to the tenant

## Timeline paymentNotification item (Timeline paymentNotification item DryRun)
+ lease_id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string) - uuid

## Timeline reminder item
+ lease_id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string) - uuid
+ type: reminder
+ name: `John Smith` (string) - The name of payer
+ email: `john@domain.com` - The email
+ property: `Main Street 50` (string) - Label of property
+ unit: `Bed 1` (string) - Label of unit
+ amount: 700 (number) - Amount to be charged in cents
+ currency: EUR (string, required)
+ serie: 2 (number)
+ total_series: 5 (number)
+ note: `additional note here` (string) - Some note to sent to the tenant

## Timeline welcomeEmail DryRun (Timeline Base DryRun)
+ type: welcome_email
+ body (Timeline welcomeEmail item DryRun)

## Timeline welcomeEmail (Timeline welcomeEmail DryRun)
+ id: 1
+ body (Timeline welcomeEmail item DryRun)

## Timeline changePrice (Timeline Base DryRun)
+ id: 2
+ type: change_price
+ body (Timeline changePrice item)

## Timeline charge (Timeline Base DryRun)
+ id: 3
+ type: charge
+ body (Timeline charge item)

## Timeline paymentNotification DryRun (Timeline Base DryRun)
+ type: `payment_notification`
+ body (Timeline paymentNotification item DryRun)
+ expected_at: `2005-08-15T00:00:00+00:00`

## Timeline paymentNotification (Timeline Base)
+ id: 4
+ body (Timeline paymentNotification item)
+ type: payment_notification

## Timeline reminder (Timeline Base)
+ id: 4
+ body (Timeline reminder item)
+ type: reminder

## HAL Timeline DryRun (HAL Resource Collection)
+ _embedded (object)
    + timeline (array[object])
        + (Timeline welcomeEmail DryRun)
        + (Timeline paymentNotification DryRun)
+ total: 4 (number)

## HAL Timeline (HAL Resource Collection)
+ _embedded (object)
    + timeline (array[object])
        + (Timeline welcomeEmail)
        + (Timeline changePrice)
        + (Timeline charge)
        + (Timeline paymentNotification)
        + (Timeline reminder)
+ total: 4 (number)
