<?php

namespace AppBundle\Metier;

use AppBundle\Entity\Tirage;

class FormValuesFinder
{
    const START_YEAR = 2004;

    /** @var array */
    private $formValues;

    /** @var Tirage */
    private $higherTirage;

    /**
     * FormValuesFinder constructor.
     * @param Tirage $higherTirage
     */
    public function __construct($higherTirage)
    {
        $this->higherTirage = $higherTirage;
    }

    /**
     * @return array
     * Exemple
     * [
     *   ...,
     *   2005 => [
     *      ...,
     *      20050128,
     *      20050121,
     *      20050114,
     *      20050107
     *   ],
     *   2004 => [
     *      ...,
     *      20040305,
     *      20040227,
     *      20040220,
     *      20040213
     *   ]
     * ]
     */
    public function getFormValues()
    {
        if (!is_null($this->formValues))
            return $this->formValues;

        $formValues = [];
        $lastDay = 0;

        $pageContent = $this->getPageContentByYear(date('Y'), $lastDay);
        $currentYearFormValues = $this->getDaysFromPageContent($pageContent, $lastDay);

        for ($i = self::START_YEAR; $i < date('Y'); $i++) {
            if (!is_null($this->higherTirage)) {
                $year = intval($this->higherTirage->getJour()->format('Y'));
                if ($year > $i) continue;
            }
            $pageContent = $this->getPageContentByYear($i, $lastDay);
            $formValues[$i] = $this->getDaysFromPageContent($pageContent, $lastDay);
        }

        $formValues[intval(date('Y'))] = $currentYearFormValues;

        $this->formValues = $formValues;

        return $this->formValues;
    }

    /**
     * @param int $year
     * @param int $lastDay
     * @return \DOMDocument
     */
    private function getPageContentByYear($year, $lastDay)
    {
        if (date('Y') == $year)
            $html = file_get_contents('http://www.secretsdujeu.com/euromillion/resultat');
        else {
            $postData = http_build_query([
                'Y' => $year,
                'D' => $lastDay,
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
        }

        $pageContent = new \DOMDocument();
        libxml_use_internal_errors(true);
        $pageContent->loadHTML($html);
        libxml_use_internal_errors(false);

        return $pageContent;
    }

    /**
     * @param \DOMDocument $pageContent
     * @param int &$lastDay
     * @return array
     */
    private function getDaysFromPageContent($pageContent, &$lastDay)
    {
        /** @var \DOMNodeList $selectList */
        $selectList = $pageContent->getElementsByTagName('select');
        /** @var \DOMElement $daySelect */
        $daySelect = $selectList->item(1);

        $result = [];

        for ($i = $daySelect->childNodes->length - 1; $i > 0; $i--) {
            $option = $daySelect->childNodes->item($i);
            $lastDay = $option->getAttribute('value');
            if(!is_null($this->higherTirage) && intval($this->higherTirage->getJour()->format('Ymd')) >= $lastDay)
                continue;
            $result[] = $lastDay;
        }

        return $result;
    }
}