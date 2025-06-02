<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Booking\Models\Booking;
use Modules\Constant\Models\Constant;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings,WithStyles
{
    public array $columns;

    public array $dateRange;

    public function __construct($columns, $dateRange)
    {
        $this->columns = $columns;
        $this->dateRange = $dateRange;
    }

    public function headings(): array
    {
        $modifiedHeadings = [];

        foreach ($this->columns as $column) {
            // Capitalize each word and replace underscores with spaces
            $modifiedHeadings[] = ucwords(str_replace('_', ' ', $column));
        }

        return $modifiedHeadings;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Booking::query()->branch()->with('user', 'services', 'mainServices');
       
        if(auth()->user()->hasRole('admin')){
            $query->where('created_by',auth()->id());
        }
   
        $query->whereDate('bookings.start_date_time', '>=', $this->dateRange[0]);

        $query->whereDate('bookings.start_date_time', '<=', $this->dateRange[1]);
        // dd($query->get());
        $query = $query->get();


        $booking_status = Constant::getAllConstant()->where('type', 'BOOKING_STATUS');

        $newQuery = $query->map(function ($row) use ($booking_status) {
            $selectedData = [];

            foreach ($this->columns as $column) {
                switch ($column) {
                    case 'id':
                        $selectedData[$column] = $row->id;
                        break;

                    case 'date':
                        $selectedData[$column] = customDate($row->start_date_time);
                        break;

                    case 'customer':
                        $selectedData[$column] = $row->user->full_name ?? default_user_name();
                        break;

                    case 'employee':
                        $selectedData[$column] = $row->services->first()->employee?->full_name ?? '-';
                        break;

                    case 'service_amount':
                        $selectedData[$column] = \Currency::format($row->services->sum('service_price'));
                        break;  

                    case 'service_duration':
                        $selectedData[$column] = $row->services->sum('duration_min').' ' . __('messages.lbl_min');
                        break;

                    case 'services':
                        $selectedData[$column] = implode(', ', $row->services->pluck('service_name')->toArray());
                        break;

                    case 'status':
                        $selectedData[$column] = $booking_status->where('name', $row->status)->first()->value;
                        break;

                    case 'payment_status':
                        $paymentConstant = Constant::getAllConstant()->where('type', 'PAYMENT_STATUS')
                            ->where('value', optional($row->payment)->payment_status)
                            ->first();
                        $selectedData[$column] = $paymentConstant->name ?? 'N/A';
                        break;

                    case 'updated_at':
                        $diff = timeAgoInt($row->updated_at);

                        if ($diff < 25) {
                            $selectedData[$column] = timeAgo($row->updated_at);
                        } else {
                            $selectedData[$column] = customDate($row->updated_at);
                        }
                        break;

                    default:
                        $selectedData[$column] = $row[$column];
                        break;
                }
            }

            return $selectedData;
        });

        return $newQuery;
    }

    public function styles(Worksheet $sheet)
    {
        applyExcelStyles($sheet);
    }

}
