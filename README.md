PHP Load test

* PHP 7.2

* React
* PHP-FPM
* Nginx-Unit

* Docker

Что необходимо сравнить:
PHP 7.2
Symfony 4
Nginx 1.15

Проксирование через Nginx
Взаимодействие через 

* React-PHP  (работает в 1 поток)
* PHP-FPM
* Nginx-Unit

На выходе должны быть 3 docker-compose

* react-php
* php-fpm
* nginx-unit

поставить максимальное количество потоков
php-react - количество upstreams nginx
php-fpm - количество потоков
php-nginx - количество потоков


## AB Test

### Nginx-unit
```
ab -c 100 -t 60 -r 'http://185.147.80.147:8000/'
This is ApacheBench, Version 2.3 <$Revision: 1826891 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 185.147.80.147 (be patient)
Completed 5000 requests
Completed 10000 requests
Completed 15000 requests
Finished 15669 requests


Server Software:        nginx/1.15.6
Server Hostname:        185.147.80.147
Server Port:            8000

Document Path:          /
Document Length:        37 bytes

Concurrency Level:      100
Time taken for tests:   60.004 seconds
Complete requests:      15669
Failed requests:        1593
   (Connect: 0, Receive: 0, Length: 1593, Exceptions: 0)
Total transferred:      3852793 bytes
HTML transferred:       577972 bytes
Requests per second:    261.13 [#/sec] (mean)
Time per request:       382.948 [ms] (mean)
Time per request:       3.829 [ms] (mean, across all concurrent requests)
Transfer rate:          62.70 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        3   22  41.9      7     406
Processing:    14  359 247.8    345    8374
Waiting:       10  359 247.5    344    8374
Total:         19  382 244.1    357    8405

Percentage of the requests served within a certain time (ms)
  50%    357
  66%    379
  75%    397
  80%    411
  90%    451
  95%    530
  98%    717
  99%   1073
 100%   8405 (longest request)
```

### React-PHP
```
ab -c 100 -t 60 -r 'http://185.147.80.147:8000/'
This is ApacheBench, Version 2.3 <$Revision: 1826891 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 185.147.80.147 (be patient)
Completed 5000 requests
Completed 10000 requests
Completed 15000 requests
Completed 20000 requests
Completed 25000 requests
Finished 26683 requests


Server Software:        nginx/1.15.7
Server Hostname:        185.147.80.147
Server Port:            8000

Document Path:          /
Document Length:        37 bytes

Concurrency Level:      100
Time taken for tests:   60.001 seconds
Complete requests:      26683
Failed requests:        1431
   (Connect: 0, Receive: 0, Length: 1431, Exceptions: 0)
Total transferred:      6802734 bytes
HTML transferred:       985840 bytes
Requests per second:    444.71 [#/sec] (mean)
Time per request:       224.868 [ms] (mean)
Time per request:       2.249 [ms] (mean, across all concurrent requests)
Transfer rate:          110.72 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        3   69  45.9     74     985
Processing:     4  127 304.0     99    5042
Waiting:        4  127 304.0     98    5042
Total:          8  197 303.6    174    5053

Percentage of the requests served within a certain time (ms)
  50%    174
  66%    198
  75%    204
  80%    208
  90%    240
  95%    302
  98%    506
  99%    651
 100%   5053 (longest request)
```

### PHP-FPM Docker
```
ab -c 100 -t 60 -r 'http://185.147.80.147:8000/'
This is ApacheBench, Version 2.3 <$Revision: 1826891 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 185.147.80.147 (be patient)
Completed 5000 requests
Completed 10000 requests
Completed 15000 requests
Completed 20000 requests
Finished 20633 requests


Server Software:        nginx/1.15.6
Server Hostname:        185.147.80.147
Server Port:            8000

Document Path:          /
Document Length:        37 bytes

Concurrency Level:      100
Time taken for tests:   60.016 seconds
Complete requests:      20633
Failed requests:        2045
   (Connect: 0, Receive: 0, Length: 2045, Exceptions: 0)
Total transferred:      5609895 bytes
HTML transferred:       761140 bytes
Requests per second:    343.79 [#/sec] (mean)
Time per request:       290.876 [ms] (mean)
Time per request:       2.909 [ms] (mean, across all concurrent requests)
Transfer rate:          91.28 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        3   12  16.9      8     241
Processing:    18  278  34.7    281     568
Waiting:       12  277  34.6    281     567
Total:         41  290  31.8    292     751

Percentage of the requests served within a certain time (ms)
  50%    292
  66%    304
  75%    311
  80%    315
  90%    326
  95%    336
  98%    352
  99%    371
 100%    751 (longest request)
```

## PHP-FPM native
```
ab -c 100 -t 60 -r 'http://185.147.80.147:9000/'
This is ApacheBench, Version 2.3 <$Revision: 1826891 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 185.147.80.147 (be patient)
Completed 5000 requests
Completed 10000 requests
Completed 15000 requests
Completed 20000 requests
Finished 21488 requests


Server Software:        nginx/1.14.1
Server Hostname:        185.147.80.147
Server Port:            9000

Document Path:          /
Document Length:        37 bytes

Concurrency Level:      100
Time taken for tests:   60.071 seconds
Complete requests:      21488
Failed requests:        2098
   (Connect: 0, Receive: 0, Length: 2098, Exceptions: 0)
Total transferred:      5842416 bytes
HTML transferred:       792736 bytes
Requests per second:    357.71 [#/sec] (mean)
Time per request:       279.556 [ms] (mean)
Time per request:       2.796 [ms] (mean, across all concurrent requests)
Transfer rate:          94.98 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        3   76  75.9     48     525
Processing:    27  201  57.0    208     908
Waiting:       15  200  57.0    207     908
Total:         32  277  62.9    261    1292

Percentage of the requests served within a certain time (ms)
  50%    261
  66%    284
  75%    297
  80%    304
  90%    334
  95%    392
  98%    489
  99%    528
 100%   1292 (longest request)
```