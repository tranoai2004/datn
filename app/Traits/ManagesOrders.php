<?php

namespace App\Traits;

use App\Models\Order;
use Illuminate\Http\Request;

trait ManagesOrders
{
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail(id: $id);

        $order->status = $request->input('status');

        // Nếu trạng thái được cập nhật là "shipped", cập nhật trạng thái thanh toán
        if ($order->status === 'shipped') {
            $order->payment_status = 'paid'; // Cập nhật trạng thái thanh toán
        } elseif ($order->status === 'canceled' || $order->status === 'refunded') {
            $order->payment_status = 'pending'; // Cập nhật trạng thái thanh toán thành chưa thanh toán
        }

        $order->save();

        return redirect()->route('orders.index')
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

}

