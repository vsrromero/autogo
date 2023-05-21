# AutoGo API - Rent a car
All endpoints are available at `url/api/endpoints`

## Method Index at Brands

### Index Brands
Retrieves a list of brands with optional filtering, field selection, and related version information.

*  URL: /brands
*  Method: GET
*  URL Parameters: None
*  Query Parameters:
    * `fields_versions` (optional): Comma-separated list of fields to include from the associated versions. If not provided, all fields will be included. Prefix the fields with `versions`: to specify version fields.
    * `filter` (optional): Semi-colon separated list of filters to apply. Each filter consists of a field name, an operator, and a value, separated by colons (`field:operator:value`). Multiple filters can be applied by separating them with semi-colons. Supported operators include` =`, `!=`,` <`, `>`, `<=`, `>=` and `like`.  
    * `fields` (optional): Comma-separated list of fields to include from the brands. If not provided, all fields will be included.

### Request Examples

Retrieve all brands with associated version information:  
HTTP Request endpoint  
`GET /brands`  
  
Retrieve brands with selected fields from the associated versions:  
HTTP Request endpoint  
`GET /brands?fields_versions=name,image`  
  
Retrieve brands filtered by specific conditions:  
HTTP Request endpoint  
`GET /brands?filter=name:=:Ford`  
  
Retrieve brands with selected fields:  
HTTP Request endpoint  
`GET /brands?fields=name,image`  
  
### Response

Status Code: 200 (OK)
Response Format: JSON
The response will contain an array of brand objects, each including the requested fields and associated version information (if specified). The structure of each brand object will be as follows:

```json
{
  "id": 1,
  "name": "Brand 1",
  "image": "brand1.jpg",
  "created_at": "2023-05-20T12:34:56Z",
  "updated_at": "2023-05-20T12:34:56Z",
  "versions": [
    {
      "id": 1,
      "brand_id": 1,
      "name": "Version 1",
      "image": "version1.jpg",
      "number_of_doors": 4,
      "seats": 5,
      "airbags": true,
      "abs": true,
      "created_at": "2023-05-20T12:34:56Z",
      "updated_at": "2023-05-20T12:34:56Z"
    },
    ...
  ]
}
```
If no brands match the specified filters, an empty array will be returned.

### Error Responses

Status Code: 500 (Internal Server Error)
If there is a server-side error while processing the request.

## Method Index at Versions
Retrieves a list of versions with optional filtering and field selection.

*  URL: /versions
*  Method: GET
*  URL Parameters: None
*  Query Parameters:
    * `fields_brand` (optional): Comma-separated list of fields to include from the associated brand information. If not provided, all fields will be included.
    * `filter` (optional): Semi-colon separated list of filters to apply. Each filter consists of a field name, an operator, and a value, separated by colons (`field:operator:value`). Multiple filters can be applied by separating them with semi-colons. Supported operators include` =`, `!=`,` <`, `>`, `<=`, `>=` and `like`.  
    * `fields` (optional): Comma-separated list of fields to include from the versions. If not provided, all fields will be included.

### Request Examples

1. Retrieve all versions with associated brand information:  
HTTP Request endpoint   
`GET /versions`   
  
2. Retrieve versions with selected fields from the associated brand:  
HTTP Request endpoint   
`GET /versions?fields_brand=name,image`  
  
3. Retrieve versions filtered by specific conditions:  
HTTP Request endpoint   
`GET /versions?filter=number_of_doors:>=:4;seats:<:8`  
  
4. Retrieve versions with selected fields:  
HTTP Request endpoint   
`GET /versions?fields=name,image,number_of_doors`   

### Response
Status Code: 200 (OK)
Response Format: JSON
The response will contain an array of version objects, each including the requested fields and associated brand information (if specified). The structure of each version object will be as follows:

```json
{
  "id": 1,
  "brand_id": 1,
  "name": "Version 1",
  "image": "version1.jpg",
  "number_of_doors": 4,
  "seats": 5,
  "airbags": true,
  "abs": true,
  "created_at": "2023-05-20T12:34:56Z",
  "updated_at": "2023-05-20T12:34:56Z",
  "brand": {
    "id": 1,
    "name": "Brand 1",
    "image": "brand1.jpg",
    "created_at": "2023-05-20T12:34:56Z",
    "updated_at": "2023-05-20T12:34:56Z",
  }
}
```
If no versions match the specified filters, an empty array will be returned.

### Error Responses

Status Code: 500 (Internal Server Error)
If there is a server-side error while processing the request.