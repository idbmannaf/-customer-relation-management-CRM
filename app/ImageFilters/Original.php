<?php

namespace App\ImageFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Original implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        // return $image->fit(406, 150);
        return $image->fit(null, null); // 600 /400
    }
}
