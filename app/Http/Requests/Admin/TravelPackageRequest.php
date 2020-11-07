<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TravelPackageRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'about' => 'required|string',
            'featured_event' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'foods' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'duration' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|integer',
        ];
    }
}
