<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Tag;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('orders.index')->withOrders(Order::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('orders.create')
            ->withOrder(new Order)
            ->withCustomers(Customer::all())
            ->withTags(Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'tag_id' => 'nullable|array'
        ]);
        $validator->validate();

        $order = new Order();
        $order->title = $request->input('title');
        $order->description = $request->input('description');
        $order->cost = $request->input('cost');
        $order->customer_id = $request->input('customer_id');
        $order->save();
        $order->tags()->attach($request->input('tag_id'), ['created_at' => Carbon::now()]);
        
        $contract = new Contract();
        $contract->customer_id = $order->customer_id;
        $contract->order_id = $order->id;
        $contract->save();

        return redirect()->route('orders.edit', $order)->withMessage('Order created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'))
            ->withCustomers(Customer::all())
            ->withTags(Tag::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'tag_id' => 'nullable|array'
        ]);
        $validator->validate();

        $order->title = $request->input('title');
        $order->description = $request->input('description');
        $order->cost = $request->input('cost');
        $order->customer_id = $request->input('customer_id');
        $order->save();

        $tagsToDetach = $order->tags()->pluck('tag_id')->diff(collect($request->input('tag_id')));
        $tagsToAttach = collect($request->input('tag_id'))->diff($order->tags()->pluck('tag_id'));

        $order->tags()->detach($tagsToDetach->toArray());
        $order->tags()->attach($tagsToAttach->toArray(), ['created_at' => Carbon::now()]);

        return view('orders.edit', compact('order'))
            ->withCustomers(Customer::all())
            ->withTags(Tag::all())
            ->withMessage('Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        Contract::where('order_id', $order->id)->delete();
        $order->delete();

        return redirect()->route('orders.index')->withMessage('Order deleted successfully, click <a href="' . route('orders.restore', $order) . '">here</a> to restore');
    }

    public function restore($orderId)
    {
        Order::withTrashed()->find($orderId)->restore();
        Contract::withTrashed()->where('order_id', $orderId)->restore();

        return redirect()->route('orders.index')->withMessage('Order restored successfully');
    }
}
