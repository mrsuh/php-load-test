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
+ copy tests/load.yaml from tests/load.yaml.example
and replace $SERVER and $PORT variables in tests/load.yaml

+ copy tests/monitoring.xml from tests/monitoring.xml.example
and replace $SERVER and $USERNAME variables in tests/monitoring.xml

+ get token from https://overload.yandex.net and put it to file tests/overload_token.txt

+ put ssh public key to target server

+ run yandex-tank:
```sh
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
* road-runner-reboot https://overload.yandex.net/151961
* react-php https://overload.yandex.net/150697
* react-php-reboot https://overload.yandex.net/152063

### Cumulative quantiles (ms)
|                    | 95%(ms) | 90%(ms) | 80%(ms) | 50%(ms) | HTTP OK(%) | HTTP OK(count) |
| ------------------ | ------- | ------- | ------- | ------- | ---------- | -------------- |
| php-fpm            | 9.9     | 6.3     | 4.35    | 3.59    | 100        | 57030          |
| php-ppm            | 9.4     | 6       | 3.88    | 3.16    | 100        | 57030          |
| nginx-unit         | 11      | 6.6     | 4.43    | 3.69    | 100        | 57030          |
| road-runner        | 8.1     | 5.1     | 3.53    | 2.92    | 100        | 57030          |
| road-runner-reboot | 12      | 8.6     | 5.3     | 3.85    | 100        | 57030          |
| react-php          | 8.5     | 4.91    | 3.29    | 2.74    | 100        | 57030          |
| react-php-reboot   | 13      | 8.5     | 5.5     | 3.95    | 100        | 57030          |

### Monitoring
|                    | cpu median(%) | cpu max(%) | memory median(MB) | memory max(MB) |
| ------------------ | ------------- | ---------- | ----------------- | -------------- |
| php-fpm            | 9.15          | 12.58      | 880.32            | 907.97         |
| php-ppm            | 7.08          | 13.68      | 901.72            | 913.80         |
| nginx-unit         | 9.56          | 12.54      | 923.02            | 943.90         |
| road-runner        | 5.57          | 8.61       | 992.71            | 1,001.46       |
| road-runner-reboot | 9.18          | 12.67      | 848.43            | 870.26         |
| react-php          | 4.53          | 6.58       | 1,004.68          | 1,009.91       |
| react-php-reboot   | 9.61          | 12.67      | 885.92            | 892.52         |


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
* road-runner-reboot https://overload.yandex.net/152011
* react-php https://overload.yandex.net/150717
* react-php-reboot https://overload.yandex.net/152064

### Cumulative quantiles (ms)
|                    | 95%(ms) | 90%(ms) | 80%(ms) | 50%(ms) | HTTP OK(%) | HTTP OK(count) |
| ------------------ | ------- | ------- | ------- | ------- | ---------- | -------------- |
| php-fpm            | 13      | 8.4     | 5.3     | 3.69    | 100        | 285030         |
| php-ppm            | 15      | 9       | 4.72    | 3.24    | 100        | 285030         |
| nginx-unit         | 12      | 8       | 5.5     | 3.93    | 100        | 285030         |
| road-runner        | 9.6     | 6       | 3.71    | 2.83    | 100        | 285030         |
| road-runner-reboot | 14      | 11      | 7.1     | 4.45    | 100        | 285030         |
| react-php          | 9.3     | 5.8     | 3.57    | 2.68    | 100        | 285030         |
| react-php-reboot   | 15      | 12      | 7.2     | 4.21    | 100        | 285030         |

### Monitoring
|                    | cpu median(%) | cpu max(%) | memory median(MB) | memory max(MB) |
| ------------------ | ------------- | ---------- | ----------------- | -------------- |
| php-fpm            | 41.68         | 48.33      | 1,006.06          | 1,015.09       |
| php-ppm            | 33.90         | 48.90      | 1,046.32          | 1,055.00       |
| nginx-unit         | 42.13         | 47.92      | 1,006.67          | 1,015.73       |
| road-runner        | 24.08         | 28.06      | 1,035.86          | 1,044.58       |
| road-runner-reboot | 46.23         | 52.04      | 939.63            | 948.08         |
| react-php          | 19.57         | 23.42      | 1,049.83          | 1,060.26       |
| react-php-reboot   | 41.30         | 47.89      | 957.01            | 958.56         |


## 1000 rps
```yaml
phantom:
    load_profile:
        load_type: rps
        schedule: line(1, 1000, 60s) const(1000, 60s)
```
### Overload links
* php-fpm https://overload.yandex.net/150841
* php-fpm-80 https://overload.yandex.net/153612
* php-ppm https://overload.yandex.net/150842
* nginx-unit https://overload.yandex.net/150843
* road-runner https://overload.yandex.net/150844
* road-runner-reboot https://overload.yandex.net/152068
* react-php https://overload.yandex.net/150846
* react-php-reboot https://overload.yandex.net/152065

### Cumulative quantiles (ms)
|                    | 95%(ms) | 90%(ms) | 80%(ms) | 50%(ms) | HTTP OK(%) | HTTP OK(count) |
| ------------------ | ------- | ------- | ------- | ------- | ---------- | -------------- |
| php-fpm            | 11050   | 11050   | 9040    | 195     | 80.67      | 72627          |
| php-fpm-80         | 3150    | 1375    | 1165    | 152     | 99.85      | 89895          |
| php-ppm            | 2785    | 2740    | 2685    | 2545    | 100        | 90030          |
| nginx-unit         | 98      | 80      | 60      | 21      | 100        | 90030          |
| road-runner        | 27      | 15      | 7.1     | 3.21    | 100        | 90030          |
| road-runner-reboot | 1110    | 1100    | 1085    | 1060    | 100        | 90030          |
| react-php          | 23      | 13      | 5.6     | 2.86    | 100        | 90030          |
| react-php-reboot   | 28      | 24      | 19      | 11      | 100        | 90030          |

### Monitoring
|                    | cpu median(%) | cpu max(%) | memory median(MB) | memory max(MB) |
| ------------------ | ------------- | ---------- | ----------------- | -------------- |
| php-fpm            | 12.66         | 78.25      | 990.16            | 1,006.56       |
| php-fpm-80         | 83.78         | 91.28      | 746.01            | 937.24         |
| php-ppm            | 66.16         | 91.20      | 1,088.74          | 1,102.92       |
| nginx-unit         | 78.11         | 88.77      | 1,010.15          | 1,062.01       |
| road-runner        | 42.93         | 54.23      | 1,010.89          | 1,068.48       |
| road-runner-reboot | 77.64         | 85.66      | 976.44            | 1,044.05       |
| react-php          | 36.39         | 46.31      | 1,018.03          | 1,088.23       |
| react-php-reboot   | 72.11         | 81.81      | 911.28            | 961.62         |


## 10000 rps
```yaml
phantom:
    load_profile:
        load_type: rps
        schedule: line(1, 10000, 30s) const(10000, 30s)
```

### Overload links
* php-fpm https://overload.yandex.net/150849
* php-fpm-80 https://overload.yandex.net/153615
* php-ppm https://overload.yandex.net/150874
* nginx-unit https://overload.yandex.net/150876
* road-runner https://overload.yandex.net/150881
* road-runner-reboot https://overload.yandex.net/152069
* react-php https://overload.yandex.net/150885
* react-php-reboot https://overload.yandex.net/152066

### Cumulative quantiles (ms)
|                    | 95%(ms) | 90%(ms) | 80%(ms) | 50%(ms) | HTTP OK(%) | HTTP OK(count) |
| ------------------ | ------- | ------- | ------- | ------- | ---------- | -------------- |
| php-fpm            | 11050   | 11050   | 11050   | 1880    | 70.466     | 317107         |
| php-fpm-80         | 3260    | 3140    | 1360    | 1145    | 99.619     | 448301         |
| php-ppm            | 2755    | 2730    | 2695    | 2605    | 100        | 450015         |
| nginx-unit         | 1020    | 1010    | 1000    | 980     | 100        | 450015         |
| road-runner        | 640     | 630     | 615     | 580     | 100        | 450015         |
| road-runner-reboot | 1130    | 1120    | 1110    | 1085    | 100        | 450015         |
| react-php          | 1890    | 1090    | 1045    | 58      | 99.996     | 449996         |
| react-php-reboot   | 3480    | 3070    | 1255    | 91      | 99.72      | 448753         |

### Monitoring
|                    | cpu median(%) | cpu max(%) | memory median(MB) | memory max(MB) |
| ------------------ | ------------- | ---------- | ----------------- | -------------- |
| php-fpm            | 5.57          | 79.35      | 984.47            | 998.78         |
| php-fpm-80         | 85.05         | 92.19      | 936.64            | 943.93         |
| php-ppm            | 66.86         | 82.41      | 1,089.31          | 1,097.41       |
| nginx-unit         | 86.14         | 93.94      | 1,067.71          | 1,069.52       |
| road-runner        | 73.41         | 82.72      | 1,129.48          | 1,134.00       |
| road-runner-reboot | 80.32         | 86.29      | 982.69            | 984.80         |
| react-php          | 73.76         | 82.18      | 1,101.71          | 1,105.06       |
| react-php-reboot   | 85.77         | 91.92      | 975.85            | 978.42         |

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
