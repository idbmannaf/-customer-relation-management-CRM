<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerOfficeLocation;
use App\Http\Resources\CustomerOfficeLocationResource;
use App\Models\Customer;
use App\Models\OfficeLocation;
use App\Models\Product;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    public function addOrEditCustomer(Request $request)
    {
        $customer = Customer::find($request->customer);

        $office_location = OfficeLocation::where(function ($q) use ($customer) {
            $q->where('type', 'customer');
            $q->where('customer_company_id', $customer->company_id);
        })
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%');
                $q->orWhere('location_code', 'like', '%' . $request->q . '%');
                $q->orWhere('booth_id', 'like', '%' . $request->q . '%');
            })
            ->select('*')->take(30)->get();


        // dd($office_location);
        if ($office_location->count()) {
            if ($request->ajax()) {
                return CustomerOfficeLocationResource::collection($office_location);
            }
        } else {
            if ($request->ajax()) {
                return CustomerOfficeLocationResource::collection($office_location);
            }
        }
    }
    public function productAllAjax(Request $request)
    {
        if ($request->category) {

            $products = Product::where('category_id', $request->category)
                ->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%');
                    $q->orWhere('model', 'like', '%' . $request->q . '%');
                })
                ->select(['id', 'model', 'name'])->take(30)->get();
        } else {
            if ($request->type) {
                $products = Product::where('product_type', $request->type)->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%');
                    $q->orWhere('model', 'like', '%' . $request->q . '%');
                })
                    ->select(['id', 'model', 'name'])->take(30)->get();
            } else {
                $products = Product::where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('model', 'like', '%' . $request->q . '%')
                    ->select(['id', 'model', 'name'])->take(30)->get();
            }
        }


        if ($products->count()) {
            if ($request->ajax()) {
                return $products;
            }
        } else {
            if ($request->ajax()) {
                return $products;
            }
        }
    }
    public function productAllGlobalAjax(Request $request)
    {
        $users = Customer::where(function ($q) use ($request) {
            $q->where('customer_code', 'like', '%' . $request->q . '%');
            $q->orWhere('customer_name', 'like', '%' . $request->q . '%');
        })
            ->select(['id', 'customer_code', 'customer_name'])->take(30)->get();

        if ($users->count()) {
            if ($request->ajax()) {
                // return Response()->json(['items'=>$users]);
                return $users;
            }
        } else {
            if ($request->ajax()) {
                return $users;
            }
        }
    }

    public function getCustomerOffice(Request $request)
    {
        $customer = Customer::find($request->customer);
        $customer_company_offices = OfficeLocation::where('customer_company_id', $customer->company_id)->get();

        return response()->json([
            'success' => true,
            'html' => view('employee.visitPlan.ajax.customersCompanyOfficeOption', compact('customer_company_offices', 'customer'))->render()
        ]);
        if ($customer_company_offices->count()) {
            return response()->json([
                'success' => true,
                'html' => view('employee.visitPlan.ajax.customersCompanyOfficeOption', compact('customer_company_offices', 'customer'))->render()
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
}
