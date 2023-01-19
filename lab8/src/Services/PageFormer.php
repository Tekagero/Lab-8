<?php

namespace App\Services;

use App\Entity\News;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;

class PageFormer
{
    static public function formPagination(int $page, ManagerRegistry $doctrine) : array
    {
        $count = $doctrine->getRepository(News::class)->getCount();

        $k = $page;
        $j = $page;
        while (($j - $k) < 10) {
            $end_while = true;
            if ($k > 1) {
                $k --;
                $end_while = false;
            }
            if (($j * 10) <= $count) {
                $j++;
                $end_while = false;
            }
            if ($end_while) {
                break;
            }
        }

        $pages = array();
        for ($i = $k; $i <= $j; $i++) {
            $pages[] = $i;
        }

        return $pages;

    }

}