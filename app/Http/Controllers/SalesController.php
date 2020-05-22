<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests\SalesRepFormRequest;
use App\Models\SalesRep;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, SalesRep $rep)
    {
        $name = isset($request->name) ? $request->name : null;
        $phone = isset($request->phone) ? $request->phone : null;
        $email = isset($request->email) ? $request->email : null;
        $status = isset($request->status) ? $request->status : 0;
        $order_by = isset($request->order_by) ? $request->order_by : 0;
        $reps = $rep->filterByName($name)
            ->filterByPhone($phone)
            ->filterByEmail($email)
            ->filterByStatus($status)
            ->withOrder($order_by)
            ->paginate(10);
        return view('sales.index', compact('reps', 'name', 'phone', 'email', 'status', 'order_by'));
    }

    public function create(SalesRep $rep)
    {
        return view('sales.create_or_edit', compact('rep'));
    }

    public function store(SalesRepFormRequest $request, SalesRep $rep)
    {
        $rep->fill($request->all());
        $rep->save();
        return redirect()->to(route('sales.index'))->with('success', 'Sales rep created successfully');
    }

    public function edit(SalesRep $rep)
    {
        return view('sales.create_or_edit', compact('rep'));
    }

    public function update(SalesRepFormRequest $request, SalesRep $rep)
    {
        $rep->update($request->all());
        return redirect()->to(route('sales.index'))->with('success', 'Sales rep updated successfully');
    }

    public function destroy(SalesRep $rep)
    {
        $rep->delete();
        return redirect()->to(route('sales.index'))->with('success', 'Sales rep deleted successfully');
    }

    public function restore($rep)
    {
        $salesRep = SalesRep::withTrashed()->findOrFail($rep);
        $salesRep->restore();
        return redirect()->to(route('sales.index'))->with('success', 'Sales rep restored successfully');
    }
}
