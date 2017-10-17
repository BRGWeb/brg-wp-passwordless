# BRG WP Passwordless

WordPress plugin for passwordless registration and login with 
* Facebook
* Google
* Twitter
* SMS/Email with Facebook Account Kit.

#### Plugin in active development. Should not be used in production... yet

## Requirements
* [Docker](https://www.docker.com/get-docker) / [Docker Compose](https://docs.docker.com/compose/install/) - for container version
* [Composer](https://getcomposer.org/download/)


## Instalation

Container Version
```
git clone https://github.com/BRGWeb/brg-wp-passwordless.git
cd brg-wp-paswordless
docker-compose up -d
```
Standard plugin version
```
#in wp-content/plugins
git clone https://github.com/BRGWeb/brg-wp-passwordless.git
cd brg-wp-paswordless
composer.phar install
```
After instalation activate the plugin in wp-admin as usual.
