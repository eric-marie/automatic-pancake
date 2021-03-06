<?php

namespace AppBundle\Metier;

class ResultPage
{
    const BONUS_TYPE_JOKER_PLUS = 'Joker+';
    const BONUS_TYPE_MY_MILLION = 'My Million';

    // Argument Année et Jour pour l'appel de la page
    private $year;
    private $day;

    /** @var \DOMDocument Contenu de la page */
    private $pageContent;

    // Résultat du tirage
    private $boule1;
    private $boule2;
    private $boule3;
    private $boule4;
    private $boule5;
    private $etoile1;
    private $etoile2;
    private $jockerPlus;
    private $myMillion;

    // Tableau récapitulatif des gains
    private $gains;

    /**
     * ResultPage constructor.
     * @param string $year
     * @param string $day
     */
    public function __construct($year, $day)
    {
        $this->year = $year;
        $this->day = $day;
    }

    /**
     * @return \DOMDocument
     */
    private function getPageContent()
    {
        if (!empty($this->pageContent))
            return $this->pageContent;

        $postData = http_build_query([
            'Y' => $this->year,
            'D' => $this->day,
            'T' => '1'
        ]);
        $header = [
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: " . strlen($postData)
        ];
        $request = [
            'http' => [
                'method' => 'POST',
                'header' => $header,
                'content' => $postData
            ]
        ];

        $context = stream_context_create($request);

        $html = file_get_contents('http://www.secretsdujeu.com/euromillion/resultat', false, $context);

        $this->pageContent = new \DOMDocument();
        libxml_use_internal_errors(true);
        $this->pageContent->loadHTML($html);
        libxml_use_internal_errors(false);

        return $this->pageContent;
    }

    /**
     * @return int
     */
    public function getBoule1()
    {
        if (!is_null($this->boule1))
            return $this->boule1;

        $this->boule1 = $this->getResultNumberByIndex(0);

        return $this->boule1;
    }

    /**
     * @return int
     */
    public function getBoule2()
    {
        if (!is_null($this->boule2))
            return $this->boule2;

        $this->boule2 = $this->getResultNumberByIndex(1);

        return $this->boule2;
    }

    /**
     * @return int
     */
    public function getBoule3()
    {
        if (!is_null($this->boule3))
            return $this->boule3;

        $this->boule3 = $this->getResultNumberByIndex(2);

        return $this->boule3;
    }

    /**
     * @return int
     */
    public function getBoule4()
    {
        if (!is_null($this->boule4))
            return $this->boule4;

        $this->boule4 = $this->getResultNumberByIndex(3);

        return $this->boule4;
    }

    /**
     * @return int
     */
    public function getBoule5()
    {
        if (!is_null($this->boule5))
            return $this->boule5;

        $this->boule5 = $this->getResultNumberByIndex(4);

        return $this->boule5;
    }

    /**
     * @return int
     */
    public function getEtoile1()
    {
        if (!is_null($this->etoile1))
            return $this->etoile1;

        $this->etoile1 = $this->getResultNumberByIndex(6);

        return $this->etoile1;
    }

    /**
     * @return int
     */
    public function getEtoile2()
    {
        if (!is_null($this->etoile2))
            return $this->etoile2;

        $this->etoile2 = $this->getResultNumberByIndex(7);

        return $this->etoile2;
    }

    /**
     * @param int $index
     * @return string
     */
    private function getResultNumberByIndex($index)
    {
        /** @var \DOMElement $boulesElt */
        $boulesElt = $this->getPageContent()->getElementById('boules');
        /** @var \DOMNodeList $boules */
        $boules = $boulesElt->getElementsByTagName('p');
        /** @var \DOMElement $boule */
        $boule = $boules->item($index);
        /** @var \DOMText $bouleText */
        $bouleText = $boule->firstChild;

        return $bouleText->wholeText;
    }

    /**
     * @return array of int
     * Exemple pour Joker+ 8 154 956
     * ['8', '1', '5', '4', '9', '5', '6']
     */
    public function getJockerPlus()
    {
        if (!is_null($this->jockerPlus))
            return $this->jockerPlus;

        return $this->getBonusCodeByType(self::BONUS_TYPE_JOKER_PLUS);
    }

    /**
     * @return array of char / int
     * Exemple pour My Million BS 375 1626
     * ['B', 'S', '3', '7', '5', '1', '6', '2', '6']
     */
    public function getMyMillion()
    {
        if (!is_null($this->myMillion))
            return $this->myMillion;

        return $this->getBonusCodeByType(self::BONUS_TYPE_MY_MILLION);
    }

    /**
     * @param string $type
     * @return array|null
     */
    private function getBonusCodeByType($type)
    {
        /** @var \DOMElement $mymillionElt */
        $mymillionElt = $this->getPageContent()->getElementById('mymillion-link');

        if(is_null($mymillionElt))
            return null;

        /** @var \DOMNodeList $info */
        $info = $mymillionElt->getElementsByTagName('span');
        /** @var \DOMText $typeText */
        $typeText = $info->item(0)->firstChild;
        /** @var \DOMText $bonusText */
        $bonusText = $info->item(1)->firstChild;

        if ($type != $typeText->wholeText)
            return null;

        $code = str_replace(' ', '', $bonusText->wholeText);
        return str_split($code);
    }

    /**
     * @return array
     * Exemple pour :
     * Rang 1 0 gagant 0 € de gain
     * Rang 2 12 gagnants 512 739,20 € de gain
     * ...
     * [
     *   [
     *      "rang" => 1,
     *      "nombre" => 0,
     *      "gains" => 0
     *   ],
     *   [
     *      "rang" => 2,
     *      "nombre" => 12,
     *      "gains" => 512739.2
     *   ],
     *   ...
     * ]
     */
    public function getGains()
    {
        if (!is_null($this->gains))
            return $this->gains;

        /** @var \DOMElement $panelResult */
        $panelResult = $this->getPageContent()->getElementsByTagName('table')->item(0);

        $results = [];
        $level = 1;
        /** @var \DOMElement $resultElt */
        foreach ($panelResult->getElementsByTagName('tr') as $resultElt) {
            /** @var \DOMNodeList $infoCells */
            $infoCells = $resultElt->getElementsByTagName('td');

            if (5 != $infoCells->length) continue; // On est dans le <thead>

            /** @var \DOMText $rank */
            $rank = $infoCells->item(1)->firstChild->firstChild;
            /** @var \DOMText $winnerCount */
            $winnerCount = $infoCells->item(2)->firstChild->firstChild;
            /** @var \DOMText $winnerPrize */
            $winnerPrize = $infoCells->item(3)->firstChild->firstChild;

            $rankVal = intval($rank->wholeText[0]);

            $winnerCountVal = intval(preg_replace('/[^0-9]/', '', $winnerCount->wholeText));

            if (0 == $winnerCountVal) $winnerPrizeVal = 0;
            else {
                $winnerPrizeVal = str_replace(',', '.', $winnerPrize->wholeText);
                $winnerPrizeVal = preg_replace('/[^0-9\.]/', '', $winnerPrizeVal);
                $winnerPrizeVal = floatval($winnerPrizeVal);
            }

            $results[] = [
                'rang' => $level++,
                'bonsNumeros' => $rankVal,
                'bonnesEtoiles' => $infoCells->item(1)->childNodes->length - 1,
                'nombre' => $winnerCountVal,
                'gains' => $winnerPrizeVal
            ];
        }

        return $results;
    }
}