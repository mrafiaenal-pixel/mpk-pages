<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkProgramRequest extends FormRequest
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
            'nama_progja' => ['required', 'string', 'max:200', Rule::unique('tb_program_kerja', 'nama_progja')->ignore($this->route('kegiatan'))],
            'deskripsi_progja' => ['nullable', 'string', 'max:2000'],
            'status_progja' => ['required', Rule::in(['rencana', 'berjalan', 'terlaksana', 'belum_terlaksana', 'sedang_berjalan', 'selesai', 'batal'])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_progja.required' => 'Nama kegiatan wajib diisi.',
            'nama_progja.unique' => 'Kegiatan dengan nama tersebut sudah tersedia.',
            'status_progja.required' => 'Status kegiatan wajib dipilih.',
        ];
    }
}
