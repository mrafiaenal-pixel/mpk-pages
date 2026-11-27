<?php

namespace App\Http\Requests;

use App\Models\CalonPemilu;
use App\Services\SiteSettings;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreCalonRequest extends FormRequest
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
            'nomor_urut' => ['required', 'integer', 'min:1', 'max:99', 'unique:tb_calon_pemilu,nomor_urut'],
            'slogan' => ['nullable', 'string', 'max:120'],
            'visi_misi' => ['required', 'string', 'max:2000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return list<string>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $maksimal = SiteSettings::resolve()->maksimalCalon;
                if (CalonPemilu::count() >= $maksimal) {
                    $validator->errors()->add('nama_calon', "Kuota calon sudah penuh ({$maksimal} calon). Tambah kuota di pengaturan.");
                }
            },
        ];
    }
}
