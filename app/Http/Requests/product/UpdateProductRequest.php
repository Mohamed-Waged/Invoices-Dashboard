<?php

namespace App\Http\Requests\product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $id = request()->id;
        return [
            'name' => ['required','min:3','max:255','unique:products,name,'.$id],
            'description' => ['max:500'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'يرجي ادخال اسم المنتج',
            'name.unique' => 'اسم المنتج موجود بالفعل',
            'name.min' => 'اسم المنتج لا يقل عن 3 احرف',
            'name.max' => 'اسم المنتج لا يزيد عن 255 احرف',
            'description.max' => 'اسم المنتج لا يزيد عن 500 حرف',
        ];
    }
}
