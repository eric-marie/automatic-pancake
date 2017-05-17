<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Gagnant;
use AppBundle\Entity\JokerPlus;
use AppBundle\Entity\MyMillion;
use AppBundle\Entity\Tirage;
use AppBundle\Metier\FormValuesFinder;
use AppBundle\Metier\ResultPage;
use AppBundle\Repository\TirageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TirageRepository $tirageRepository */
        $tirageRepository = $em->getRepository('AppBundle:Tirage');
        $totalCount = $tirageRepository->getTotalCount();
        $numbersOrder = $tirageRepository->getNumbersOrder();
        $numbersBestFriendsOrder = $tirageRepository->getNumbersBestFriendsOrder([50]);

        return [
            'totalCount' => $totalCount,
            'numbersOrder' => $numbersOrder,
            'numbersBestFriendsOrder' => $numbersBestFriendsOrder
        ];
    }

    /**
     * @Route("/update-data/", name="update-data")
     * @Template()
     */
    public function updateDataAction()
    {
        // TODO gérer le cas de plusieurs MyMillion comme le 20160610

        $em = $this->getDoctrine()->getManager();

        $formValueFinder = new FormValuesFinder($em->getRepository('AppBundle:Tirage'));
        $formValues = $formValueFinder->getFormValues();

        $count = 0;
        foreach ($formValues as $year => $days) {
            foreach ($days as $day) {
                if($count >= 30)
                    return $this->redirectToRoute('update-data');

                $resultPage = new ResultPage($year, $day);
                $newTirage = new Tirage();

                $newTirage->setJour(\DateTime::createFromFormat('Ymd', $day));

                $newTirage->setBoule1($resultPage->getBoule1());
                $newTirage->setBoule2($resultPage->getBoule2());
                $newTirage->setBoule3($resultPage->getBoule3());
                $newTirage->setBoule4($resultPage->getBoule4());
                $newTirage->setBoule5($resultPage->getBoule5());
                $newTirage->setEtoile1($resultPage->getEtoile1());
                $newTirage->setEtoile2($resultPage->getEtoile2());

                $em->persist($newTirage);
                $em->flush();

                if (!is_null($resultPage->getJockerPlus())) {
                    $newJokerPlus = new JokerPlus();
                    $newJokerPlus->setChiffre1($resultPage->getJockerPlus()[0]);
                    $newJokerPlus->setChiffre2($resultPage->getJockerPlus()[1]);
                    $newJokerPlus->setChiffre3($resultPage->getJockerPlus()[2]);
                    $newJokerPlus->setChiffre4($resultPage->getJockerPlus()[3]);
                    $newJokerPlus->setChiffre5($resultPage->getJockerPlus()[4]);
                    $newJokerPlus->setChiffre6($resultPage->getJockerPlus()[5]);
                    $newJokerPlus->setChiffre7($resultPage->getJockerPlus()[6]);
                    $newJokerPlus->setTirage($newTirage);
                    $em->persist($newJokerPlus);
                }

                if (!is_null($resultPage->getMyMillion())) {
                    $newMyMillion = new MyMillion();
                    $newMyMillion->setLettre1($resultPage->getMyMillion()[0]);
                    $newMyMillion->setLettre2($resultPage->getMyMillion()[1]);
                    $newMyMillion->setChiffre1($resultPage->getMyMillion()[2]);
                    $newMyMillion->setChiffre2($resultPage->getMyMillion()[3]);
                    $newMyMillion->setChiffre3($resultPage->getMyMillion()[4]);
                    $newMyMillion->setChiffre4($resultPage->getMyMillion()[5]);
                    $newMyMillion->setChiffre5($resultPage->getMyMillion()[6]);
                    $newMyMillion->setChiffre6($resultPage->getMyMillion()[7]);
                    $newMyMillion->setChiffre7($resultPage->getMyMillion()[8]);
                    $newMyMillion->setTirage($newTirage);
                    $em->persist($newMyMillion);
                }

                foreach ($resultPage->getGains() as $gain) {
                    $newGagnant = new Gagnant();
                    $newGagnant->setRang($gain['rang']);
                    $newGagnant->setBonsNumeros($gain['bonsNumeros']);
                    $newGagnant->setBonnesEtoiles($gain['bonnesEtoiles']);
                    $newGagnant->setNombre($gain['nombre']);
                    $newGagnant->setGains($gain['gains']);
                    $newGagnant->setTirage($newTirage);
                    $em->persist($newGagnant);
                }

                $em->flush();

                $count++;
            }
        }

        return [];
    }
}
