<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerCompany;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements ToModel, WithStartRow
{

    public function startRow(): int
    {
        return 2;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
// dump($row[7] ?? '');
// die;
        if ($row[2]) {
            if ($row[4]) {
                $brand = ProductBrand::where('name', 'like', "" . $row[4] . "")->where('type','spare_parts')->first();
                if ($brand) {
                    $brand_id = $brand->id;
                } else {
                    $brand = new ProductBrand;
                    $brand->name = $row[4];
                    $brand->addedBy_id = Auth::id();
                    $brand->type ='spare_parts';
                    $brand->save();
                    $brand_id = $brand->id;
                }
            } else {
                $brand_id = null;
            }
            if ($row[1]) {
                $category = ProductCategory::where('name', 'like', "" . $row[1] . "")->where('type','spare_parts')->first();
                if ($category) {
                    $category_id = $category->id;
                } else {
                    $category = new ProductCategory;
                    $category->name = $row[1];
                    $category->addedBy_id = Auth::id();
                    $category->type ='spare_parts';
                    $category->save();

                    $category_id = $category->id;
                }
            } else {
                $category_id = null;
            }

            $product = new Product;
            $product->name = $row[2];
            $product->category_id = $category_id;
            $product->capacity = $row[3];
            $product->brand_id = $brand_id;

            $product->model = $row[5];
            $product->backup_time = $row[6];
            $product->type = $row[7];
            $product->short_description = $row[8];

            $product->origin = $row[9];
            $product->made_in = $row[10];
            $product->warranty = $row[11];
            $product->unit_price = $row[12] ?? 0.00;
            $product->product_type = 'spare_parts';
            $product->addedBy_id = Auth::id();
            $product->save();
            // die;
        }
    }
}
