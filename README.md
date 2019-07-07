
![ss](http://tw.greywool.com/i/QfSba.png)

# Invito 
This is a simple backend for invitation receive/send application with Symfony. 


## Requirements

Make sure that, 
  - You have Symfony. 
  - You have Composer.
  - PHP
  - MYSQL Server (You can use XAMPP)
  - Changed your .env file according to factors above

## Install Deps

    $ git clone https://github.com/serhangursoy/invito-backend.git
    $ cd invito-backend
    $ composer install
    $ php bin/console server:start
    $ php bin/console doctrine:database:create
    $ php bin/console doctrine:migrations:diff
    $ php bin/console doctrine:migrations:migrate
    
## Start

    $ php bin/console server:start

## Test

Function call might change on your OS. For windows
    $ php "vendor/phpunit/phpunit/phpunit"


## Where is the client?
You can check it from [invito-frontend](https://github.com/serhangursoy/invito-frontend)
