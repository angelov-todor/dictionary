### APIProblem401
+ detail: The request requires user authentication (string)
+ status: 401 (number)
+ title: Unauthorized (string)
+ type: http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html (string)

### APIProblem404
+ detail: Resource not found (string)
+ status: 404 (number)
+ title: Not found (string)
+ type: http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html (string)

### APIProblem409
+ detail: The request could not be completed due to a conflict with the current state of the resource (string)
+ status: 409 (number)
+ title: Conflict (string)
+ type: http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html (string)

### APIProblem422
+ detail: The request was well formed but was unable to be followed due to semantic errors (string)
+ status: 422 (number)
+ title: Unprocessable Entity (string)
+ type: http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html (string)
+ validationMessages - Contains a property for each field that failed validation.
    + field
        + stringLength: The input is less than 6 characters long (string)
