<?php

namespace AppBundle\Metier;

class ResultPage
{
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
        if (!empty($this->boule1))
            return $this->boule1;

        $this->boule1 = $this->getResultNumberByIndex(0);

        return $this->boule1;
    }

    /**
     * @return int
     */
    public function getBoule2()
    {
        if (!empty($this->boule2))
            return $this->boule2;

        $this->boule1 = $this->getResultNumberByIndex(1);

        return $this->boule2;
    }

    /**
     * @return int
     */
    public function getBoule3()
    {
        if (!empty($this->boule3))
            return $this->boule3;

        $this->boule1 = $this->getResultNumberByIndex(2);

        return $this->boule3;
    }

    /**
     * @return int
     */
    public function getBoule4()
    {
        if (!empty($this->boule4))
            return $this->boule4;

        $this->boule1 = $this->getResultNumberByIndex(3);

        return $this->boule4;
    }

    /**
     * @return int
     */
    public function getBoule5()
    {
        if (!empty($this->boule5))
            return $this->boule5;

        $this->boule1 = $this->getResultNumberByIndex(4);

        return $this->boule5;
    }

    /**
     * @return int
     */
    public function getEtoile1()
    {
        if (!empty($this->etoile1))
            return $this->etoile1;

        $this->boule1 = $this->getResultNumberByIndex(5);

        return $this->etoile1;
    }

    /**
     * @return int
     */
    public function getEtoile2()
    {
        if (!empty($this->etoile2))
            return $this->etoile2;

        $this->boule1 = $this->getResultNumberByIndex(6);

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
        dump($bouleText);

        return $bouleText->wholeText;
    }

    /**
     * @return array of int
     * Exemple pour Joker+ 8 154 956
     * ['8', '1', '5', '4', '9', '5', '6']
     */
    public function getJockerPlus()
    {
        if (!empty($this->jockerPlus))
            return $this->jockerPlus;

        // TODO : aller chercher l'info
    }

    /**
     * @return array of char / int
     * Exemple pour My Million BS 375 1626
     * ['B', 'S', '3', '7', '5', '1', '6', '2', '6']
     */
    public function getMyMillion()
    {
        if (!empty($this->myMillion))
            return $this->myMillion;

        // TODO : aller chercher l'info
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
     *      "gagnants" => 0,
     *      "gain" => 0
     *   ],
     *   [
     *      "rang" => 2,
     *      "gagnants" => 12,
     *      "gain" => 512739.2
     *   ],
     *   ...
     * ]
     */
    public function getGains()
    {
        if (!empty($this->gains))
            return $this->gains;

        // TODO : aller chercher l'info
    }
}