Movie Nightmare
===============

Welcome to Movie Nightmare, a small moviethek platform.

[![Build Status](https://travis-ci.org/createproblem/mn-webapp.png?branch=master)](https://travis-ci.org/createproblem/mn-webapp)

1) Installing the platform
--------------------------
If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s https://getcomposer.org/installer | php

For other ways to get the composer running visit [Commposer][2]

Now, install or update the libraries with the following command:

    php composer.phar install

Run this command on the top of the mn-webapp directory.

Build all assets by running

    php app/console assets:install
    php app/console assetic:dump


2) Requirements
---------------
The following package list is necessary to run g5webapp.

*   [PHP][3]

    Version 5.3.3 or higher is needed.

*   [MySQL][4]


3) Configuration
----------------
Add `/_configurator` in your browsers URL. Now create the database structure with the following command:

    php app/console doctrine:mongodb:schema:create --index

Create a new user to enter the secured area.

Enjoy!

[2]:  https://getcomposer.org/
[3]:  http://www.php.net/
[4]:  http://www.mongodb.org/
[5]:  http://www.php.net/manual/en/book.mongo.php
