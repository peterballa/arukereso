# Arukereso project by Balla Péter

## Summary

This project is an API, where by calling endpoints we can manage order process like:

- Create order
- Query orders by filters
- Change order status

The application store orders in the orders table and store theirs products in the order_products table.
Any other tables like migrations, users and personal_access_tokens needs for migrating and authenticating. 

Please before start, read the **Setup** section carefully.

## Setup

Put the project into LAMP environment e.g.: Docker or WAMP or XAMPP.

1. Install packages

```
> composer install
```

2. Set .env

Copy or rename the .env.example to .env and fill the database part with your database environment like e.g.:

```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Fill the APP_URL as well e.g.:

```
APP_URL=http://localhost
```

3. Generate key
```
> php artisan key:generate
```

4. Create database schema

```
> php artisan migrate
```

5. Create user and get authentication token

For all API requests will be need a user and its token. Below command will create a user and will print a token which will be need.
So **COPY IT!!!**

```
> php artisan db:seed
```

Token example: 1|ZKkU4jftElLanansy4dIIEbfv24p4Bj1OIJpWpyW

## Testing

Run the below commands to run the tests. Important! Don't forget set the APP_URL first!

```
php artisan test
```

## Postman

There is a arukereso.postman_collection.json in the root. Please import in the postman and on the **Authorization** tab set the token, what you got on database seeding.

![postman_auth](docs/images/postman_auth.png)

On **Variables** tab please set the base_url

![postman_variables](docs/images/postman_variables.png)

After this you can run any request with **Send** button

![postman_send_request](docs/images/postman_send_request.png)

The **?XDEBUG_SESSION_START=PHPSTORM** part in the URL means you can debug in PhpStorm if Xdebug is enable.

On the right side you can convert the request in different code snippet like cURL

![postman_curl](docs/images/postman_curl.png)


##Swagger

To generate swagger API documentation, please run the command below. Then you can reach the doc on the **/api/doc** route

```
> php artisan l5-swagger-generate
```

For authorization please click on the green lock button with "Authorize title" and give the token in the below format.

![swagger_authorization](docs/images/swagger_authorization.png)

## Running Code Quality Tools

### Psalm

```
> composer psalm
```

### Mess Detector

```
> composer phpmd
```

### Codesniffer

```
> composer cs
```
