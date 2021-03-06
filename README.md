nano
====

Nano is a minimalistic application template using [Phundament](https://github.com/phundament/app), [Yii 2.0 Framework](http://www.yiiframework.com/doc-2.0/guide-index.html), 
[PHP](http://php.net) and [Docker](https://www.docker.com).

Based upon the Docker `phundament/app` image it includes a ready-to-use application, which you can customize to fit your
needs.

## Requirements

- [docker](https://docs.docker.com/engine/installation/)
- [docker-compose](https://docs.docker.com/compose/) **>=1.5.2**

## Installation

[Download](https://github.com/phundament/nano/releases) the latest release and start by creating the essential
local configuration files.

    cp .env-dist .env
    cp docker-compose.override-dist.yml docker-compose.override.yml

Now, you are ready to setup the `vendor` folder for local development and code-completion
    
    docker-compose run --rm php composer install

Start stack

    docker-compose up -d

Check the logs, especially when you are starting a fresh stack, since the setup may take a minute
    
    docker-compose logs
    
When you see `Application initialized.` you are ready to open the application in your browser
    
OS X
    
    open http://192.168.99.100:8004
    
Ubuntu
    
    xdg-open http://192.168.99.100:8004

## Usage

To customize the application you have the following options.

### Override existing files

You can override files on the base-image as layed out in Phundament's [`src`](https://github.com/phundament/app/tree/master/src) 
folder. 

By default we're *adding* the src folder to the built image, see [`ADD`](https://docs.docker.com/engine/articles/dockerfile_best-practices/#add-or-copy) 
for details how this works.

Files can also be taken from the `phundament/app` repo or copied from the image. 

### Change configuration

Add your custom configuration opions to `src/config/local.php`.

### Add `composer` package

A large part of an application usually consists of libraries. To use an library, find the package 
you want to install and run

    docker-compose run --rm php composer require "vendor/package" "^1.0.0"

### Development `bash`

Start a bash in the PHP container to run `yii`, `composer` or other commands.     
    
    docker-compose run --rm php bash

### Create a `yii` module

By default there is `cms` folder mounted in `docker-compose.yml`. You can create a skeleton module there by entering
an application bash and run the following command inside the PHP container

    $ yii gii/module --moduleID=frontend --moduleClass=modules\\frontend\\Module

### Copy files from Docker image

You can copy the asset files from the image for customization in the frontend module    
    
    docker cp $(docker-compose ps -q php):/app/src/assets modules/frontend/

    
For more in information see

 - [Phundament README](https://github.com/phundament/app/blob/master/README.md)
 - [Phundament documentation](https://github.com/phundament/docs)

## Deploy

Build your image

    docker-compose build

Tag it

    docker tag -f nano_php registry/vendor/image

And push it to a registry    
    
    docker push registry/vendor/image
    
---

Built by [*dmstr](http://diemeisterei.de), Stuttgart :de:
