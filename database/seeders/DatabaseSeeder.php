<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Şubeler (En az 10 kayıt)
        $branchData = [
            ['name' => 'İstanbul Kadıköy Şubesi', 'address' => 'Bağdat Caddesi No: 142, Kadıköy / İstanbul', 'phone' => '+90 216 345 67 89'],
            ['name' => 'İstanbul Beşiktaş Şubesi', 'address' => 'Barbaros Bulvarı No: 88, Beşiktaş / İstanbul', 'phone' => '+90 212 258 11 22'],
            ['name' => 'İstanbul Havalimanı (İST) Şubesi', 'address' => 'Tayakadın Mah. Terminal Cad. No: 1, Arnavutköy / İstanbul', 'phone' => '+90 212 463 30 00'],
            ['name' => 'Sabiha Gökçen Havalimanı (SAW) Şubesi', 'address' => 'Sanayi Mah. Havaalanı Girişi, Pendik / İstanbul', 'phone' => '+90 216 588 88 88'],
            ['name' => 'Ankara Çankaya Şubesi', 'address' => 'Tunalı Hilmi Caddesi No: 54, Çankaya / Ankara', 'phone' => '+90 312 428 10 20'],
            ['name' => 'Ankara Esenboğa Havalimanı Şubesi', 'address' => 'Esenboğa Havalimanı İç Hatlar, Akyurt / Ankara', 'phone' => '+90 312 398 00 00'],
            ['name' => 'İzmir Alsancak Şubesi', 'address' => 'Atatürk Caddesi No: 210, Konak / İzmir', 'phone' => '+90 232 464 55 66'],
            ['name' => 'İzmir Adnan Menderes Havalimanı Şubesi', 'address' => 'Gaziemir Girişi Dış Hatlar, Gaziemir / İzmir', 'phone' => '+90 232 274 24 24'],
            ['name' => 'Antalya Muratpaşa Şubesi', 'address' => 'Lara Caddesi No: 95, Muratpaşa / Antalya', 'phone' => '+90 242 324 70 80'],
            ['name' => 'Bursa Nilüfer Şubesi', 'address' => 'Fatih Sultan Mehmet Bulvarı No: 73, Nilüfer / Bursa', 'phone' => '+90 224 452 30 40'],
        ];

        $branches = collect();
        foreach ($branchData as $data) {
            $branches->push(Branch::create($data));
        }

        // 2. Müşteriler (En az 10 gerçekçi kayıt)
        $customerData = [
            ['first_name' => 'Ahmet', 'last_name' => 'Yılmaz', 'phone' => '+90 532 111 22 33', 'email' => 'ahmet.yilmaz@example.com', 'driving_license_number' => 'TR90123456'],
            ['first_name' => 'Mehmet', 'last_name' => 'Kaya', 'phone' => '+90 533 222 33 44', 'email' => 'mehmet.kaya@example.com', 'driving_license_number' => 'TR81234567'],
            ['first_name' => 'Ayşe', 'last_name' => 'Demir', 'phone' => '+90 535 333 44 55', 'email' => 'ayse.demir@example.com', 'driving_license_number' => 'TR72345678'],
            ['first_name' => 'Fatma', 'last_name' => 'Çelik', 'phone' => '+90 542 444 55 66', 'email' => 'fatma.celik@example.com', 'driving_license_number' => 'TR63456789'],
            ['first_name' => 'Mustafa', 'last_name' => 'Şahin', 'phone' => '+90 555 555 66 77', 'email' => 'mustafa.sahin@example.com', 'driving_license_number' => 'TR54567890'],
            ['first_name' => 'Zeynep', 'last_name' => 'Yıldız', 'phone' => '+90 530 666 77 88', 'email' => 'zeynep.yildiz@example.com', 'driving_license_number' => 'TR45678901'],
            ['first_name' => 'Emre', 'last_name' => 'Öztürk', 'phone' => '+90 531 777 88 99', 'email' => 'emre.ozturk@example.com', 'driving_license_number' => 'TR36789012'],
            ['first_name' => 'Elif', 'last_name' => 'Aydın', 'phone' => '+90 534 888 99 00', 'email' => 'elif.aydin@example.com', 'driving_license_number' => 'TR27890123'],
            ['first_name' => 'Burak', 'last_name' => 'Özdemir', 'phone' => '+90 536 999 00 11', 'email' => 'burak.ozdemir@example.com', 'driving_license_number' => 'TR18901234'],
            ['first_name' => 'Selin', 'last_name' => 'Arslan', 'phone' => '+90 537 101 20 30', 'email' => 'selin.arslan@example.com', 'driving_license_number' => 'TR09012345'],
            ['first_name' => 'Can', 'last_name' => 'Koç', 'phone' => '+90 538 212 31 41', 'email' => 'can.koc@example.com', 'driving_license_number' => 'TR98123450'],
            ['first_name' => 'Gamze', 'last_name' => 'Kurt', 'phone' => '+90 539 323 42 52', 'email' => 'gamze.kurt@example.com', 'driving_license_number' => 'TR87234561'],
        ];

        $customers = collect();
        foreach ($customerData as $data) {
            $customers->push(Customer::create($data));
        }

        // 3. Araçlar (En az 10 gerçekçi kayıt)
        $vehicleData = [
            ['brand' => 'Renault', 'model' => 'Clio 1.0 TCe', 'plate_number' => '34 ABC 101', 'model_year' => 2023, 'daily_rate' => 1250.00, 'status' => 'available'],
            ['brand' => 'Fiat', 'model' => 'Egea Sedan 1.4 Fire', 'plate_number' => '34 DEF 202', 'model_year' => 2023, 'daily_rate' => 1100.00, 'status' => 'available'],
            ['brand' => 'Toyota', 'model' => 'Corolla 1.5 Vision', 'plate_number' => '34 GHK 303', 'model_year' => 2024, 'daily_rate' => 1750.00, 'status' => 'rented'],
            ['brand' => 'Volkswagen', 'model' => 'Golf 1.5 eTSI', 'plate_number' => '34 LMN 404', 'model_year' => 2023, 'daily_rate' => 2100.00, 'status' => 'available'],
            ['brand' => 'Volkswagen', 'model' => 'Passat 1.5 TSI Elegance', 'plate_number' => '06 ANK 505', 'model_year' => 2022, 'daily_rate' => 2600.00, 'status' => 'available'],
            ['brand' => 'Ford', 'model' => 'Focus Titanium 1.5 EcoBlue', 'plate_number' => '06 FOC 606', 'model_year' => 2023, 'daily_rate' => 1800.00, 'status' => 'maintenance'],
            ['brand' => 'Peugeot', 'model' => '3008 Allure 1.5 BlueHDi', 'plate_number' => '35 IZM 707', 'model_year' => 2024, 'daily_rate' => 2800.00, 'status' => 'available'],
            ['brand' => 'Hyundai', 'model' => 'i20 1.4 MPI Style', 'plate_number' => '35 HYU 808', 'model_year' => 2023, 'daily_rate' => 1150.00, 'status' => 'available'],
            ['brand' => 'BMW', 'model' => '320i Sedan M Sport', 'plate_number' => '07 ANT 909', 'model_year' => 2024, 'daily_rate' => 4200.00, 'status' => 'rented'],
            ['brand' => 'Mercedes-Benz', 'model' => 'C200 4MATIC AMG', 'plate_number' => '16 BUR 010', 'model_year' => 2024, 'daily_rate' => 4500.00, 'status' => 'available'],
            ['brand' => 'Audi', 'model' => 'A3 Sedan 35 TFSI', 'plate_number' => '34 AUD 111', 'model_year' => 2023, 'daily_rate' => 3200.00, 'status' => 'available'],
            ['brand' => 'Nissan', 'model' => 'Qashqai 1.3 DIG-T', 'plate_number' => '34 NIS 222', 'model_year' => 2023, 'daily_rate' => 2300.00, 'status' => 'available'],
        ];

        $vehicles = collect();
        foreach ($vehicleData as $idx => $data) {
            $data['branch_id'] = $branches[$idx % $branches->count()]->id;
            $vehicles->push(Vehicle::create($data));
        }

        // 4. Kiralamalar (En az 10 gerçekçi kayıt - çakışma olmadan)
        $today = Carbon::today();

        $rentals = [
            // Geçmiş tamamlanmış kiralamalar
            [
                'vehicle_id' => $vehicles[0]->id,
                'customer_id' => $customers[0]->id,
                'pickup_branch_id' => $branches[0]->id,
                'return_branch_id' => $branches[0]->id,
                'start_date' => $today->copy()->subDays(20)->format('Y-m-d'),
                'end_date' => $today->copy()->subDays(17)->format('Y-m-d'),
                'status' => 'completed',
                'notes' => 'Sorunsuz teslim alındı.',
            ],
            [
                'vehicle_id' => $vehicles[1]->id,
                'customer_id' => $customers[1]->id,
                'pickup_branch_id' => $branches[1]->id,
                'return_branch_id' => $branches[2]->id,
                'start_date' => $today->copy()->subDays(15)->format('Y-m-d'),
                'end_date' => $today->copy()->subDays(10)->format('Y-m-d'),
                'status' => 'completed',
                'notes' => 'Havalimanı teslimi yapıldı.',
            ],
            [
                'vehicle_id' => $vehicles[3]->id,
                'customer_id' => $customers[2]->id,
                'pickup_branch_id' => $branches[2]->id,
                'return_branch_id' => $branches[2]->id,
                'start_date' => $today->copy()->subDays(12)->format('Y-m-d'),
                'end_date' => $today->copy()->subDays(8)->format('Y-m-d'),
                'status' => 'completed',
                'notes' => 'Şehir içi kullanım.',
            ],
            [
                'vehicle_id' => $vehicles[4]->id,
                'customer_id' => $customers[3]->id,
                'pickup_branch_id' => $branches[4]->id,
                'return_branch_id' => $branches[5]->id,
                'start_date' => $today->copy()->subDays(10)->format('Y-m-d'),
                'end_date' => $today->copy()->subDays(5)->format('Y-m-d'),
                'status' => 'completed',
                'notes' => 'Protokol kiralama.',
            ],
            [
                'vehicle_id' => $vehicles[6]->id,
                'customer_id' => $customers[4]->id,
                'pickup_branch_id' => $branches[6]->id,
                'return_branch_id' => $branches[7]->id,
                'start_date' => $today->copy()->subDays(8)->format('Y-m-d'),
                'end_date' => $today->copy()->subDays(3)->format('Y-m-d'),
                'status' => 'completed',
                'notes' => 'İzmir tatil paketi.',
            ],
            // Aktif kiralamalar
            [
                'vehicle_id' => $vehicles[2]->id, // Toyota Corolla (status: rented)
                'customer_id' => $customers[5]->id,
                'pickup_branch_id' => $branches[0]->id,
                'return_branch_id' => $branches[0]->id,
                'start_date' => $today->copy()->subDays(2)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(3)->format('Y-m-d'),
                'status' => 'active',
                'notes' => 'Halen aktif kiralama süreci.',
            ],
            [
                'vehicle_id' => $vehicles[8]->id, // BMW 320i (status: rented)
                'customer_id' => $customers[6]->id,
                'pickup_branch_id' => $branches[8]->id,
                'return_branch_id' => $branches[8]->id,
                'start_date' => $today->copy()->subDays(1)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(4)->format('Y-m-d'),
                'status' => 'active',
                'notes' => 'VIP müşteri transfer kiralama.',
            ],
            // Gelecek aktif rezervasyonlar
            [
                'vehicle_id' => $vehicles[0]->id,
                'customer_id' => $customers[7]->id,
                'pickup_branch_id' => $branches[0]->id,
                'return_branch_id' => $branches[3]->id,
                'start_date' => $today->copy()->addDays(5)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(9)->format('Y-m-d'),
                'status' => 'active',
                'notes' => 'Hafta sonu gezisi için rezerve edildi.',
            ],
            [
                'vehicle_id' => $vehicles[1]->id,
                'customer_id' => $customers[8]->id,
                'pickup_branch_id' => $branches[1]->id,
                'return_branch_id' => $branches[1]->id,
                'start_date' => $today->copy()->addDays(7)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(11)->format('Y-m-d'),
                'status' => 'active',
                'notes' => 'İş seyahati.',
            ],
            [
                'vehicle_id' => $vehicles[9]->id,
                'customer_id' => $customers[9]->id,
                'pickup_branch_id' => $branches[9]->id,
                'return_branch_id' => $branches[9]->id,
                'start_date' => $today->copy()->addDays(3)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(6)->format('Y-m-d'),
                'status' => 'active',
                'notes' => 'Düğün organizasyonu.',
            ],
            // İptal edilen kiralama (çakışma kontrolü testleri için gerçekçi örnek)
            [
                'vehicle_id' => $vehicles[0]->id,
                'customer_id' => $customers[10]->id,
                'pickup_branch_id' => $branches[0]->id,
                'return_branch_id' => $branches[0]->id,
                'start_date' => $today->copy()->addDays(5)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(8)->format('Y-m-d'),
                'status' => 'cancelled',
                'notes' => 'Müşteri talebiyle iptal edildi.',
            ],
        ];

        foreach ($rentals as $rental) {
            $vehicle = Vehicle::find($rental['vehicle_id']);
            $start = Carbon::parse($rental['start_date']);
            $end = Carbon::parse($rental['end_date']);
            $days = max(1, $start->diffInDays($end));
            $totalPrice = $days * $vehicle->daily_rate;

            $rental['total_price'] = $totalPrice;
            Rental::create($rental);
        }
    }
}
