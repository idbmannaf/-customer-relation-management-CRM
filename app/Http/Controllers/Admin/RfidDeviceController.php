<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeLocation;
use App\Models\RfidDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RfidDeviceController extends Controller
{
    public function rfidDevices(Request $request, OfficeLocation $location)
    {
        $rfid_devices = RfidDevice::where('company_office_location_id',$location->id)->paginate(50);
        return view('admin.officeLocation.rfid_devices.rfid_devices',compact('location','rfid_devices'));
    }
    public function rfidDeviceAdd(Request $request, OfficeLocation $location)
    {
        $request->validate([
            'device_name'=>'required|unique:rfid_devices,device_name'
        ]);
        $rfid_d = new RfidDevice;
        $rfid_d->device_name = $request->device_name;
        $rfid_d->company_office_location_id = $location->id;
        $rfid_d->active = $request->active ? 1 : 0;
        $rfid_d->addedBy_id = Auth::id();
        $rfid_d->save();
        return redirect()->back()->with('success','RFID Device Added Successfully');
    }
    public function rfidDeviceUpdate(Request $request, OfficeLocation $location,RfidDevice $rfid)
    {
        $request->validate([
            'device_name'=>'required|unique:rfid_devices,device_name,'.$rfid->id,
        ]);
        $rfid->device_name = $request->device_name;
        $rfid->company_office_location_id = $location->id;
        $rfid->active = $request->active ? 1 : 0;
        $rfid->editedBy_id = Auth::id();
        $rfid->save();
        return redirect()->back()->with('success','RFID Device Updated Successfully');
    }
    public function rfidDeviceDelete(Request $request, OfficeLocation $location,RfidDevice $rfid)
    {
        $rfid->delete();
        return redirect()->back()->with('success','RFID Device Deleted Successfully');
    }
}
