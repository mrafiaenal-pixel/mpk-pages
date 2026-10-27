<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAspirasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status_aspirasi' => ['required', Rule::in(['menunggu', 'proses', 'selesai', 'ditolak'])],
            'tanggapan_mpk' => ['nullable', 'string', 'max:2000'],
            'catatan_internal' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status_aspirasi.required' => 'Status aspirasi wajib dipilih.',
            'tanggapan_mpk.max' => 'Tanggapan MPK maksimal 2000 karakter.',
            'catatan_internal.max' => 'Catatan internal maksimal 2000 karakter.',
        ];
    }
}
