# PHP Load test

* PHP FPM
* PHP PPM
* Nginx Unit
* React PHP
* Road-runner

### Application
* PHP 7.2
* Symfony 4

## Installation
### PHP FPM
```sh
cd docker/php-fpm && docker-compose up
```

### PHP PPM
```sh
cd docker/php-ppm && docker-compose up
```

### Nginx Unit
```sh
cd docker/nginx-unit && docker-compose up
```

### React PHP
```sh
cd docker/react-php && docker-compose up --scale php=2
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
```sh
cp tests/load.yaml.example tests/load.yaml
cp tests/monitoring.xml.example tests/monitoring.xml
echo 'token' > tests/overload_token.txt
cd tests && docker run -v $(pwd):/var/loadtest -v $HOME/.ssh:/root/.ssh --net host -it direvius/yandex-tank
```

## 100 rps
```yaml
phantom:
    load_profile:
        load_type: rps
        schedule: line(1, 100, 60s) const(100, 540s)
```

### Overload links
* php-fpm https://overload.yandex.net/150666
* php-ppm https://overload.yandex.net/150670
* nginx-unit https://overload.yandex.net/150675
* road-runner https://overload.yandex.net/150681
* react-php https://overload.yandex.net/150697

### Cumulative quantiles (ms)
|             | 99%(ms) | 98%(ms) | 95% (ms) | 90% (ms) | 85% (ms) | 80% (ms) | 75%(ms) | 50%(ms) | HTTP OK (%) | HTTP OK (count) |
|-------------|---------|---------|----------|----------|----------|----------|---------|---------|-------------|-----------------|
| php-fpm     | 18      | 14      | 9.900    | 6.300    | 4.920    | 4.350    | 4.080   | 3.590   | 100         | 57030           |
| php-ppm     | 18      | 14      | 9.400    | 6        | 4.510    | 3.880    | 3.580   | 3.160   | 100         | 57030           |
| nginx-unit  | 19      | 15      | 11       | 6.600    | 5.100    | 4.430    | 4.150   | 3.690   | 100         | 57030           |
| road-runner | 16      | 13      | 8.100    | 5.100    | 4        | 3.530    | 3.300   | 2.920   | 100         | 57030           |
| react-php   | 17      | 13      | 8.500    | 4.910    | 3.730    | 3.280    | 3.090   | 2.740   | 100         | 57030           |

### Monitoring
|             | cpu user usage median (%) | cpu user usage max (%) | cpu system usage median (%) | cpu system usage max (%) | memory used median (bytes) | memory used max (bytes) |
|-------------|---------------------------|------------------------|-----------------------------|--------------------------|----------------------------|-------------------------|
| php-fpm     | 6.599                     | 8.543                  | 2.551                       | 4.040                    | 923,086,848                | 952,074,240             |
| php-ppm     | 4.545                     | 9.137                  | 2.538                       | 4.545                    | 945,522,688                | 958,193,664             |
| nginx-unit  | 6.533                     | 8.040                  | 3.030                       | 4.500                    | 967,860,224                | 989,753,344             |
| road-runner | 3.046                     | 4.569                  | 2.525                       | 4.040                    | 1,040,932,864              | 1,050,112,000           |
| react-php   | 3                         | 4.040                  | 1.531                       | 2.538                    | 1,053,478,912              | 1,058,971,648           |


## 500 rps
```yaml

phantom:
    load_profile:
        load_type: rps
        schedule: line(1, 500, 60s) const(500, 540s)
```

### Overload links
* php-fpm https://overload.yandex.net/150705
* php-ppm https://overload.yandex.net/150710
* nginx-unit https://overload.yandex.net/150711
* road-runner https://overload.yandex.net/150715
* react-php https://overload.yandex.net/150717

### Cumulative quantiles (ms)
|             | 99%(ms) | 98%(ms) | 95% (ms) | 90% (ms) | 85% (ms) | 80% (ms) | 75%(ms) | 50%(ms) | HTTP OK (%) | HTTP OK (count) |
|-------------|---------|---------|----------|----------|----------|----------|---------|---------|-------------|-----------------|
| php-fpm     | 21      | 17      | 13       | 8.400    | 6.300    | 5.300    | 4.760   | 3.690   | 100         | 285030          |
| php-ppm     | 35      | 24      | 15       | 9        | 6.200    | 4.720    | 4.090   | 3.240   | 100         | 285030          |
| nginx-unit  | 21      | 17      | 12       | 8.100    | 6.300    | 5.500    | 4.960   | 3.940   | 100         | 285030          |
| road-runner | 18      | 14      | 9.600    | 6        | 4.400    | 3.710    | 3.360   | 2.830   | 100         | 285030          |
| react-php   | 18      | 14      | 9.300    | 5.800    | 4.260    | 3.570    | 3.230   | 2.680   | 100         | 285030          |

### Monitoring
|             | cpu user usage median (%) | cpu user usage max (%) | cpu system usage median (%) | cpu system usage max (%) | memory used median (bytes) | memory used max (bytes) |
|-------------|---------------------------|------------------------|-----------------------------|--------------------------|----------------------------|-------------------------|
| php-fpm     | 30.570                    | 34.184                 | 11.111                      | 14.141                   | 1,054,930,944              | 1,064,402,944           |
| php-ppm     | 22.165                    | 33.163                 | 11.735                      | 15.736                   | 1,097,146,368              | 1,106,251,776           |
| nginx-unit  | 30.729                    | 34.375                 | 11.399                      | 13.542                   | 1,055,565,824              | 1,065,070,592           |
| road-runner | 13.333                    | 15.625                 | 10.742                      | 12.435                   | 1,086,179,328              | 1,095,323,648           |
| react-php   | 12.051                    | 14.286                 | 7.519                       | 9.137                    | 1,100,822,528              | 1,111,760,896           |


## 1000 rps
```yaml
phantom:
    load_profile:
        load_type: rps
        schedule: line(1, 1000, 60s) const(1000, 60s)
```
### Overload links
* php-fpm https://overload.yandex.net/150841
* php-ppm https://overload.yandex.net/150842
* nginx-unit https://overload.yandex.net/150843
* road-runner https://overload.yandex.net/150844
* react-php https://overload.yandex.net/150846

### Cumulative quantiles (ms)

|             | 99%(ms) | 98%(ms) | 95%(ms) | 90%(ms) | 85%(ms) | 80%(ms) | 75%(ms) | 50%(ms) | HTTP OK (%) | HTTP OK (count) |
|-------------|---------|---------|---------|---------|---------|---------|---------|---------|-------------|-----------------|
| php-fpm     | 11050   | 11050   | 11050   | 11050   | 11050   | 9040    | 4080    | 195     | 80.670      | 72627           |
| php-ppm     | 2880    | 2855    | 2785    | 2740    | 2710    | 2685    | 2665    | 2545    | 100         | 90030           |
| nginx-unit  | 126     | 115     | 98      | 80      | 69      | 60      | 54      | 21      | 100         | 90030           |
| road-runner | 55      | 44      | 27      | 15      | 9.900   | 7.100   | 5.500   | 3.210   | 100         | 90030           |
| react-php   | 52      | 40      | 23      | 13      | 7.800   | 5.600   | 4.360   | 2.860   | 100         | 90030           |

### Monitoring
|             | cpu user usage median (%) | cpu user usage max (%) | cpu system usage median (%) | cpu system usage max (%) | memory used median (bytes) | memory used max (bytes) |
|-------------|---------------------------|------------------------|-----------------------------|--------------------------|----------------------------|-------------------------|
| php-fpm     | 8.122                     | 58.763                 | 4.534                       | 19.487                   | 1,038,258,176              | 1,055,453,184           |
| php-ppm     | 50                        | 66.327                 | 16.162                      | 24.873                   | 1,141,622,784              | 1,156,497,408           |
| nginx-unit  | 58.823                    | 65.657                 | 19.289                      | 23.116                   | 1,059,219,456              | 1,113,595,904           |
| road-runner | 23.834                    | 29.231                 | 19.095                      | 25                       | 1,059,991,552              | 1,120,387,072           |
| react-php   | 22.364                    | 26.601                 | 14.029                      | 19.704                   | 1,067,479,040              | 1,141,092,352           |


## 10000 rps
```yaml
phantom:
    load_profile:
        load_type: rps
        schedule: line(1, 10000, 30s) const(10000, 30s)
```

### Overload links
* php-fpm https://overload.yandex.net/150849
* php-ppm https://overload.yandex.net/150874
* nginx-unit https://overload.yandex.net/150876
* road-runner https://overload.yandex.net/150881
* react-php https://overload.yandex.net/150885

### Cumulative quantiles (ms)
|             | 99%(ms) | 98%(ms) | 95%(ms) | 90%(ms) | 85%(ms) | 80%(ms) | 75%(ms) | 50%(ms) | HTTP OK (%) | HTTP OK (count) |
|-------------|---------|---------|---------|---------|---------|---------|---------|---------|-------------|-----------------|
| php-fpm     | 11050   | 11050   | 11050   | 11050   | 11050   | 11050   | 11050   | 1880    | 70.466      | 317107          |
| php-ppm     | 2815    | 2795    | 2755    | 2730    | 2710    | 2695    | 2680    | 2605    | 100         | 450015          |
| nginx-unit  | 1035    | 1030    | 1020    | 1010    | 1005    | 1000    | 995     | 980     | 100         | 450015          |
| road-runner | 655     | 650     | 640     | 630     | 620     | 615     | 610     | 580     | 100         | 450015          |
| react-php   | 3300    | 3080    | 1890    | 1090    | 1060    | 1045    | 1035    | 58      | 99.996      | 449996          |

### Monitoring
|             | cpu user usage median (%) | cpu user usage max (%) | cpu system usage median (%) | cpu system usage max (%) | memory used median (bytes) | memory used max (bytes) |
|-------------|---------------------------|------------------------|-----------------------------|--------------------------|----------------------------|-------------------------|
| php-fpm     | 2.538                     | 59.043                 | 3.030                       | 20.305                   | 1,032,294,400              | 1,047,293,952           |
| php-ppm     | 51.031                    | 58.794                 | 15.829                      | 23.618                   | 1,142,228,992              | 1,150,717,952           |
| nginx-unit  | 64.824                    | 68.687                 | 21.32                       | 25.248                   | 1,119,574,016              | 1,121,476,608           |
| road-runner | 43.256                    | 47.449                 | 30.151                      | 35.266                   | 1,184,348,160              | 1,189,081,088           |
| react-php   | 49.254                    | 53.608                 | 24.510                      |                          | 1,155,225,600              | 1,158,742,016           |


## Charts

### Test log directories
```sh
test_logs
    /php-fpm
    /php-ppm
    /nginx-unit
    /road-runner
    /react-php
```

### Generate charts
```sh
php tests/charts/generate.php /path/to/test_logs /path/to/output/file.html
Input dir: /path/to/test_logs
Output file: tank_100.html
Handling /path/to/test_logs/nginx-unit/monitoring.log...
Handling /path/to/test_logs/nginx-unit/phout_DIv_wS.log...
Handling /path/to/test_logs/php-fpm/monitoring.log...
Handling /path/to/test_logs/php-fpm/phout_j5yRj3.log...
Handling /path/to/test_logs/php-ppm/monitoring.log...
Handling /path/to/test_logs/php-ppm/phout_aEAoEM.log...
Handling /path/to/test_logs/react-php/monitoring.log...
Handling /path/to/test_logs/react-php/phout_u50Gwi.log...
Handling /path/to/test_logs/road-runner/monitoring.log...
Handling /path/to/test_logs/road-runner/phout_RSKT30.log...
Done!
```