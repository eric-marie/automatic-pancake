<?php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    const NUMBER_COUNT = 5;
    const NUMBER_MAX = 50;
    const STAR_COUNT = 2;
    const STAR_MAX = 12;

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('probabilityBall', array($this, 'probabilityBallFunction')),
            new \Twig_SimpleFunction('probabilityStar', array($this, 'probabilityStarFunction')),
            new \Twig_SimpleFunction('probabilityEuroMillion', array($this, 'probabilityEuroMillionFunction')),
            new \Twig_SimpleFunction('probabilityFormula', array($this, 'probabilityFormulaFunction')),
        );
    }

    /**
     * @return int
     */
    public function probabilityBallFunction()
    {
        $proba = 1;
        for ($i = self::NUMBER_MAX ; $i > self::NUMBER_MAX - self::NUMBER_COUNT ; $i--)
            $proba *= $i;

        $subProba = 1;
        for ($i = self::NUMBER_COUNT ; $i > 0 ; $i--)
            $subProba *= $i;

        $proba /= $subProba;

        return $proba;
    }

    /**
     * @return int
     */
    public function probabilityStarFunction()
    {
        $proba = 1;
        for ($i = self::STAR_MAX ; $i > self::STAR_MAX - self::STAR_COUNT ; $i--)
            $proba *= $i;

        $subProba = 1;
        for ($i = self::STAR_COUNT ; $i > 0 ; $i--)
            $subProba *= $i;

        $proba /= $subProba;

        return $proba;
    }

    /**
     * @return int
     */
    public function probabilityEuroMillionFunction()
    {
        return $this->probabilityBallFunction() * $this->probabilityStarFunction();
    }

    /**
     * @return string
     */
    public function probabilityFormulaFunction()
    {
        $formula = '[ (';

        $numbers = array();
        for ($i = self::NUMBER_MAX ; $i > self::NUMBER_MAX - self::NUMBER_COUNT ; $i--)
            $numbers[] = $i;
        $formula .= implode(' x ', $numbers);

        $formula .= ') / (';

        $numbers = array();
        for ($i = self::NUMBER_COUNT ; $i > 0 ; $i--)
            $numbers[] = $i;
        $formula .= implode(' x ', $numbers);

        $formula .= ') ] x [ (';

        $numbers = array();
        for ($i = self::STAR_MAX ; $i > self::STAR_MAX - self::STAR_COUNT ; $i--)
            $numbers[] = $i;
        $formula .= implode(' x ', $numbers);

        $formula .= ') / (';

        $numbers = array();
        for ($i = self::STAR_COUNT ; $i > 0 ; $i--)
            $numbers[] = $i;
        $formula .= implode(' x ', $numbers);

        $formula .= ') ]';

        return $formula;
    }
}