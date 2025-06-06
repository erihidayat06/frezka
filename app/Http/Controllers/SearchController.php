<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Constant\Models\Constant;
use Modules\Service\Models\Service;
use Modules\Booking\Models\Booking;
class SearchController extends Controller
{
    public function get_search_data(Request $request)
    {
        $is_multiple = isset($request->multiple) ? explode(',', $request->multiple) : null;
        if (isset($is_multiple) && count($is_multiple)) {
            $multiplItems = [];
            foreach ($is_multiple as $key => $value) {
                $multiplItems[$key] = $this->getData(collect($request[$value]));
            }

            return response()->json(['status' => 'true', 'results' => $multiplItems]);
        } else {
            return response()->json(['status' => 'true', 'results' => $this->getData($request->all())]);
        }
    }


    protected function getData($request)
    {
        $items = [];

        $type = $request['type'];
        $sub_type = $request['sub_type'] ?? null;

        $keyword = $request['q'] ?? null;

        switch ($type) {
            case 'employees':
                // Need To Add Role Base
                $items = User::role('employee')->select('id', \DB::raw("CONCAT(first_name,' ',last_name) AS text"));
                if (auth()->user()->hasRole('admin')) {
                    $items->where('created_by', auth()->user()->id);
                }
                if ($keyword != '') {
                    $items->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%'.$keyword.'%');
                }
              
        
                $items = $items->limit(50)->get();
                break;
            case 'customers':
                $items = User::role('user')->select('id', \DB::raw("CONCAT(first_name,' ',last_name) AS text"));

                if (auth()->user()->hasRole('admin')) {
                    $items->where('created_by', auth()->id());
                } elseif (auth()->user()->hasRole('employee') || auth()->user()->hasRole('manager')) {
                    $items->whereHas('employee', function ($q) {
                        $q->where('employee_id', auth()->id());
                    });
                }
                if ($keyword != '') {
                    $items->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%'.$keyword.'%');
                }
                $items = $items->limit(50)->get();
                break;
            case 'services':
                $items = Service::select('id', 'name as text');
                if ($keyword != '') {
                    $items->where('name', 'LIKE', '%'.$keyword.'%');
                }
                if (auth()->user()->hasRole('admin')) {
                    $items->where('created_by', auth()->id());
                } elseif (auth()->user()->hasRole('employee') || auth()->user()->hasRole('manager')) {
                    $items->whereHas('employee', function ($q) {
                        $q->where('employee_id', auth()->id());
                    });
                }
                $items = $items->limit(50)->get();
                break;
            case 'earning_payment_method':
                $query = Constant::getAllConstant()
                    ->where('type', 'EARNING_PAYMENT_TYPE');
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->name,
                                'text' => $data->value,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->name,
                            'text' => $data->value,
                        ];
                    }
                }
                break;

            case 'booking_status':
                $query = Constant::getAllConstant()
                    ->where('type', 'BOOKING_STATUS');
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->name,
                                'text' => $data->value,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->name,
                            'text' => $data->value,
                        ];
                    }
                }
                break;

            case 'time_zone':
                $items = timeZoneList();

            

                $data = [];
                $i = 0;
                foreach ($items as $key => $row) {
                    $data[$i] = [
                        'id' => $key,
                        'text' => $row,
                    ];

                    $i++;
                }

                $items = $data;

                break;

            case 'additional_permissions':
                $query = Constant::getAllConstant()
                    ->where('type', 'additional_permissions');
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->name,
                                'text' => $data->value,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->name,
                            'text' => $data->value,
                        ];
                    }
                }

                break;

            case 'constant':
                $query = Constant::getAllConstant()->where('type', $sub_type);
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->name,
                                'text' => $data->value,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->name,
                            'text' => $data->value,
                        ];
                    }
                }

                break;

            case 'role':
                $query = Role::all();
                foreach ($query as $key => $data) {
                    if ($keyword != '') {
                        if (strpos($data->name, str_replace(' ', '_', strtolower($keyword))) !== false) {
                            $items[] = [
                                'id' => $data->id,
                                'text' => $data->name,
                            ];
                        }
                    } else {
                        $items[] = [
                            'id' => $data->id,
                            'text' => $data->name,
                        ];
                    }
                }
                break;
            case 'booking_customers':
            $bookings = Booking::with('user')->get();

            // Map through bookings to get user data
            
            $customers = $bookings->map(function ($booking) {
                return User::where('id', $booking->user_id)
                    ->where('created_by', auth()->id())
                    ->first(); // Fetch single user
            })->filter()->unique('id'); 
      

            foreach ($customers as $key => $customer) {
         
                $items[] = [
                    'id' => $customer->id,
                    'text' => $customer->full_name,
                ];
            }

                break;
        }

        return $items;
    }
}
