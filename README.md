# PHP Load test

* PHP 7.2
* Symfony 4

* PHP FPM
* Nginx Unit
* React PHP
* Road-runner


## Installation
### PHP FPM
```sh
cd docker/php-fpm && docker-compose up
```

### Nginx Unit
```sh
cd docker/nginx-unit && docker-compose up
```

### React PHP
```sh
cd docker/react-php && docker-compose up --scale php=20
```

### Road Runner
```sh
cd docker/road-runner && docker-compose up
```

## Example
```sh
curl 'http://127.0.0.1:8000/' | python -m json.tool
{
    "env": "prod",
    "type": "php-fpm",
    "pid": 8,
    "random_num": 37264,
    "php": {
        "version": "7.2.12",
        "date.timezone": "Europe/Paris",
        "display_errors": "",
        "error_log": "/proc/self/fd/2",
        "error_reporting": "32767",
        "log_errors": "1",
        "memory_limit": "256M",
        "opcache.enable": "1",
        "opcache.max_accelerated_files": "20000",
        "opcache.memory_consumption": "256",
        "opcache.validate_timestamps": "0",
        "realpath_cache_size": "4096K",
        "realpath_cache_ttl": "600",
        "short_open_tag": ""
    }
}
```

## Tests
```
cp tests/load.yaml.example tests/load.yaml
cp tests/monitoring.xml.example tests/monitoring.xml
echo 'token' > tests/overload_token.txt
cd tests && docker run -v $(pwd):/var/loadtest -v $HOME/.ssh:/root/.ssh --net host -it direvius/yandex-tank
```