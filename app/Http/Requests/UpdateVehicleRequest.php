<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
        $vehicle = $this->route('vehicle');
        $vehicleId = is_object($vehicle) ? $vehicle->id : $vehicle;

        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles', 'plate_number')->ignore($vehicleId)],
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'model_year' => ['required', 'integer', 'min:1990', 'max:'.(date('Y') + 1)],
            'daily_rate' => ['required', 'numeric', 'min:1'],
            'status' => ['required', 'in:available,rented,maintenance'],
        ];
    }

    /**
     * Custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'branch_id.required' => 'Lütfen bağlı olduğu şubeyi seçiniz.',
            'branch_id.exists' => 'Seçilen şube sistemde bulunamadı.',
            'plate_number.required' => 'Plaka alanı zorunludur.',
            'plate_number.unique' => 'Bu plaka ile kayıtlı başka bir araç bulunmaktadır.',
            'brand.required' => 'Marka alanı zorunludur.',
            'model.required' => 'Model alanı zorunludur.',
            'model_year.required' => 'Model yılı zorunludur.',
            'daily_rate.required' => 'Günlük kiralama ücreti zorunludur.',
            'daily_rate.min' => 'Günlük ücret en az 1 TL olmalıdır.',
            'status.required' => 'Araç durumu zorunludur.',
            'status.in' => 'Geçersiz araç durumu seçildi.',
        ];
    }
}
