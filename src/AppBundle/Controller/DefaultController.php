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

        return [
            'totalCount' => $totalCount
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

        $formValueFinder = new FormValuesFinder($em->getRepository('AppBundle:Tirage'), $this->get('kernel')->getCacheDir());
        $formValues = $formValueFinder->getFormValues();

        $count = 0;
        $countMax = 30;
        foreach ($formValues as $year => $days) {
            foreach ($days as $day) {
                if ($count >= $countMax)
                    return [
                        'countMaxReached' => true,
                        'countMax' => $countMax
                    ];

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

        return [
            'countMaxReached' => false,
            'countMax' => $countMax
        ];
    }

    /**
     * @Route("/number-rate/", name="number-rate")
     * @Template()
     */
    public function numberRateAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TirageRepository $tirageRepository */
        $tirageRepository = $em->getRepository('AppBundle:Tirage');
        $numbersOrder = $tirageRepository->getNumbersOrder();
        $numbersBestFriendsOrder = $tirageRepository->getNumbersBestFriendsOrder([50]);

        return [
            'numbersOrder' => $numbersOrder,
            'numbersBestFriendsOrder' => $numbersBestFriendsOrder
        ];
    }

    /**
     * @Route("/star-rate/", name="star-rate")
     * @Template()
     */
    public function starRateAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TirageRepository $tirageRepository */
        $tirageRepository = $em->getRepository('AppBundle:Tirage');
        $starsOrder = $tirageRepository->getStarsOrder();
        $totalCount = $tirageRepository->getTotalCount();
        $totalCountBefore12Star = $tirageRepository->getTotalCountBefore12Star();

        return [
            'starsOrder' => $starsOrder,
            'totalCount' => $totalCount,
            'totalCountBefore12Star' => $totalCountBefore12Star
        ];
    }

    /**
     * @Route("star-best-friends/", name="star-best-friends")
     * @Template()
     */
    public function starBestFriendsAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TirageRepository $tirageRepository */
        $tirageRepository = $em->getRepository('AppBundle:Tirage');
        $starsBestFriendsOrder = $tirageRepository->getStarsBestFriendsOrder();
        $totalCount = $tirageRepository->getTotalCount();
        $totalCountBefore12Star = $tirageRepository->getTotalCountBefore12Star();

        $top5BestFriends = [];
        foreach ($starsBestFriendsOrder as $stat) {
            $newStat = $stat;
            if ($stat['etoile'] > $stat['duo']) {
                $newStat['etoile'] = $stat['duo'];
                $newStat['duo'] = $stat['etoile'];
            }

            if (count($top5BestFriends) < 5) {
                $top5BestFriends[] = $newStat;
                continue;
            }

            $weakestOccurrence = $totalCount + 1;
            $weakestIndex = 0;
            for ($i = 0; $i < 5; $i++) {
                if ($newStat['etoile'] == $top5BestFriends[$i]['etoile'] && $newStat['duo'] == $top5BestFriends[$i]['duo'])
                    continue 2;
                if ($weakestOccurrence > $top5BestFriends[$i]['occurrence']) {
                    $weakestOccurrence = $top5BestFriends[$i]['occurrence'];
                    $weakestIndex = $i;
                }
            }

            if ($newStat['occurrence'] > $weakestOccurrence) {
                $top5BestFriends[$weakestIndex] = $newStat;
            }
        }

        usort($top5BestFriends, [self::class, '_callbackSortTop5BestFriends']);

        return [
            'starsBestFriendsOrder' => $starsBestFriendsOrder,
            'totalCount' => $totalCount,
            'totalCountBefore12Star' => $totalCountBefore12Star,
            'top5BestFriends' => $top5BestFriends
        ];
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    private static function _callbackSortTop5BestFriends($a, $b)
    {
        if ($a['occurrence'] > $b['occurrence'])
            return -1;

        if ($a['occurrence'] < $b['occurrence'])
            return 1;

        return 0;
    }
}
