# PHP SERVER
## Procfile
Heroku uses this file to run the deployed application, this file is 
from heroku docs
Tells to herou that my code exists in directory web and to run it using apache

## composer.json
This is what heroku uses to install the apache tool for heroku
composer json is used for tool management by any language

## test.bat
curl is used to test the application, its used instead of the browser
so I can use other methods than GET

## schema.sql
Schema for database


## .htaccess
Rules for apache 
Turn on write engine for apache to write rules
Turn on write base(hbtedy mnen) / which means run after /
IF requested resource is not a file or directory, anything in url parameter will redirect to main.php
[qsa] dont show the user the redirection of parameter
if variable http authroization exists, take the value and insert it into header http authorization


## web/main.php

## web/utils.php


---

## POST /register/

request body
```json
{
    "name": "",
    "email": "",
    "password": ""
}
```

response body 2XX
```http
Authorization: token
```
```json
{
    "token": ""
}
```

respnose body 4XX/5XX
```json
{
    "details": ""
}
```
## POST /login/

request body
```json
{
    "email": "",
    "password": ""
}
```

response body 2XX
```http
Authorization: token
```
```json
{
    "token": ""
}
```

respnose body 4XX/5XX
```json
{
    "details": ""
}
```
## GET /user

response body 2XX
```json
{
    "id": "",
    "email": "",
    "name": "",
    "registration_date": "Y-m-d"
}
```

respnose body 4XX/5XX
```json
{
    "details": ""
}
```
## PATCH /user/

request body
```json
{
    "email": "",
    "name": "",
    "old_password": "",
    "password": ""
}
```

response body 2XX
```json
{
    "id": "",
    "email": "",
    "name": "",
    "registration_date": "DD-MM-YYYY"
}
```

respnose body 4XX/5XX
```json
{
    "details": ""
}
```
## DELETE /user

response body 2XX
```json
{
    "details": ""
}
```

respnose body 4XX/5XX
```json
{
    "details": ""
}
```
## GET /email_checker?email=<email>

response body 2XX
```json
{
    "details": ""
}
```

respnose body 4XX/5XX
```json
{
    "details": ""
}
```

## GET /departments

response body 2XX
```json
[
    {
        "id": int
        "name": string
    }
]
```

respnose body 4XX/5XX
```json
{
    "details": ""
}
```

## GET /admin
