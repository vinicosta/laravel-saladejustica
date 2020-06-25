<?php

use Illuminate\Support\Facades\Storage;

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

if (!function_exists('typeId')) {
    function typeId($type_name)
    {
        switch ($type_name) {
            case 'comics':
                return 1;
                break;

            case 'books':
                return 2;
                break;

            case 'magazines':
                return 3;
                break;

        }
    }
}

if (!function_exists('termToSearch')) {
    function termToSearch($term)
    {
        return '%' . str_replace(' ', '%', trim($term)) . '%';
    }
}

if (!function_exists('periodicsDetails')) {
    function periodicsDetails($publisher, $issues_count, $date_publication, $fulldate = false)
    {
        setlocale(LC_ALL, 'pt_BR');
        $label = ' edição';

        if ($issues_count > 1){
            $label = ' edições';
        }

        $details = $publisher;

        if($publisher != '' and ($issues_count or $date_publication != '')){
            $details .= ' - ';
        }
        if($issues_count){
            $details .= $issues_count . $label;
        }
        if($date_publication != ''){
            if(!$fulldate){
                $details .= strftime('%b %Y', strtotime($date_publication));
            }
            else{
                $details .= strftime('%B de %Y', strtotime($date_publication));
            }
        }

        return $details;
    }
}

if (!function_exists('periodicsTitle')) {
    function periodicsTitle($name, $issue_number)
    {
        $title = $name;

        if($issue_number != ''){
            $title .= ' #' . $issue_number;
        }

        return $title;
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($image)
    {
        if($image != ''){
            Storage::delete('public/covers/' . $image);
        }
    }
}

if (!function_exists('listAuthors')) {
    function listAuthors($authors, $ids = false)
    {
        $count = count($authors);
        $i = 1;
        $result = '';

        foreach ($authors as $author) {
            if(!$ids){
                $result .= $author->name;
            }
            else{
                $result .= $author->id;
            }
            if($i < $count){
                $result .= ',';
                if(!$ids){
                    $result .= ' ';
                }
            }
            $i++;
        }

        return $result;
    }
}

if (!function_exists('showDateInterval')) {
    function showDateInterval($date_publication, $final_date)
    {
        $final_date = new DateTime(date('Y-m-d'));
        $date_publication = new DateTime($date_publication);

        $diff = $final_date->diff($date_publication);

        $str_diff = '';

        if($diff->y){
            $str_diff .= "$diff->y ano";
            if($diff->y > 1){
                $str_diff .= "s";
            }
            if($diff->m and $diff->d){
                $str_diff .= ",";
            }
            elseif($diff->m or $diff->d){
                $str_diff .= "e";
            }
        }

        if($diff->m){
            $str_diff .= " $diff->m ";
            if($diff->m > 1){
                $str_diff .= "meses";
            }
            else{
                $str_diff .= "mês";
            }
            if($diff->d){
                $str_diff .= " e";
            }
        }

        if($diff->d){
            $str_diff .= " $diff->d dia";
            if($diff->d > 1){
                $str_diff .= "s";
            }
        }

        return $str_diff;
    }
}

