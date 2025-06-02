<?php

namespace App\Http\Controllers;

use App\Models\PlanTax;
use Illuminate\Http\Request;
use Modules\Subscriptions\Models\Plan;
use Yajra\DataTables\DataTables;

class PlanTaxController extends Controller
{
    public $module_title;
    public $module_name;
    public $module_icon;

    public function __construct()
    {
        // Page Title
        $this->module_title =  __('report.lbl_taxes');

        // module name
        $this->module_name = 'plan.tax';

        // module icon
        $this->module_icon = 'fa-solid fa-money-bill-trend-up';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => $this->module_icon,
            'module_name' => $this->module_name,
        ]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $module_title =  __('report.lbl_taxes');
        $module_action =__('report.lbl_taxes');

        return view('taxes.index', compact('module_action','module_title'));
    }

    public function index_data(Datatables $datatable)
    {
        $query = PlanTax::query();

        return $datatable->eloquent($query)
            ->filterColumn('plans', function ($query, $keyword) {
                // Adjust the subquery to use the correct table name 'plan'
                $query->whereRaw("
                    EXISTS (
                        SELECT 1
                        FROM plan
                        WHERE FIND_IN_SET(plan.id, plan_taxes.plan_ids)
                        AND LOWER(plan.name) LIKE ?
                    )
                ", ["%{$keyword}%"]);
            })
            ->editColumn('plans', function ($data) {
                // Use the custom `plans` method to fetch related plan names
                $plans = $data->plans();
                return $plans->isNotEmpty()
                    ? implode(',', $plans->pluck('name')->toArray())
                    : '-';
            })
            ->editColumn('status', function ($row) {
                $checked = '';
                if ($row->status) {
                    $checked = 'checked="checked"';
                }

                return '
                <div class="form-check form-switch ">
                    <input type="checkbox" data-url="'.route('backend.plan.tax.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
                </div>
               ';
            })
            ->editColumn('value', function ($data) {
                if ($data->type === 'Percentage') {
                    // Append the percentage symbol for percentage type
                    return $data->value . '%';
                } else {
                    // Prepend the currency symbol for fixed type
                     $value = \Currency::format($data->value ?? 0);

                    return $value;
                }
            })
            ->editColumn('type', function ($data) {
                return ucfirst($data->type);
            })
            ->rawColumns(['action', 'status'])
            ->orderColumns(['id'], '-:column $1')
            ->toJson();
    }



    public function create()
    {
        $module_action = __('messages.create') . ' ' . __('report.lbl_taxes');
        $module_title =  __('report.lbl_taxes');
        $plans = Plan::where('is_free_plan',0)->where('status',1)->get();

        return view('taxes.create', compact('module_action','plans','module_title'));
    }

    public function store(Request $request)
    {
        $tax = PlanTax::find($request->id);
        $tax = ($tax) ? $tax : new PlanTax;

        $tax->title = $request->title;
        $tax->value = $request->value;
        $tax->type = $request->type;
        $tax->status = $request->status ? 1 : 0;
        $tax->plan_ids = isset($request->plan_ids) ? implode(',',$request->plan_ids) : null;
        $tax->save();

        foreach($tax->plans() as $plan)
        {
            $plan->tax = $plan->calculateTotalTax();
            $plan->total_price = $plan->totalPrice();
            $plan->save();
        }

        return redirect()->route('backend.plan.tax.index')->with('success',($request->id) ? __('messages.tax_updated_successfully') : __('messages.tax_created_successfully'));
    }

    public function edit($id)
    {
        $module_action = __('messages.edit') . ' ' . __('report.lbl_taxes');
        $module_title =  __('report.lbl_taxes');
     
        $tax = PlanTax::find($id);
        $plans = Plan::where('is_free_plan',0)->where('status',1)->get();

        return view('taxes.create', compact('module_action','plans','tax','module_action','module_title'));
    }

    public function delete($id)
    {
        $tax = PlanTax::find($id);
        $tax->value = 0;
        $tax->save();
        foreach($tax->plans() as $plan)
        {
            $plan->tax = $plan->calculateTotalTax();
            $plan->total_price = $plan->totalPrice();
            $plan->save();
        }
        $tax->delete();

        
        $message = __('messages.delete_form', ['form' => __('report.lbl_taxes')]);

        return response()->json(['message' => $message, 'status' => true], 200);      }


    public function updateStatus(Request $request, PlanTax $id)
    {
        $id->update(['status' => $request->status]);

        foreach ($id->plans() as $plan) {
            $plan->tax = $plan->calculateTotalTax();
            $plan->total_price = $plan->totalPrice();
            $plan->save();
        }

        return response()->json(['status' => true, 'message' => __('branch.status_update')]);
    }
}
