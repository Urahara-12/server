# PHP SERVER
## Procfile

## composer.json

## test.bat

## schema.sql

## .htaccess

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
