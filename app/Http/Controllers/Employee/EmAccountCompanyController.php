<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CollectionHistory;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Visit;
use Illuminate\Http\Request;

class EmAccountCompanyController extends Controller
{
    use EmployeeTrait;
    public function billCollection(Request $request)
    {
        $type = $request->type;
        menuSubmenu('billCollection', 'billCollection_' . $type);
        if ($type == 'assign') {
            $prepear_collections_from_invoces = Invoice::with('assignedTo', 'assignedBy')->latest()->paginate(100);
            return view('employee.account.bill_collection', compact('prepear_collections_from_invoces'));
        }
    }
    public function assignToBillCollection(Request $request, Invoice $invoice)
    {
        $my_employees = Employee::whereIn('id', $this->alEmployees())->orderBy('name')->get();
        return view('employee.account.assign_an_employee_for_bill_collection', compact('invoice', 'my_employees'));
    }
    public function transectionHistory(Request $request)
    {
        if ($request->invoice) {
            $histories = CollectionHistory::where('invoice_id', $request->invoice)->latest()->paginate(100);
            return view('employee.customers.transectionHistory', compact('histories'));
        }
    }
    public function collectionList(Request $reqeust)
    {
        $type = $reqeust->type;
        menuSubmenu('collectionList',"collectionList_",$type);
        $collections = Visit::whereHas('visit_plan', function ($q) {
            $q->where('service_type', 'collection');
        })
            ->where('status', $type)
            ->latest()
            ->paginate(50);

            return view('employee.customers.collectionList', compact('collections'));
    }
}
