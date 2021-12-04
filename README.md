# Arukereso project by Balla Péter

## Setup

1. Running docker (this docker setting based on https://github.com/harshalone/docker-compose-lamp)

Note: Before run the above command make sure other container doesn't use the localhost and localhost:8080 

```
> docker-compose up -d
```

2. Install packages

```
> docker exec -it lamp-php74 bash

> composer install
```

3. Set .env

Rename the .env.example and fill the database part with the above

```
DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=docker
DB_USERNAME=docker
DB_PASSWORD=docker
```

```
> docker exec -it lamp-php74 bash

> php artisan key:generate
```

4. Create database schema and migrate csv

```
> docker exec -it lamp-php74 bash

> php artisan migrate
```

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
