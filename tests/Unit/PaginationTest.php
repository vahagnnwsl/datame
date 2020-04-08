<?php

namespace Tests\Unit;


use Tests\TestCase;

class PaginationTest extends TestCase
{

    public function testCreate()
    {

        $pages = [];

        //текущая страница
        $currentPage = 12;

        //количество страниц
        $quantityPage = 15;

        //количество ссылок которые нужно показывать
        $quantityShowPages = 9;

        if($quantityPage != 1) {

            //если количество ссылок которые нужно показывать больше чем  всего страниц
            if($quantityShowPages > $quantityPage)
                $quantityShowPages = $quantityPage;

            //если текущая страница меньше чем половина ссылок которые нужно показывать
            if($currentPage <= ($quantityShowPages / 2)) {
                for($i = 1; $i <= $quantityShowPages; $i++) {
                    array_push($pages, $i);
                }
                if($quantityShowPages != $quantityPage && $currentPage != $quantityPage) {
                    array_push($pages, "...");
                    array_push($pages, $quantityPage);
                }

            } //если текущая страница находится на растоянии половины ссылок которые нужно показать от конца списка
            else if(($currentPage + $quantityShowPages / 2) >= $quantityPage) {

                for($i = $quantityPage; $i > $quantityPage - $quantityShowPages; $i--) {
                    array_unshift($pages, $i);
                }

                if($currentPage > $quantityShowPages && $quantityShowPages != $quantityPage) {
                    array_unshift($pages, "...");
                    array_unshift($pages, 1);
                }

            } else {

                array_push($pages, 1);
                array_push($pages, "...");

                $left = (int)round($quantityShowPages / 2) - 1;

                for($i = $currentPage - $left; $i < $currentPage; $i++) {
                    if($i != 1)
                        array_push($pages, $i);
                }
                for($i = $currentPage; $i < $currentPage + $left; $i++) {
                    if($i < $quantityPage)
                        array_push($pages, $i);
                }

                if($quantityShowPages != $quantityPage && $currentPage != $quantityPage) {
                    array_push($pages, "...");
                    array_push($pages, $quantityPage);

                }

            }
        }

        dd($pages);
    }
}