# VAT Calculator

This project is a simple VAT calculator built using Symfony. It supports the following features:

- Calculate VAT for a given value and rate.
- Display calculation history.
- Export history as a CSV file.
- Clear history.
- Protection against XSS and SQL Injection.
- 
## Requirements
- PHP >=8.2
- Symfony CLI (https://symfony.com/download)
- Docker (https://docs.docker.com/get-started/get-docker/)
- Composer (https://getcomposer.org/download/)

## Installation

1. Clone the repository.
2. Run `composer install`.
3. Run `composer create-test-mariadb-container`
4. Run `composer run-test-site`

To stop web server on step 4. please press CTRL + C
To stop test-mariadb container docker use `docker stop test-mariadb`
To remove test-mariadb container docker use `docker remove test-mariadb`

## Usage
Add value with rate
http://127.0.0.1:8000/vat-calculate?rate=12&value=20



## Troubleshooting
1. Check if MariaDB environment have correct IP using this command 
`composer run-test-site-check`
Example info:
```
composer run-test-site-check
MariaDB IP: 172.17.0.2
MariaDB enviroment: DATABASE_URL="mysql://root:root@172.17.0.2:3306/app?serverVersion=11.5.2-MariaDB&charset=utf8mb4"
```
If IP in .env file is not same as MariaDB IP please correct it.


## Alternative installation
Run below commands: 
`docker run -q --detach --name test-mariadb --env MARIADB_ROOT_PASSWORD=root  mariadb:11.5.2`
`php bin/console doctrine:database:create`
`php bin/console --no-interaction doctrine:migrations:migrate`
`symfony server:start`