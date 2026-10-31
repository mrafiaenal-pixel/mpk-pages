<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBendaharaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->canEditPenilaian();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|integer|min:0',
            'tipe' => 'required|in:masuk,keluar',
            'tanggal' => 'required|date',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'keterangan.required' => 'Keterangan wajib diisi.',
            'nominal.required' => 'Nominal wajib diisi.',
            'nominal.integer' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal minimal 0.',
            'tipe.required' => 'Jenis transaksi wajib dipilih.',
            'tipe.in' => 'Jenis transaksi harus masuk atau keluar.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
        ];
    }
}
