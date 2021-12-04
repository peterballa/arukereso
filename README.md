# Arukereso project by Balla Péter

## Setup

1. Install packages

```
> composer install
```

2. Set .env

Copy or rename the .env.example to .env and fill the database part with the above

```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
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

## Running Code Quality Tools

### Psalm

```
> docker exec -it lamp-php74 bash

> composer psalm
```

### Mess Detector

```
> docker exec -it lamp-php74 bash

> composer phpmd
```

### Codesniffer

```
> docker exec -it lamp-php74 bash

> composer cs
```
