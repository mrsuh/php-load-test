<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    private $num = 0;

    /**
     * @Route("/")
     */
    public function number()
    {
        if (0 === $this->num) {
            $this->num = random_int(0, 100000);
        }

        return new JsonResponse([
            'random_num' => $this->num,
            'env'        => getenv('APP_ENV'),
            'type'       => getenv('APP_TYPE'),
            'pid'        => getmypid(),
            'php'        => [
                'version'                       => phpversion(),
                'date.timezone'                 => ini_get('date.timezone'),
                'short_open_tag'                => ini_get('short_open_tag'),
                'log_errors'                    => ini_get('log_errors'),
                'error_reporting'               => ini_get('error_reporting'),
                'display_errors'                => ini_get('display_errors'),
                'error_log'                     => ini_get('error_log'),
                'memory_limit'                  => ini_get('memory_limit'),
                'opcache.enable'                => ini_get('opcache.enable'),
                'opcache.memory_consumption'    => ini_get('opcache.memory_consumption'),
                'opcache.max_accelerated_files' => ini_get('opcache.max_accelerated_files'),
                'opcache.validate_timestamps'   => ini_get('opcache.validate_timestamps'),
                'realpath_cache_size'           => ini_get('realpath_cache_size'),
                'realpath_cache_ttl'            => ini_get('realpath_cache_ttl')
            ]
        ]);
    }
}