<?php

namespace App\Http\Requests\section;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','unique:sections','min:3','max:255'],
            'description' => ['required','between:10,600'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'يرجي ادخال اسم القسم',
            'name.unique' => 'اسم القسم موجود بالفعل',
            'name.min' => 'اسم القسم لا يقل عن 3 احرف',
            'name.max' => 'اسم القسم لا يزيد عن 255 احرف',
            'description.required' => 'يرجي ادخال الوصف ',
            'description.between' => 'الوصف يجب الا يقل عن 10 احرف ولا يزيد عن 600 حرف',
        ];
    }
}
