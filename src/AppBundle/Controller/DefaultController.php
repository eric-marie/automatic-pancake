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
        $totalCount = $tirageRepository->getTotalCount();
        $top10 = $this->_getTop($numbersOrder, $totalCount, 10);

        return [
            'numbersOrder' => $numbersOrder,
            'top10' => $top10
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
        $starsBestFriendsOrder = $tirageRepository->getStarsBestFriendsOrder();
        $totalCount = $tirageRepository->getTotalCount();
        $totalCountBefore12Star = $tirageRepository->getTotalCountBefore12Star();
        $top5 = $this->_getTop($starsOrder, $totalCount);
        $top5BestFriends = $this->_getTopBestFriends('etoile', $starsBestFriendsOrder, $totalCount);

        return [
            'starsOrder' => $starsOrder,
            'starsBestFriendsOrder' => $starsBestFriendsOrder,
            'totalCount' => $totalCount,
            'totalCountBefore12Star' => $totalCountBefore12Star,
            'top5' => $top5,
            'top5BestFriends' => $top5BestFriends
        ];
    }

    /**
     * @param array $order
     * @param int $totalCount
     * @param int $count
     * @return array
     */
    private function _getTop($order, $totalCount, $count = 5)
    {
        $top = [];

        foreach ($order as $stat) {
            if (count($top) < $count) {
                $top[] = $stat;
                continue;
            }

            $weakestOccurrence = $totalCount + 1;
            $weakestIndex = 0;
            for ($i = 0; $i < $count; $i++) {
                if ($weakestOccurrence > $top[$i]['occurrence']) {
                    $weakestOccurrence = $top[$i]['occurrence'];
                    $weakestIndex = $i;
                }
            }

            if ($stat['occurrence'] > $weakestOccurrence) {
                $top[$weakestIndex] = $stat;
            }
        }

        usort($top, [self::class, '_callbackSortTop']);

        return $top;
    }

    /**
     * @param string $type
     * @param $BestFriendsOrder
     * @param int $totalCount
     * @param int $count
     * @return array
     */
    private function _getTopBestFriends($type, $BestFriendsOrder, $totalCount, $count = 5)
    {
        $topBestFriends = [];

        foreach ($BestFriendsOrder as $stat) {
            $newStat = $stat;
            if ($stat[$type] > $stat['duo']) {
                $newStat[$type] = $stat['duo'];
                $newStat['duo'] = $stat[$type];
            }

            if (count($topBestFriends) < $count) {
                $topBestFriends[] = $newStat;
                continue;
            }

            $weakestOccurrence = $totalCount + 1;
            $weakestIndex = 0;
            for ($i = 0; $i < $count; $i++) {
                if ($newStat[$type] == $topBestFriends[$i][$type] && $newStat['duo'] == $topBestFriends[$i]['duo'])
                    continue 2;
                if ($weakestOccurrence > $topBestFriends[$i]['occurrence']) {
                    $weakestOccurrence = $topBestFriends[$i]['occurrence'];
                    $weakestIndex = $i;
                }
            }

            if ($newStat['occurrence'] > $weakestOccurrence) {
                $topBestFriends[$weakestIndex] = $newStat;
            }
        }

        usort($topBestFriends, [self::class, '_callbackSortTop']);

        return $topBestFriends;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    private static function _callbackSortTop($a, $b)
    {
        if ($a['occurrence'] > $b['occurrence'])
            return -1;

        if ($a['occurrence'] < $b['occurrence'])
            return 1;

        return 0;
    }
}
