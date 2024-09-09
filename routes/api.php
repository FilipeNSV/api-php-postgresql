<?php

/**
 * This file defines all API routes and their associated controllers and methods.
 * Example: "example-route" => "ExampleController@method"
 * 
 * Protected routes (require token) are listed in the $protectedRoutes array.
 */

// Define the routes for different HTTP methods
$routes = [
    "GET" => [
        "user-list" => "UserController@listUsers",
        "user-get" => "UserController@getUser",
        "user-delete" => "UserController@deleteUser",

        "products-list" => "ProductController@listProducts",
        "product-get" => "ProductController@getProduct",
        "product-delete" => "ProductController@deleteProduct",

        "product-types-list" => "ProductTypeController@listProductTypes",
        "product-type-delete" => "ProductTypeController@deleteProductType",

        "transaction-list" => "TransactionController@listTransaction",
    ],
    "POST" => [
        "login" => "AuthController@login",
        "user-create" => "UserController@createUser",
        "user-update" => "UserController@updateUser",

        "product-update" => "ProductController@updateProduct",
        "product-create" => "ProductController@createProduct",

        "product-type-create" => "ProductTypeController@createProductType",

        "transaction-purchase" => "TransactionController@purchaseTransaction",
        "transaction-sale" => "TransactionController@salesTransaction",
    ]
];

/**
 * List of protected routes that require authentication.
 * 
 * @var string[]
 */
$protectedRoutes = [
    "user-list",
    "user-get",
    "user-update",
    "user-delete",
    "products-list",
    "product-get",
    "product-delete",
    "product-types-list",
    "product-type-delete",
    "transaction-list",
    "product-create",
    "product-update",
    "product-type-create",
    "transaction-purchase",
    "transaction-sale"
];
