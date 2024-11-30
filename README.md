
# Rest API template

This is an simple REST API template with an modern pattern made with pure **PHP**, with possibilities to, define more secure routes, handle the request with middlewares, set an ruleset for the request data, create, 

#### This project was creadted from the scratch using [Laravel](https://laravel.com/) as inspiration!

### In this documentation you're going to find the following topics:
- [Getting Started With the Project](#getting-started-with-the-project)
- [Folder Structure](#folder-structure)
- [Basic workflow of the code](#basic-workflow-of-the-code)
- [Creating your own routes](#creating-your-own-routes)
- [Creating requests and validation rulesets](#requests)
- [Setting your Middlewares](#creating-middlewares)
- [Setting your Controllers objects](#creating-controller)
- [Sending results as responses](#sending-responses)
- [Setting your Business objects](#creating-business)
- [Setting your Model objects](#creating-models)
- [Using the query helper from the base model](#using-the-query-helper-from-the-base-model)

## Getting Started With the Project 

Before you start to develop and use the project template, you need to set up some configs in order to make your project work.

**Important:** this project don't have one local server `Laravel`, so you'll need to download one application package for server with `PHP 8.2` and `MySql Server` in order to run the application on your localhost.

#### Clone the repository
```bash
git clone https://github.com/Daniel10013/php-study.git
```

#### Change the folder
```bash 
cd php-study
```

#### Configure your `.env` file
In this project you need to have an active database working, so the next step is to set up the configs for your database, if you don't have the `.env` file in the root of your project you'll need to create one. 

Your file should look like this:

```env
DB_NAME="dabase_name"
DB_HOST="database_host"
DB_USER="dabatase_user"
DB_PASSWORD="database_password"
```
If you followed the last steps correctly, you can go to the base url of your project and you'll see one message like this: 

```text
The API docs can be found on the read.me file!
```

## Folder Structure

For this project i've used one very used folder structure, and as I said this project was made based on **Laravel**, so you'll be able to find somethings closer to it

#### This is how the folder structure looks like: 
    App
    ├── Business
    ├── Config
    ├── Core
    ├── Exceptions
    ├── Http      
    │ └── Controller
    │ └── Middleware
    │ └── Request
    │ └── Routes 
    ├── Lib
    └── Model

### Core
The core folder contains all the base code that the application needs to work well, it cointains the base files for controllers, models, request, response etc.
This folder also contains the **router** of the application. **You don't need to do anything in this folder, except if you want to change how the application works**

### Config
This folder contains the default config file that sets the database connection data in the code and also this folder contains one file with all the `HTTP status codes`, so they can all be accessed globaly in the code  

### Lib
This folder contains all the classes that are meant to be reused like libraries, so, this is where to create them, some usefull classes are already done in this folder, the docs on them are going to be explained in the [Others](#others) section.

### Http
This folder contains all the classes related to handling, processing and validating HTTP requests
 #### Routes
 Here is where all the route files of the application are going to be
 #### Request
 In this folder you can create your personalized Request files in order to set your rules to validate the reqeust
 #### Controller
 Here is where all your controller files need to be located, the files and classes needs to have the word `Controller` in their names
 #### Middleware
 In order to pre-process the requests, you can create middlewares in this folder, they need to have the function `handle()` otherwise they won't work 
### Business
Contains the application’s business logic, processing and applying business rules. It separates complex rules that don’t fit directly into the controllers
### Model
Contains the functions to manipulate and interact with the database. The base model already have simple functions that were made as helpers to interact with the database
### Exceptions
You can creat your own exceptions to use and handle with the errors the way you like more, also in this project you need to use the class `BaseException` as the default exception object

## How the application works 
The basic workflow of the application is very easy to understand, it's like one linear line that can be represented like this:

```text
index -> router -> execute route -> validate request -> middleware -> controller
```

That's basically the flow in the application runlife works, the `index.php` file is the entrypoint, in this file the connection with the database is validated, so **you need to have your connection working**, or else the application won't work

After the entrypoint, the router validate if the route exists and in case of success, the route is executed, after executing the route, the base `Request` objct is intialized and if the code find one personalized `Request` class the request will be validated.

If the validation succeds the appliaction will check the middlewares, if there's no problem, then the controller objct will be created and the `Request` object will be used as parameter on the start of the Controller, after this the code will finish to execute and if you want to send any response to the client you need to use the `Response` class at `App\Core\Response`

That's basically how the code works, of course there's other things like using the Business and the Model, but this will be explained later.

## Creating routes

The routing system in this application template works based on rest patters, in other words you can create requests on all the common http methods, the available mthods are: 

`GET`, `POST`, `PATCH`, `PUT`, `DELETE`, `OPTIONS` and `HEAD`

The base of the route is defined by the file name, so for example, if you want to create the following route: `http://localhost/php-study/users` you need to create the `users.php` file under the `App\Http\Routes` folder.

The route file **needs** to use the namespace  `App\Routes`

After creating the route file you'll set the routes by using variable `$route` and calling the http method you want as one function, like this: `$route->post`.

#### Defining the routes 

This is how one route function looks like, the route can have 3 parameters, and the last one is not required

* **Required: `string` $route**  

    The route being defined, if you want to use parameters on the url you have to use them like this: `route/:id`, then in the request object you'll have `$url->id`to be used

* **Required: `array` $methodToExecute**  
    One array with the first item being the Controller and the second item the method that will be executed

* **Optional `array` $middlewares**
    
    One array with the first item being the Controller and the second item the method that will be executed


This is how the function looks like in use:
```php 
$route->post('test/:id', [UsersController::class, "listUsers"], ["Auth"]);
```
And if you want to use and diferent http method, you can just call `$route->get()` and pass the parameters.

## Requests

As explained in the explanation about the program flow section, when one route is accessed, one `Request` object instance is created, the request have 3 properties that can be usefull while developing
* **StdClass $body**  
    Contains all the data sent in the request body as an StdClass, so you use can access the data like `$body->name`

* **StdClass $header**  
    Contains all the information available in the request header as an StdClass too

* **StdClass $url**  
    Contains all the sent as an url parameter as an StdClass, so they can be accessed the same way as the `$body` data

###  Validating requests

You can create an ruleset to validate your requests in an more automatic way, these are not definitive validations and more complex rules may be needed to validate something especific.

To validate your requests, you need to create one file in the `App\Http\Requests` folder with the word `Request` in the name or else the code won't find it

Your request file shoud look like this:

```php
<?php
namespace App\Http\Request;
use App\Core\Request\Request;

class UsersRequest extends Request{

    protected function rules(): array{
        return [
            "method" => [
                "fieldName" => []
        ];
    }
}
```

**Make sure** your class follow these rules:
* Extends the base request (and use it)
* Use the correct namespace, as the one above
* Override the `rules()` function

The validation happens in the following way, the `rules()` function returns one `array` where each key of this `array` is the method that will have the request validated (the method is defined in the creation of the route). 

Inside the `method` key you'll have another `array` with all the fields being validated, as in the exemple above

And finally inside the `fieldName` another array with the validations, **any field name inside the `method` key needs to be on the request**, so the basic strcuture without the ruleset is like this:

```php
[
    "method" => [
        "fieldName" => []
    ],  
];
```
Finally, inside the `fieldName` key, you need to set the validations you're going to use, there are 4 type os validations:

* **Type (string)**
    
    The type of the value being validated, the available validations are:

    `string`, `int`, `number`, `email`, `boolean`, `phone`, `date`
    
    The **diference** between `int` and `number` is that `number` can any kind of numerical value
    
* **Required (bool)**

    Checks wheter one field can have the value empty or not, the validation needs to be one bool(true | false)

* **Min or Max (int)**
    
    Sets the minimal or maximum value for one field, **only works for string and numbers**

Here is one example on how to create the `array` inside the `fieldName`

```php
[
    "type" =>  "string",
    "required" => true,
    "min" => 100
    "max" => 250
]
```

## Creating Middlewares

The middleware works pretty simple in this project, every middleware class needs to be in the `App\Http\Middlewares` folder, and use the `namespace App\Http\Middleware`

Your class need to have this structure

```php
<?php

namespace App\Http\Middleware;
use App\Core\Request\Request;
class MiddlewareName{
    public function handle(Request $request){
    }
}
```

To use the middleware you need to use the class name as the third parameter in the route function;

## Creating controllers

Every controller in the project need to be inside the `App\Http\Controller` folder, use the `namespace App\Http\Controller`, have the word 'Controller' in the name and **needs** to extends the default controller
 
Your class need to have this structure

```php
<?php

namespace App\Http\Controller;
use App\Core\Controller\Controller;

class exampleController extends Controller{
    //create the functions 
}
```

The controller have two attribute by default 

* **Request**
    
    You can use to get the values sent in the request, you can use it by calling `$this->request`, if you want to know more about it, check the [request](#requests) section

* **Business**
    
    You can use to get call the business related to this controller (if it exists), the controller try to find the business on the `__construct()` function

## Sending responses

To send responses to the client after finishing the execution of your code, you can use the class `App\Core\Response\Response` and use the function `send`, like this:

```php
Response::send(array $json, int $status_code): void
```
* **array $json**
    
    One array formated in the way you want your response to be

*  **int $status_code**

    The status code you want your response to be, i strongly recomend you to use the status code constants available in the config 


## Creating Business

Every business in the project need to be inside the `App\Business` folder, use the `namespace App\Business`, have the word 'Business' in the name and **needs** to extends the default `business`
 
Your class need to have this structure

```php
<?php
namespace App\Business;
use App\Core\Business\Business;

class UsersBusiness extends Business{
    //create the functions
}
```

The business have one attribute by default 

* **Model**
    
    You use the model related to this business (if it exists), the business try to find the model on the `__construct()` function, and returns `null` in case it dont exists

## Creating Models

Every model in the project need to be inside the `App\Model` folder, use the `namespace App\Model`, have the word 'Model' in the name and **needs** to extends the default `Model`, or else the class won't do nothing

When creating the model you **need** to set the attribute `$this->table` with the name of tha table that the model represents
 
Your class needs to look like this
```php
<?php
namespace App\Model;
use App\Core\Model\Model;

class UsersModel extends Model{

    protected string $table = "users";
}
```

## Using the query helper from the base model

In the default model you'll have a couple of functions that you can use to fastly interact with your database
 
You'll see down bellow how to use them and an brief explanation on what they do
```php
<?php
/**
* Gets all the records from one single table
* @return array One associative array with all the items
*/
public function findAll(): array

/**
* Gets the first record with the matching id the table
* @param int $id The id of the item you're looking for
* @return array One associative array in case the item is found
*/
public function find(int $id): array

/**
* Gets the first record with matching an specific filter
* @param string $field The field of the table used as the filter
* @param mixed $value The value of item matching the filter
* @return array One associative array with all the items matching the filter
*/
public function where(string $field, mixed $value): array

/**
* Gets the first record of the table
* @return array The first item of the table
*/
public function first(): array

/**
* Deletes the item with the matching id
* @param int $id The id of the item you want to delete
* @return bool true or false based on the result
*/
public function delete(int $id): bool

/**
* Insert one new record in the table
* @param array $dataToCreate One associative array with all the data to create the new record
* @return bool true or false based on the result
*/
public function create(array $dataToCreate): bool

/**
* Update one or more fields from the table
* @param array $data One associative array with the data to update
* @param int $id The id of the item you want to update
* @return bool true or false based on the result
*/
public function update(array $data, int $id): bool

/**
* Check if one record exists based on one filter and the value that needs to match the filter
* @param mixed $value The value of the item you want to check if exists 
* @param string $field The field you want to use as filter, 
* @return bool true or false based on the result
*/
public function exists(mixed $value, string $field = 'id'): bool

/**
* Get all the records from one table making one join with other table, this join is made using the id as field reference 
* @param string $table The table you want to join
*/
public function join(string $table): array
```
Those are all the funcions that can be used to execute fast querys
