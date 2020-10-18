<?php


namespace App\Helpers;


class Helper
{

    public function compare($a,$b)
    {
        return $a['level'] < $b['level'] ? 1 : -1;
    }
}