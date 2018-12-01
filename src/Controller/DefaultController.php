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
            'pid'        => getmypid()
        ]);
    }
}