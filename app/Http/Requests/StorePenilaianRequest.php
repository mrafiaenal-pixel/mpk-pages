<?php

namespace App\Http\Requests;

use App\Models\DivisionMember;
use App\Services\PenilaiSkor;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenilaianRequest extends FormRequest
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
            'id_anggota_sekbid' => [
                'required',
                Rule::exists('tb_anggota_sekbid', 'id'),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $anggota = DivisionMember::with('division')->find($value);

                    if ($anggota !== null && ! $this->user()->canAccessDivision($anggota->id_sekbid)) {
                        $fail('Anggota sekbid ini berada di luar divisi yang Anda tangani.');
                    }
                },
            ],
            'keaktifan_gds' => ['required', Rule::in(array_keys(PenilaiSkor::RUBRIK['keaktifan_gds']))],
            'piket_ro' => ['required', Rule::in(array_keys(PenilaiSkor::RUBRIK['piket_ro']))],
            'kekompakan' => ['required', Rule::in(array_keys(PenilaiSkor::RUBRIK['kekompakan']))],
            'komunikasi' => ['required', Rule::in(array_keys(PenilaiSkor::RUBRIK['komunikasi']))],
            'kehadiran_rapat' => ['required', Rule::in(array_keys(PenilaiSkor::RUBRIK['kehadiran_rapat']))],
            'kegiatan_progja' => ['required', Rule::in(array_keys(PenilaiSkor::RUBRIK['kegiatan_progja']))],
            'inisiatif_bantuan' => ['nullable', 'boolean'],
            'catatan_terhadap_anggota' => ['nullable', 'string', 'max:1000'],
            'tanggal_penilaian' => ['required', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id_anggota_sekbid.required' => 'Anggota sekbid yang dinilai wajib dipilih.',
            'id_anggota_sekbid.exists' => 'Anggota sekbid yang dipilih tidak ditemukan.',
        ];
    }
}
