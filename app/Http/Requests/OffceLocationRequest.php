<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OffceLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'=>'required',
            'location'=>'required',
            'company'=>'required',
            'lat'=>'required',
            'lng'=>'required',
            'division'=>'nullable',
            'district'=>'nullable',
            'thana'=>'nullable',
            'office_start_time'=>'required',
            'office_end_time'=>'required',
            'featured_image'=>'nullable',
            'active'=>'nullable',
        ];
    }
}
