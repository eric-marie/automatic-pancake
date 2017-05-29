<?php

namespace AppBundle\Metier;

use AppBundle\Entity\Tirage;
use AppBundle\Repository\TirageRepository;

class FormValuesFinder
{
    const START_YEAR = 2004;

    /** @var array */
    private $formValues;

    /** @var TirageRepository */
    private $tirageRepository;

    /** @var string */
    private $cacheDir;

    /**
     * FormValuesFinder constructor.
     * @param TirageRepository $tirageRepository
     * @param string $cacheDir
     */
    public function __construct($tirageRepository, $cacheDir)
    {
        $this->tirageRepository = $tirageRepository;
        $this->cacheDir = str_replace('/', DS, str_replace('\\', DS, $cacheDir));
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
        if (!is_null($this->formValues)) {
            return $this->formValues;
        }

        $formValuesCacheDir = $this->cacheDir . DS . 'FormValues';
        if (!file_exists($formValuesCacheDir))
            mkdir($formValuesCacheDir);

        $formValues = [];
        $lastDay = 0;

        $pageContent = $this->_getPageContentByYear(date('Y'), $lastDay);
        $currentYearFormValues = $this->_getDaysFromPageContent($pageContent, $lastDay);

        for ($i = self::START_YEAR; $i < date('Y'); $i++) {
            $yearFormValuesCacheFile = $formValuesCacheDir . DS . $i . '.json';
            if (file_exists($yearFormValuesCacheFile)) {
                $jsonFormValues = file_get_contents($yearFormValuesCacheFile);
                $formValues[$i] = json_decode($jsonFormValues);
            } else {
                $pageContent = $this->_getPageContentByYear($i, $lastDay);
                $formValues[$i] = $this->_getDaysFromPageContent($pageContent, $lastDay);

                $jsonFormValues = json_encode($formValues[$i]);

                file_put_contents($yearFormValuesCacheFile, $jsonFormValues);
            }
        }

        $formValues[intval(date('Y'))] = $currentYearFormValues;

        $this->formValues = $formValues;

        return $this->_cleanFormValues();
    }

    /**
     * @param int $year
     * @param int $lastDay
     * @return \DOMDocument
     */
    private function _getPageContentByYear($year, $lastDay)
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
    private function _getDaysFromPageContent($pageContent, &$lastDay)
    {
        /** @var \DOMNodeList $selectList */
        $selectList = $pageContent->getElementsByTagName('select');
        /** @var \DOMElement $daySelect */
        $daySelect = $selectList->item(1);

        $result = [];

        for ($i = $daySelect->childNodes->length - 1; $i > 0; $i--) {
            $option = $daySelect->childNodes->item($i);
            $lastDay = $option->getAttribute('value');
            $result[] = $lastDay;
        }

        return $result;
    }

    /**
     * @return array|null
     */
    private function _cleanFormValues()
    {
        if (is_null($this->formValues))
            return null;

        $newFormValues = [];

        foreach ($this->formValues as $year => $foundDays) {
            if (0 == count($foundDays))
                continue;

            $savedDays = [];
            $neededDays = [];

            $savedTirage = $this->tirageRepository->findByYear($year);

            /** @var Tirage $tirage */
            foreach ($savedTirage as $tirage)
                $savedDays[] = $tirage->getJour()->format('Ymd');

            foreach ($foundDays as $day) {
                if (!in_array($day, $savedDays))
                    $neededDays[] = $day;
            }

            if (0 == count($neededDays))
                continue;

            $newFormValues[$year] = $neededDays;
        }

        $this->formValues = $newFormValues;

        return $this->formValues;
    }
}