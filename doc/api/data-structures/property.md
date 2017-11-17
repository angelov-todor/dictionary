## HAL Property (HAL Resource)
+ id: `6f8e839d-8721-499b-ba6e-9a58671d6840` (string) - The Property ID
+ name: `Main Street 10, 3rd floor A` (string) - Property postal address
+ timezone: `Europe/Madrid` (string) - Property timezone
+ address: `Seychelles island` (string) - Property address
+ raw_address (object) - Raw property address as resolved from Google Places API
    + formatted_address: `10 North Main Street, Santa Ana, CA, United States` (string, optional) - Property full address 

## HAL Property with Units (HAL Property)
+ `_embedded` (object)
    + units (array[object])
        + (HAL Unit)

## HAL Property Collection (HAL Resource Collection)
+ `_embedded` (object)
    + properties (array[object])
        + (HAL Property)
