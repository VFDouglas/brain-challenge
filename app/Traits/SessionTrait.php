<?php

namespace App\Traits;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
trait SessionTrait
{
    public function validatePageWithSession($path)
    {
        if (!$path) {
            $path = request()->getPathInfo();
        }
        foreach (session('page_access') as $item) {
            if ($item->url == $path) {
                return $item;
            }
        }
        return false;
    }
}
