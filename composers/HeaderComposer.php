<?php
namespace Composers;

use DAO\CategoryDAO;
use DAO\BrandDAO;

class HeaderComposer
{
    public static function compose()
    {
        $categoryDAO = new CategoryDAO();
        $brandDAO = new BrandDAO();

        return [
            'categories' => $categoryDAO->getByLimit(5),
            'brands'     => $brandDAO->getByLimit(5)
        ];
    }
}
