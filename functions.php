<?php
/**
 * Remove columns from an array
 * @param array $array
 * @param array $keys
 * @return array
 */
function array_columns_delete($array, $keys) {
    foreach ($array as $k1 => $row) {
        foreach ($row as $k2 =>$col) {
            if (in_array($k2,$keys)) unset($array[$k1][$k2]);
        }
    }
    return $array;
}