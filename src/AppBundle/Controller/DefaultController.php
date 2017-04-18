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
        $test2 = $test->getBoule1();
        dump($test2);
        $test2 = $test->getBoule2();
        dump($test2);
        $test2 = $test->getBoule3();
        dump($test2);
        $test2 = $test->getBoule4();
        dump($test2);
        $test2 = $test->getBoule5();
        dump($test2);
        $test2 = $test->getEtoile1();
        dump($test2);
        $test2 = $test->getEtoile2();
        dump($test2);

        die();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
