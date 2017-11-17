## HAL Lease payment (HAL Resource)
+ id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string) - uuid
+ lease_id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string) - The lease ID
+ serie: 3 (number, required) - serie
+ expected_at: `2005-08-15T00:00:00+00:00` (string, required) - Date in ISO 8601
+ amount: 50000 (number, required) - In cents
+ currency: EUR (string, required)
+ available_on: `2005-08-15T00:00:00+00:00` (string, optional) - Date in ISO 8601
+ notifications_sent: 3 (number, required) - notifications sent
+ note: `some note to the lease payment` (string, required)
+ marked_paid_at: `2005-08-15T00:00:00+00:00` (string, optional) - Date in ISO 8601
+ status: `pending` (enum[string]) - Lease payment status
    + `canceled`
    + `marked_as_paid`
    + `refunded`
    + `transferred`
    + `paid`
    + `pending`
    + `waiting`
    + `waiting_warning`
    + `not_paid`

## HAL Lease payment with related Property Unit and Lease (HAL Lease payment)
+ `_embedded` (object)
    + unit (array[object])
        + (HAL Unit)
    + property (array[object])
        + (HAL Property)
    + lease (array[object])
        + (HAL Lease)

## Hal Lease payment revenue (object)
+ date: `2005-08-15T00:00:00+00:00` (string, optional) - Date in ISO 8601
+ paid: 13100 (number, required) - the paid amount
+ not_paid: 23100 (number, required) - the not paid amount
+ waiting: 35600 (number, required) - the amount to be paid
