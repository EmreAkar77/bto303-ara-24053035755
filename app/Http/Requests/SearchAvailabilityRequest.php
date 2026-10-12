<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchAvailabilityRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // İlk sayfa ziyaretinde (parametre yokken) hata fırlatmamak için
        if (! $this->query->has('start_date') && ! $this->query->has('end_date') && ! $this->query->has('branch_id')) {
            return [];
        }

        return [
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'branch_id' => ['nullable', 'exists:branches,id'],
        ];
    }

    /**
     * Custom validation messages in Turkish.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'start_date.required' => 'Lütfen alış tarihini seçiniz.',
            'start_date.date' => 'Geçerli bir alış tarihi giriniz.',
            'start_date.after_or_equal' => 'Alış tarihi geçmişte olamaz.',
            'end_date.required' => 'Lütfen teslim tarihini seçiniz.',
            'end_date.date' => 'Geçerli bir teslim tarihi giriniz.',
            'end_date.after_or_equal' => 'Teslim tarihi alış tarihinden önce olamaz.',
            'branch_id.exists' => 'Seçilen alış şubesi sistemde bulunamadı.',
        ];
    }
}
