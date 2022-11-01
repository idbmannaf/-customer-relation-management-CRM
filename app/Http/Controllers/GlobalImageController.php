<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Employee\EmployeeTrait;
use App\Models\Attendance;
use App\Models\CustomerOffer;
use App\Models\Employee;
use App\Models\GlobalImage;
use App\Models\Holiday;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Mail;
use Storage;

class GlobalImageController extends Controller
{
    use EmployeeTrait;
    public function store(Request $request)
    {

        $type = $request->type;
        $fiels = $request->file;
        $attachments = [];
        foreach ($fiels as $file) {

            $extension = $file->getClientOriginalExtension();
            // if ($extension == 'PNG' || $extension == 'JPEG' || $extension == 'GIF' || $extension == 'TIFF' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'tiff' || $extension == 'PDF' || $extension == 'pdf' || $extension == 'docx' || $extension == 'csv' || $extension == 'xlsx' ||  $extension == 'DOCX' || $extension == 'CSV' || $extension == 'XLSX' || $extension == 'XLS') {

            // }
            $randomFileName = time() . rand(11111111, 9999999) . "." . $extension;
            Storage::disk('public')->put('media/' . $randomFileName, File::get($file));

            if ($type == 'visit') {
                $global_file = new GlobalImage;
                $global_file->visit_plan_id = $request->visit_plan_id;
                $global_file->name = $randomFileName;
                $global_file->addedBy_id = Auth::id();
                $global_file->type = $request->type;
                if ($request->visit) {
                    $visit = Visit::find($request->visit);
                    $global_file->visit_id = $visit->id;
                }
                $global_file->save();
            } else {
                $global_file = new GlobalImage;
                $global_file->offer_id = $request->offer_id;
                $global_file->name = $randomFileName;
                $global_file->addedBy_id = Auth::id();
                $global_file->type = $request->type;
                $global_file->save();
            }
            $attachments[$global_file->id] = $global_file->name;
        }
        // dd($attachments);
        return response()->json([
            'success' => true,
            'html' => view('global.attachment', compact('attachments'))->render()
        ]);
    }
    public function delete(Request $request, GlobalImage $file)
    {
        $f = 'media/' . $file->name;
        if (Storage::disk('public')->exists($f)) {
            Storage::disk('public')->delete($f);
        }
        if ($file->delete()) {
            return response()->json([
                'success' => true,
            ]);
        }
    }
    public function myEmployees(Request $request)
    {
        $currentEmployee = Auth::user()->employee;

        if ($currentEmployee->team_admin) {
            $myEmployees = $currentEmployee->myTeamMembers()->pluck('id');
            $employee_ids = Employee::whereIn('id', $myEmployees)->orWhere('id', $currentEmployee->id)->where(function ($q) use ($request) {
                $q->orWhere('id', "like", "%" . $request->q . "%");
                $q->orWhere('name', "like", "%" . $request->q . "%");
            })->get();
        } else {
            $employee_ids = Employee::where('id', $currentEmployee->id)->where(function ($q) use ($request) {
                $q->orWhere('id', "like", "%" . $request->q . "%");
                $q->orWhere('name', "like", "%" . $request->q . "%");
            })->get();
        }

        return $employee_ids;
    }
    public function attandanceTest()
    {
        //If Friday Then No Attandance
        $friday = (date('l', strtotime(now())) == 'Friday');

        //Leave Day Of This Year

        $holiday_in_this_year = Holiday::where('year', date("Y"))->pluck('date')->toArray();
        $today = date('Y-m-d');
        foreach ($holiday_in_this_year as $holiday) {
            if ($holiday == $today || date('l', strtotime(now())) == 'Friday') {
            } else {
                $employee_users = Employee::whereHas('user', function ($q) {
                    $q->where('office_location_id', '!=', null);
                    $q->whereDoesntHave('TTAttendance');
                })->take(10)->get();

                foreach ($employee_users as $key => $em) {
                    $attendance = Attendance::where('user_id', $em->user_id)->whereDate('date', Carbon::now()->format('Y-m-d'))->first();

                    if (!$attendance) {
                        if ($em->user->officeLocation) {
                            $attendance = new Attendance;
                            $attendance->date = Carbon::now()->format('Y-m-d');
                            $attendance->company_id = $em->company_id;
                            $attendance->user_id = $em->user_id;
                            $attendance->office_location_id = $em->user->office_location_id;
                            $attendance->status = 'absent';
                            $attendance->save();
                        }
                    }
                }
            }
        }
    }
    public function sendMail(Request $request)
    {
        $details = [

            'title' => 'Mail from ItSolutionStuff.com',

            'body' => 'This is for testing email using smtp'

        ];


        $offer = CustomerOffer::latest()->first();
        Mail::send('mail.quatation', ['offer'=>$offer], function ($message) {
            $message->to('idbmannaf@gmail.com', url('/'))
                ->subject('Offer Quotation' . url('/'));
            });

        // Mail::to('saifislamfci@gmail.com')->send(new \App\Mail\QuatationSend($offer));

    }
    public function test()
    {
        $offer = CustomerOffer::latest()->first();
        return view('test', compact('offer'));
    }
}
