<?php

namespace App\UI\Responders\Interfaces\Trick;

use Symfony\Component\HttpFoundation\Response;

interface HomePageResponderInterface
{
    /**
     * @param array $tricks
     * @return Response
     */
    public function homePageResponse(?array $tricks = []): Response;
}
