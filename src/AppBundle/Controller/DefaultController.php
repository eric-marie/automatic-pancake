<?php

namespace AppBundle\Controller;

use AppBundle\Metier\ResultPage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $test = new ResultPage('2017', '20170127');
        dump($test->getBoule1());
        dump($test->getBoule2());
        dump($test->getBoule3());
        dump($test->getBoule4());
        dump($test->getBoule5());
        dump($test->getEtoile1());
        dump($test->getEtoile2());
        dump($test->getJockerPlus());
        dump($test->getMyMillion());
        dump($test->getGains());

        die();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
