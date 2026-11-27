<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiteSettingsRequest extends FormRequest
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
            'singkatan' => ['required', 'string', 'max:20'],
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'nama_sekolah' => ['required', 'string', 'max:150'],
            'email_sekolah' => ['required', 'email', 'max:150'],
            'descripsi_profile' => ['nullable', 'string', 'max:1000'],
            'visi' => ['required', 'string', 'max:2000'],
            'misi' => ['required', 'string', 'max:5000'],
            'maksimal_calon' => ['required', 'integer', 'min:1', 'max:50'],
            'komisi' => ['required', 'array', 'size:3'],
            'komisi.*.kode' => ['required', 'string', 'max:1', 'distinct', Rule::in(['A', 'B', 'C'])],
            'komisi.*.nama' => ['required', 'string', 'max:60'],
            'komisi.*.deskripsi' => ['required', 'string', 'max:150'],
            'pengurus' => ['required', 'array', 'min:1', 'max:30'],
            'pengurus.*.id' => ['nullable', 'integer', 'distinct', Rule::exists('tb_profil_kepengurusan', 'id')],
            'pengurus.*.nama_pengurus' => ['required', 'string', 'max:100'],
            'pengurus.*.jabatan_pengurus' => ['required', 'string', 'max:100'],
            'pengurus.*.komisi_pengurus' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'komisi.size' => 'Harus ada tepat 3 komisi (A, B, C).',
            'komisi.*.kode.in' => 'Kode komisi hanya boleh A, B, atau C.',
            'komisi.*.kode.distinct' => 'Kode komisi tidak boleh sama.',
            'pengurus.min' => 'Minimal satu pengurus harus diisi.',
        ];
    }
}
