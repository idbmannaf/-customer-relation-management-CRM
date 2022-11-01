<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Requisition;
use App\Models\RequisitionProduct;
use App\Models\StockHistory;
use Auth;
use Illuminate\Http\Request;

class InventoryMaintainController extends Controller
{
    public function inventoryMaintain(Request $request)
    {
        $type = $request->type;
        menuSubmenu('inventoryMaintain', "INV_".$type);
        $requisitions = Requisition::where('status', 'approved')
            ->where('status', '!=', 'temp')
            ->where('type', $type)
            ->latest()
            ->paginate(100);
        return view('admin.inventory.index', compact('type', 'requisitions'));
    }
    public function inventoryMaintainDetails(Request $request)
    {

        $type = $request->type;
        $requisition = Requisition::find($request->requisition);
        return view('admin.inventory.details', compact('type', 'requisition'));
    }
    public function inventoryMaintainUpdate(Request $request)
    {
        $requisition = Requisition::find($request->requisition);
        $req_product = RequisitionProduct::find($request->item);
        $product = Product::find($req_product->product_id);

        if (!$req_product || !$product) {
            return redirect()->back()->with('warning', "Product Not Found");
        }
        if ($req_product->stock_out) {
            return redirect()->back()->with('warning', "This Product ");
        }

        if ($product->stock < $req_product->quantity) {
            return redirect()->back()->with('warning', "This Product ({$req_product->product_name}) Doesn't Have Enough Stock. Current Stock:{$product->stock}");
        }

        $prev_stock = $product->stock;

        $product->stock = $product->stock - $req_product->quantity;
        $product->save();

        $req_product->stock_out = true;
        $req_product->save();

        //History Create Start;
        $stock_history = new StockHistory;
        $stock_history->product_id = $product->id;
        $stock_history->product_name = $req_product->product_name;
        $stock_history->customer_id = $requisition->visit ? $requisition->visit->customer_id : null;
        $stock_history->previews_stock = $prev_stock;
        $stock_history->moved_stock = $req_product->quantity;
        $stock_history->current_stock = $product->stock;
        $stock_history->visit_plan_id = $requisition->visit->visit_plan_id;
        $stock_history->visit_id = $requisition->visit_id;
        $stock_history->requisition_id = $requisition->id;
        $stock_history->purpose = "Decrease";
        $stock_history->requisition_product_item_id = $req_product->id;
        $stock_history->addedBy_id = Auth::id();
        $stock_history->save();

        $visit_plan_id = $requisition->visit->visit_plan_id;
        $stock_history->note = "User {$stock_history->addedBy_id}  Maintained This Product. This Product ({$product->id}) cames from Requisition ({$requisition->id}, Visit({$requisition->visit_id}) and  Visit Plan ({$visit_plan_id}) for Customer {$requisition->customer_id}";
        $stock_history->save();
        //History Create Start;

        return redirect()->back()->with('success', 'Quantity Decresed form Product Stock');
    }
    public function returnMaintainUpdate(Request $request)
    {
        $req_product = RequisitionProduct::find($request->item);
        $req_product->return_old_product = !$req_product->return_old_product;
        $req_product->save();
    }
    public function sendToReceived(Request $request)
    {
        $requisition = Requisition::find($request->requisition);
        $requisition->send_to_receive_by = Auth::id();
        $requisition->send_to_receive_at = now();
        $requisition->save();
        return redirect()->back()->with('success', 'Product sent for recevied');
    }

}
