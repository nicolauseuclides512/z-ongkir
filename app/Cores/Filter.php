<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Cores;


class Filter
{
    /**
     * @param $filterBy
     * @param array $arrFilter
     * @return bool
     */
    public static function isAvailableFilterBy($filterBy, $arrFilter = [])
    {
        if (!empty($filterBy) && in_array($filterBy, $arrFilter)) {
            return true;
        }
        return false;
    }

    /**
     * @param $filterBy
     * @param array $arrFilter
     * @return string
     */
    public static function getFilter($filterBy, $arrFilter = [])
    {
        if (empty($filterBy)) {
            return key($arrFilter);
        }

        if (self::isAvailableFilterBy($filterBy, $arrFilter)) {
            return $filterBy;
        }

        return key($arrFilter);
    }
}