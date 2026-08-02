# Filipino Cookbook API

##  API Title

Filipino Cookbook API

---

## API Description

The Filipino Cookbook API is a RESTful web service that provides structured information about traditional Filipino dishes, including their categories, origins, and ingredients. It is built to support developers and students who want to explore or consume Filipino culinary data programmatically.

Purpose: To provide organized, queryable access to a database of Filipino foods, their preparation instructions, categories, origins, and ingredients.
Type of information provided: Food names, categories, regional origins, cooking instructions, and ingredient lists.
Intended users: Students, developers, and client applications that need Filipino food data for learning or integration purposes.
Main functions: Retrieving all foods, retrieving a specific food by ID, searching foods by name, retrieving categories, retrieving ingredients, and adding new foods.
Technologies used: PHP, Slim Framework, MySQL, Composer, JSON.

---

## Features
Retrieve all Filipino foods with their category, origin, and ingredients
Retrieve a single food item by its ID
Search for foods by name (partial match)
Retrieve all food categories
Retrieve all ingredients
Add a new food item, including linked ingredients
Authenticate all /api requests using a Bearer token
Return all responses in JSON format
Handle database errors gracefully with appropriate HTTP status codes

---

## Technologies Used
PHP
Slim Framework 4
MySQL
Composer
JSON
Apache (XAMPP)
Thunder Client / Postman
Git
GitHub

---

## Installation Instructions
git clone https://github.com/USERNAME/filipino-cookbook-api-elizarde.git
cd filipino-cookbook-api-elizarde
composer install
Copy config.example.php to a real configuration file (or update the database credentials directly in getDBConnection() if your version does not use a config file).
Create a MySQL database named filipino_cookbook_api.
Import the provided .sql file into that database using phpMyAdmin or the MySQL CLI.
Update the $host, $user, and $pass variables in getDBConnection() to match your local MySQL setup.
Start Apache and MySQL (e.g., via XAMPP).
Place the project folder inside your server's htdocs directory.
Run the API by navigating to the base URL in your browser or API client.
Test the endpoints using Thunder Client or Postman.

---

## Database Setup
Database name: filipino_cookbook_api
SQL file: filipino_cookbook_api.sql 

Tables:

foods
categories
origins
ingredients
food_ingredients (junction table linking foods and ingredients)

Table relationships:

categories -> foods <- origins
foods -> food_ingredients <- ingredients

Each food belongs to one category and one origin. Each food can have multiple ingredients through the food_ingredients junction table, and each ingredient can belong to multiple foods.

## Base URL
http://localhost/filipino-cookbook-api/public/api

## Authentication Instructions

All endpoints under /api require a Bearer token in the request header.

Required header:

Authorization: Bearer dmmmsu-cookbook-token-2026

If the Authorization header is missing or does not match the expected token, the API returns a 401 Unauthorized response:

json
{
    "status": "error",
    "message": "Unauthorized access. Valid API token is required."
}

The root endpoint (/) does not require authentication and can be used to confirm the API is running.

## Endpoint Documentation

Endpoint: GET /
Description: Returns a welcome message confirming the API is running. No authentication required.

Example request:

GET http://localhost/filipino-cookbook-api/public/

Example response:

json
{
    "message": "Welcome to the Secured Filipino Cookbook API",
    "note": "Use a valid Bearer token to access /api endpoints."
}

---

Endpoint: GET /api/foods
Description: Returns all Filipino foods, including category, origin, and ingredients for each.

Required headers:

Authorization: Bearer dmmmsu-cookbook-token-2026
Accept: application/json

Example request:

GET http://localhost/filipino-cookbook-api/public/api/foods

Example successful response:

json
[
    {
        "food_id": 1,
        "food_name": "Adobo",
        "category_name": "Main Dish",
        "origin_name": "Philippines",
        "instructions": "Simmer pork or chicken in soy sauce, vinegar, garlic, and bay leaves.",
        "ingredients": ["Bay Leaves", "Garlic", "Soy Sauce", "Vinegar"]
    }
]

Example error response:

json
{
    "status": "error",
    "message": "Unauthorized access. Valid API token is required."
}

---

Endpoint: GET /api/foods/{id}
Description: Returns the details of a specific food item by its ID.

Required headers:

Authorization: Bearer dmmmsu-cookbook-token-2026

Example request:

GET http://localhost/filipino-cookbook-api/public/api/foods/1

Example successful response:

json
{
    "food_id": 1,
    "food_name": "Adobo",
    "category_name": "Main Dish",
    "origin_name": "Philippines",
    "instructions": "Simmer pork or chicken in soy sauce, vinegar, garlic, and bay leaves.",
    "ingredients": ["Bay Leaves", "Garlic", "Soy Sauce", "Vinegar"]
}

Example error response (food not found):

json
{
    "status": "error",
    "message": "Food not found"
}

Endpoint: GET /api/foods/search/{name}
Description: Searches for foods whose name partially matches the given search term.

Required headers:

Authorization: Bearer dmmmsu-cookbook-token-2026

Example request:

GET http://localhost/filipino-cookbook-api/public/api/foods/search/adobo

Example successful response:

json
[
    {
        "food_id": 1,
        "food_name": "Adobo",
        "category_name": "Main Dish",
        "origin_name": "Philippines",
        "instructions": "Simmer pork or chicken in soy sauce, vinegar, garlic, and bay leaves.",
        "ingredients": ["Bay Leaves", "Garlic", "Soy Sauce", "Vinegar"]
    }
]
---
Endpoint: GET /api/categories
Description: Returns all available food categories.

Required headers:

Authorization: Bearer dmmmsu-cookbook-token-2026

Example request:

GET http://localhost/filipino-cookbook-api/public/api/categories

Example successful response:

json
[
    { "category_id": 1, "category_name": "Main Dish" },
    { "category_id": 2, "category_name": "Dessert" }
]

Endpoint: GET /api/ingredients
Description: Returns all ingredients stored in the database.

Required headers:

Authorization: Bearer dmmmsu-cookbook-token-2026

Example request:

GET http://localhost/filipino-cookbook-api/public/api/ingredients

Example successful response:

json
[
    { "ingredient_id": 1, "ingredient_name": "Soy Sauce" },
    { "ingredient_id": 2, "ingredient_name": "Vinegar" }
]

Endpoint: POST /api/foods
Description: Adds a new food item, along with its linked ingredients.

Required headers:

Authorization: Bearer dmmmsu-cookbook-token-2026
Content-Type: application/json

Example request body:

json
{
    "food_name": "Sinigang",
    "category_id": 1,
    "origin_id": 1,
    "instructions": "Boil pork with vegetables in a sour tamarind broth.",
    "ingredient_ids": [3, 5, 7]
}

Example successful response:

json
{
    "status": "success",
    "message": "Food added successfully."
}

Example error response (missing fields):

json
{
    "status": "error",
    "message": "Invalid request body."
}

10. HTTP Status Codes
Status Code	Meaning
200	Request completed successfully
201	Resource created successfully
400	Invalid request or missing parameter
401	Missing or invalid authentication token
404	Requested resource was not found
500	Internal server error

11. Testing Evidence

[Insert screenshots here showing: a successful GET /api/foods request, a request with a missing/invalid token returning 401, a request for a non-existent food ID returning 404, and a successful POST /api/foods request. Add a short caption under each screenshot.]

12. Developer Information
Name: Mon Arkhie F. Elizarde
Course and Section: Bachelor of Science in Information Technology
GitHub Username: MonKei1031
Repository Link: https://github.com/MonKei1031/filipino-cookbook-api-elizarde.git
Date Completed: 02/08/2026
