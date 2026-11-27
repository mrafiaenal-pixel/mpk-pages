<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnggotaRequest extends FormRequest
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
            'nama_anggota' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tb_anggota_sekbid', 'nama_anggota')
                    ->where(fn ($query) => $query->where('id_sekbid', $this->integer('id_sekbid'))),
            ],
            'id_sekbid' => ['required', 'exists:tb_sekbid,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_anggota.unique' => 'Nama ini sudah terdaftar pada seksi bidang yang dipilih.',
        ];
    }
}
