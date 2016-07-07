<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TestController extends Controller
{
    /**
     * @Route("/test")
     */
    public function testAction()
    {
        $class = $this->get('app_service')->buildNav();
        $em = $this
            ->getDoctrine()
            ->getManager();

        return $this->render('default\newpage.html.twig', [
            'classes' => $class
        ]);
    }
}
