<?php

namespace App\Http\Requests;

use App\Models\Scholarships;
use Illuminate\Foundation\Http\FormRequest;

class UpdateScholarshipsRequest extends FormRequest
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
        $rules = Scholarships::$rules;
        
        return $rules;
    }
}
