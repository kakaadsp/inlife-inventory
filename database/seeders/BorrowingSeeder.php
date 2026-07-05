<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $staff   = User::where('email', 'staff@inlife.co.id')->first();
        $staff2  = User::where('email', 'staff2@inlife.co.id')->first();
        $manager = User::where('email', 'manager@inlife.co.id')->first();

        $laptop    = Item::where('code', 'INV-ELK-0001')->first();
        $monitor   = Item::where('code', 'INV-ELK-0002')->first();
        $proyektor = Item::where('code', 'INV-ELK-0004')->first();
        $webcam    = Item::where('code', 'INV-ELK-0008')->first();
        $kursi     = Item::where('code', 'INV-FRN-0001')->first();
        $meja      = Item::where('code', 'INV-FRN-0002')->first();
        $headset   = Item::where('code', 'INV-KMN-0002')->first();
        $kamera    = Item::where('code', 'INV-LIN-0001')->first();

        $borrowings = [
            // ─── Active borrowings ─────────────────────────────────────────────────
            [
                'header' => [
                    'created_by'           => $staff->id,
                    'borrowing_code'       => 'BRW-20260610-0001',
                    'borrower_name'        => 'Rizki Pratama',
                    'borrower_department'  => 'Divisi Marketing',
                    'borrower_phone'       => '081234500001',
                    'borrow_date'          => '2026-06-10',
                    'expected_return_date' => '2026-07-10',
                    'status'               => 'borrowed',
                ],
                'details' => [
                    ['item_id' => $laptop->id,  'quantity' => 1, 'condition_before' => 'good'],
                    ['item_id' => $monitor->id, 'quantity' => 1, 'condition_before' => 'good'],
                ],
            ],
            [
                'header' => [
                    'created_by'           => $staff2->id,
                    'borrowing_code'       => 'BRW-20260615-0001',
                    'borrower_name'        => 'Siti Rahayu',
                    'borrower_department'  => 'Divisi HR',
                    'borrower_phone'       => '082345600001',
                    'borrow_date'          => '2026-06-15',
                    'expected_return_date' => '2026-07-15',
                    'status'               => 'borrowed',
                ],
                'details' => [
                    ['item_id' => $webcam->id, 'quantity' => 1, 'condition_before' => 'good'],
                    ['item_id' => $headset->id, 'quantity' => 2, 'condition_before' => 'good'],
                ],
            ],
            [
                'header' => [
                    'created_by'           => $staff->id,
                    'borrowing_code'       => 'BRW-20260618-0001',
                    'borrower_name'        => 'Ahmad Fauzi',
                    'borrower_department'  => 'Divisi IT',
                    'borrower_phone'       => '083456700001',
                    'borrow_date'          => '2026-06-18',
                    'expected_return_date' => '2026-07-05',
                    'status'               => 'overdue',    // OVERDUE!
                ],
                'details' => [
                    ['item_id' => $proyektor->id, 'quantity' => 1, 'condition_before' => 'fair'],
                ],
            ],

            // ─── Completed borrowings ──────────────────────────────────────────────
            [
                'header' => [
                    'created_by'           => $staff->id,
                    'returned_by'          => $staff->id,
                    'borrowing_code'       => 'BRW-20260520-0001',
                    'borrower_name'        => 'Dewi Lestari',
                    'borrower_department'  => 'Divisi Finance',
                    'borrower_phone'       => '084567800001',
                    'borrow_date'          => '2026-05-20',
                    'expected_return_date' => '2026-06-01',
                    'actual_return_date'   => '2026-06-01',
                    'status'               => 'returned',
                ],
                'details' => [
                    ['item_id' => $kamera->id, 'quantity' => 1, 'condition_before' => 'good', 'condition_after' => 'good'],
                ],
            ],
            [
                'header' => [
                    'created_by'           => $staff2->id,
                    'returned_by'          => $staff2->id,
                    'borrowing_code'       => 'BRW-20260525-0001',
                    'borrower_name'        => 'Hendra Kusuma',
                    'borrower_department'  => 'Divisi Operations',
                    'borrower_phone'       => '085678900001',
                    'borrow_date'          => '2026-05-25',
                    'expected_return_date' => '2026-06-05',
                    'actual_return_date'   => '2026-06-03',
                    'status'               => 'returned',
                ],
                'details' => [
                    ['item_id' => $kursi->id, 'quantity' => 2, 'condition_before' => 'good', 'condition_after' => 'good'],
                    ['item_id' => $meja->id,  'quantity' => 1, 'condition_before' => 'good', 'condition_after' => 'good'],
                ],
            ],
        ];

        foreach ($borrowings as $data) {
            $borrowing = Borrowing::updateOrCreate(
                ['borrowing_code' => $data['header']['borrowing_code']],
                $data['header']
            );

            foreach ($data['details'] as $detail) {
                BorrowingDetail::updateOrCreate(
                    ['borrowing_id' => $borrowing->id, 'item_id' => $detail['item_id']],
                    array_merge($detail, ['borrowing_id' => $borrowing->id])
                );
            }
        }

        $this->command->info('✅ Borrowings seeded (3 active + 2 returned, 1 overdue included).');
    }
}
