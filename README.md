# BRG WP Passwordless

WordPress plugin for passwordless registration and login with 
* Facebook
* Google
* Twitter
* SMS/Email with Facebook Account Kit.

#### Plugin in active development. Should not be used in production... yet

## Requirements
* PHP >= 7.0
* HTTPS (APIs won't return to insecure connections)
* [Docker](https://www.docker.com/get-docker) / [Docker Compose](https://docs.docker.com/compose/install/) - for container version
* [Composer](https://getcomposer.org/download/)


## Instalation

Clone the repository inside `wp-content/plugins`

```sh
$ cd wp-content/plugins
$ git clone https://github.com/BRGWeb/brg-wp-passwordless.git
```

Get composer

```sh
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```

Optional: If you prefer use Docker, start project using `docker-compose`
```sh
$ cd brg-wp-paswordless
$ docker-compose up -d
```

After instalation activate the plugin in wp-admin as usual.

## Apps callbacks

 * Facebook `https://my-domain/wp-json/brg-wp-account-kit/v1/facebook-login/return`
 * Twitter `https://my-domain/wp-json/brg-wp-account-kit/v1/twitter-login/return`
 * Google `https://my-domain/wp-json/brg-wp-account-kit/v1/google-login/return?hauth.done=google`
