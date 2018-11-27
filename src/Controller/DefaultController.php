<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    private $time = 0;

    /**
     * @Route("/")
     */
    public function number()
    {
        if (0 === $this->time) {
            $this->time = random_int(0, 100000);
        }

        return new Response(
            '<html><body>Time: ' . $this->time . '</body></html>'
        );
    }
}