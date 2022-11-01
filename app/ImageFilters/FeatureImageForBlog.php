<?php

namespace App\ImageFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class FeatureImageForBlog implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        // return $image->fit(810, 300);
        return  $image->fit(1200, 400);
        // return  $image->insert('img/w.png');
 
       // return $image->insert('img/w1.png', 'bottom');
    }
}