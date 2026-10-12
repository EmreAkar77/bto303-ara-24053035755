<?php

namespace App\Http\Requests;

use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRentalRequest extends FormRequest
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
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'pickup_branch_id' => ['required', 'exists:branches,id'],
            'return_branch_id' => ['required', 'exists:branches,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:active,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
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
            'vehicle_id.required' => 'Lütfen kiralanacak aracı seçiniz.',
            'vehicle_id.exists' => 'Seçilen araç sistemde bulunamadı.',
            'customer_id.required' => 'Lütfen müşteriyi seçiniz.',
            'customer_id.exists' => 'Seçilen müşteri sistemde bulunamadı.',
            'pickup_branch_id.required' => 'Alış şubesi seçimi zorunludur.',
            'pickup_branch_id.exists' => 'Seçilen alış şubesi bulunamadı.',
            'return_branch_id.required' => 'Teslim şubesi seçimi zorunludur.',
            'return_branch_id.exists' => 'Seçilen teslim şubesi bulunamadı.',
            'start_date.required' => 'Alış tarihi zorunludur.',
            'start_date.date' => 'Geçerli bir alış tarihi giriniz.',
            'end_date.required' => 'Teslim tarihi zorunludur.',
            'end_date.date' => 'Geçerli bir teslim tarihi giriniz.',
            'end_date.after_or_equal' => 'Teslim tarihi alış tarihinden önce olamaz.',
            'status.required' => 'Kiralama durumu seçilmelidir.',
            'status.in' => 'Geçersiz kiralama durumu.',
        ];
    }

    /**
     * Configure the validator instance with business rules.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled(['vehicle_id', 'start_date', 'end_date'])) {
                $rental = $this->route('rental');
                $rentalId = is_object($rental) ? $rental->id : (int) $rental;
                $vehicle = Vehicle::find($this->vehicle_id);

                // İş Kuralı 1: Bakımdaki araç seçilemez (araç değişmişse ve yeni seçilen araç bakımdaysa)
                if ($vehicle && $vehicle->status === 'maintenance' && $rental && $rental->vehicle_id != $vehicle->id) {
                    $validator->errors()->add('vehicle_id', 'Bakımda olan araçlar için yeni kiralama işlemi başlatılamaz.');
                }

                // İş Kuralı 2: Aktif kiralamalarda tarih çakışması kontrolü (mevcut kayıt hariç tutulur)
                if ($this->input('status') !== 'cancelled') {
                    if (Rental::hasDateConflict((int) $this->vehicle_id, $this->start_date, $this->end_date, $rentalId)) {
                        $validator->errors()->add('start_date', 'Bu araç seçilen tarih aralığında başka bir aktif kiralamaya tahsis edilmiştir.');
                    }
                }
            }
        });
    }
}
