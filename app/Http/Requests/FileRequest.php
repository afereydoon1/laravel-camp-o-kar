<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
        if (request()->method === 'post' || request()->method === 'POST') {
            return [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'file' => 'required|file',
                'image' => 'required|image',
                'price' => 'required_if:membership_id,',
                'membership_id' => 'required_if:price,',
                'selectedTags.*' => 'required',
            ];
        } else {
            return [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'file' => 'nullable|file',
                'image' => 'nullable|image',
                'price' => 'required_if:membership_id,',
                'membership_id' => 'required_if:price,',
                'selectedTags.*' => 'required',
            ];
        }
    }
}
