<?php
/**
 * Author: Zahir Hayrullah
 * create date :  29/03/2020  03:00 PM
 * Last Modified Date: 29/03/2020  03:00 PM.
 */
if (!function_exists('get_class_name')) {
    function get_class_name($name)
    {
        if (strpos($name, 'App')) {
            return $name;
        }

        return '\\App\\'.$name;
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('getItemIfExists')) {
    /**
     * @param $collection
     * @param $item
     *
     * @return mixed|null
     */
    function getItemIfExists($collection, $item)
    {
        return $collection->$item ?? null;
    }
}
/*---------------------------------- </> --------------------------------*/
