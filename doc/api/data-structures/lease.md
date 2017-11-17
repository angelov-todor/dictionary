## Lease Creation (object)
+ unit_id: 6f8e839d-8721-499b-ba6e-9a58671d6840 (string, required) - uuid
+ start: `2005-08-15T00:00:00+00:00` (string, required) - Date in ISO 8601
+ end: `2005-08-15T00:00:00+00:00` (string, required) - Date in ISO 8601
+ due_day: 1 (number, optional) - The day the payments are due
  + Default: 1 
+ tenant_name: John Smith (string, required)
+ tenant_email: john@domain.com (string, required)
+ tenant_number: +14155552671 (string, required) Format E.164
+ tenant_locale: `en-GB` (string, optional) Format https://msdn.microsoft.com/en-us/library/ee825488(v=cs.20).aspx
  + Default: `en-GB`
+ amount: 50000 (number, required) - In cents
+ currency: EUR (string, required)
+ payment_frequency: weekly (enum[string], required)
    + `weekly`
    + `forthnightly`
    + `monthly`

## HAL Lease (HAL Resource)
+ id: 6f8e839d-8721-499b-ba6e-9a58671d6840 (string) - uuid
+ Include Lease Creation

## HAL Lease with Unit (HAL Resource)
+ id: 6f8e839d-8721-499b-ba6e-9a58671d6840 (string) - uuid
+ Include Lease Creation
+ days_before: 10 (number, optional) - Extension notification in days
+ `_embedded` (object)
    + unit (array[object])
        + (HAL Unit)

## HAL Leases Collection (HAL Resource Collection)
+ `_embedded` (object)
    + leases (array[object])
        + (HAL Lease)
