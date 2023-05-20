Possible endpoints for the API:

http://localhost:3000/api/

1. List all car versions:  
    Method: GET  
    URL: [/versions](http://localhost:3000/api/versions)

2. List all car versions with filtered fields:  
    Method: GET  
    Available fields: id, name, image, number_of_doors, seats, airbags, abs, brand_id  
    URL: [/versions?fields=id,name,seats](http://localhost:3000/api/versions?fields=id,name,seats)

3. List all car versions with brand relationship:  
    Method: GET  
    URL: [/versions?brands  ](http://localhost:3000/api/versions?brands)

4. List all car versions with filtered fields and brand relationship:  
    Method: GET  
    URL: [/versions?fields=name,brand_id&brands](http://localhost:3000/api/versions?fields=name,brand_id&brands)

5. List all car versions with filtered fields and brand relationship and filtered fields for brand:  
    Method: GET  
    URL: [/versions?fields=name,brand_id&brand_fields=name](http://localhost:3000/api/versions?fields=name,brand_id&brand_fields=name)
