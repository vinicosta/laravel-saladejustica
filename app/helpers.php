<?php

if (!function_exists('slug')) {
    function slug($z, $length = 0)
    {
        $str = preg_replace('/[áàãâä]/ui', 'a', $z);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        $str = preg_replace('/[^a-z0-9]/i', '_', $str);
        $str = preg_replace('/_+/', '_', $str);
        if($length){
            $str = substr($str, 0, $length);
        }
        return strtolower($str);
    }
}

if (!function_exists('typeName')) {
    function typeName($type_id)
    {
        switch ($type_id) {
            case 1:
                return 'comics';
                break;

            case 2:
                return 'books';
                break;

            case 3:
                return 'magazines';
                break;

        }
    }
}
