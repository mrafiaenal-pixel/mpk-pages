<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCalonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'ketua', 'komc']) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_calon' => ['required', 'string', 'max:100'],
            'nomor_urut' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                Rule::unique('tb_calon_pemilu', 'nomor_urut')->ignore($this->route('calon')?->getKey()),
            ],
            'slogan' => ['nullable', 'string', 'max:120'],
            'visi_misi' => ['required', 'string', 'max:2000'],
            'foto' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
