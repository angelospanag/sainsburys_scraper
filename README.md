# Sainsbury's Scraper

A console application written in PHP that scrapes the Sainsbury's grocery site - Ripe Fruits page and returns a JSON array of all the products on the page.

## Requirements
The following software must be installed on your environment to run this project.

### PHP
Your environment must be configured with PHP versions 5.6.x or 7.0.x

http://php.net/

### Composer

Composer is a dependency manager for PHP. It is used to retrieve and install all the necessary libraries and their dependencies used in this project.

https://getcomposer.org/

## Installation

From the root of the project issue the following command to install all project dependencies using `composer`:

`composer install`

## Running

From the root of the project issue the following command to run the project:

`php src/main.php`

## Unit testing
Unit testing can performed by issuing the following command from the root of the project:

#### Linux & OS X (Bash)

`./vendor/phpunit/phpunit/phpunit`

#### Windows (Command Prompt)

`php vendor\phpunit\phpunit\phpunit`

## Generating documentation

Currently generated documentation is stored in the `doc` folder from the root of the project.

You can (re)generate documentation with the following command from the root of the project:

#### Linux & OS X (Bash)

` ./vendor/bin/phpdoc -d src/ -t doc`

#### Windows (Command Prompt)

` vendor\bin\phpdoc.bat -d src/ -t doc`

## Software Used

This section describes the software dependencies used in the development of this project.

### Runtime dependencies

#### Scraping functionality
Goutte 3.1:  https://github.com/FriendsOfPHP/Goutte

### Developer dependencies

#### Unit testing

PHPUnit 5.3: https://phpunit.de/

#### Documentation
phpDocumentor 2.8:  https://www.phpdoc.org/
