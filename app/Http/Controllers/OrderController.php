<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display the specified order.
     * The view should use $order->notes to show internal notes (only to logged-in/staff views).
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Update order attributes including internal notes.
     * Validation allows empty values (nullable) but will persist text when provided.
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'notes' => ['nullable', 'string'],
            // keep other inline validation rules for orders here if present
        ]);

        // Update only notes (and merge additional fields if required elsewhere)
        if (array_key_exists('notes', $data)) {
            $order->notes = $data['notes'];
            $order->save();
        }

        return redirect()->back()->with('success', 'Order updated.');
    }
}
