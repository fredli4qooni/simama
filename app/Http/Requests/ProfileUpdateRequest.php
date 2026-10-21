<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $isMahasiswa = $user && !in_array($user->role, ['admin', 'dpl', 'admin_fakultas']);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user?->id)],
            'nim' => ['nullable', 'string', 'max:50', Rule::unique(User::class)->ignore($user?->id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique(User::class)->ignore($user?->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($isMahasiswa) {
            $rules['total_sks'] = ['required', 'integer', 'min:110'];
            $rules['ipk'] = ['required', 'numeric', 'min:2.75', 'max:4.00'];
            $rules['semester'] = ['nullable', 'integer', 'min:1', 'max:14'];
        } else {
            $rules['total_sks'] = ['nullable'];
            $rules['ipk'] = ['nullable'];
            $rules['semester'] = ['nullable'];
        }

        return $rules;
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'total_sks.min' => 'Total SKS minimal adalah 110 SKS (setara semester 6 ke atas). Sistem tidak dapat memperbarui data mahasiswa jika SKS kurang dari 110.',
            'total_sks.required' => 'Total SKS wajib diisi.',
            'ipk.min' => 'Nilai IPK minimal adalah 2.75. Sistem tidak dapat memperbarui data mahasiswa jika IPK kurang dari 2.75.',
            'ipk.max' => 'Nilai IPK tidak boleh lebih dari 4.00.',
            'ipk.required' => 'Nilai IPK wajib diisi.',
            'ipk.numeric' => 'Format IPK harus berupa angka desimal.',
            'semester.required' => 'Semester saat ini wajib dipilih/diisi.',
            'semester.integer' => 'Semester harus berupa bilangan bulat.',
            'semester.min' => 'Semester tidak valid (minimal 1).',
            'semester.max' => 'Semester maksimal adalah 14.',
        ];
    }
}
