<?php

namespace app\utils;

class DateUtils
{
    /** Format string date (dd/mm/yyyy or yyyy-mm-dd) to dateTime Object
     * @param $dateString string
     * @return \DateTime
     */
    static function createDateTime($dateString){
        $date = \DateTime::createFromFormat('d/m/Y',$dateString);
        if(!$date){
            $date= \DateTime::createFromFormat('Y-m-d',$dateString);
        }
        return $date;
    }

    /** Return good format for PDO
     * @param $dateTime \DateTime
     * @return string
     */
    static function getDateForPDO($dateTime){
        return $dateTime->format('Y-m-d');
    }

    /** Return good format for French people
     * @param $dateTime \DateTime
     * @return string
     */
    static function getFrenchFormat($dateTime){
        return $dateTime->format('d/m/Y');
    }

}