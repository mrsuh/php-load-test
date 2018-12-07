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
cd docker/react-php && docker-compose up
```

### Road Runner
```sh
cd docker/road-runner && docker-compose up
```

## Tests
```
cp tests/load.yaml.example tests/load.yaml
cp tests/monitoring.xml.example tests/monitoring.xml
echo 'token' > tests/overload_token.txt
docker run -v $(pwd):/var/loadtest -v $HOME/.ssh:/root/.ssh -it direvius/yandex-tank
```