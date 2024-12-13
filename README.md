# Symfony Book Repository App

## Features
- creating list of books
- editing each book
- deleting books
- viewing list of books (with filtering and sorting)
- simple JWT authentication system

## Project Requirements
- php 8.2
- composer
- any sql server
- symfony cli

## Tech Stack
- Symfony
- TailwindCSS
- TWIG
- PHPUnit
- CSS
- Javascript

## Project Setup
- clone git repo
- run ```composer install``` command
- configure url for your db server in .env file
- generate keys with ```php bin/console lexik:jwt:generate-keypair```
- run ```symfony console doctrine:database:create``` and ```symfony console doctrine:migrations:migrate```
- optional for testing : run ```symfony console doctrine:database:create --env=test ``` and then migrate
- run ```symfony serve```
- for testing: uncomment all "#security: false" in security.yaml config file