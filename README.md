
# Golang-Php-PubSub

## What is this?
This is just a toy project to replicate a scenario whereby we would have a golang service that sends in message queues to a laravel application via rabbitmq.

## Setup (Development)
- Have docker installed with docker compose plugin

- Run `cp .env.example .env` on both `disk-space-monitor` and `laravel-subscriber-app` to copy the initialize the `.env` files with default. 
- Run `composer install` in laravel dir.
- Run `docker compose up -d` at root folder to open up rabbitmq (do update accordingly if you need to change any config ie username, password).
- Running Laravel subscriber app: `php artisan queue:work` or `php artisan rabbitmq:consume`
- Running golang script: either run `go run .` or use sane tools for development like https://github.com/mitranim/gow with watch mode. (Can't believe there is no watch mode with the default go command ????)
- There will be logs showing it receive update from the go script. (https://flic.kr/p/2p2JfyU)


## Setup (Production)
### Php Application
-  Run `cp .env.example .env` , update the rabbitmq parts of the .env and composer install.
- In production you definitely won't be running `php artisan queue:work` but instead use something like supervisord (http://supervisord.org/) as a process daemon to as your workers.

### Rabbitmq
- Install it (https://www.rabbitmq.com/install-debian.html)
- Copy the content of `rabbitmq.conf` inside message broker folder and paste it at the correct location (OS dependent/https://www.rabbitmq.com/configure.html)


### Golang Application
- Run `go build -o disk-space-checker` to build an executable binary.
- Same as the php application, we would use a daemon or some sort to run it like supervisord (http://supervisord.org/).

## Notes
* I added ticker in the golang code simply to check and I think it somewhat make sense for a real world use case scenario.
* I have no idea if this would actually work for scenario where you have a windows host and a ubuntu sitting in a VirtualBox but in theory it should. Have no access to a windows machine at the moment and short of time left to finish this.