<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Mail\SendOrderStatus;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function processOrder($id)
    {
        $customer = DB::table('customers as c')
            ->join('orders as o', 'o.customer_id', '=', 'c.id')
            ->join('statuses as s', 'o.status_id', '=', 's.id')
            ->where('o.id', $id)
            ->select('c.lname', 'c.fname', 'c.addressline', 'c.phone', 'o.id as order_id', 's.status', 'o.date_placed')
            ->first();

        $orders = DB::table('customers as c')
            ->join('orders as o', 'o.customer_id', '=', 'c.id')
            ->join('shippings as sh', 'o.shipping_id', '=', 'sh.id')
            ->join('order_item as io', 'o.id', '=', 'io.order_id')
            ->join('items as i', 'io.item_id', '=', 'i.id')
            ->leftJoin('item_images as img', 'i.id', '=', 'img.item_id')
            ->where('o.id', $id)
            ->select(
                'i.id',
                'i.item_name',
                'img.image_path',
                'i.description',
                'io.quantity',
                'i.sell_price',
                'sh.region as shipping_region',
                'sh.rate as shipping_rate'
            )
            ->groupBy(
                'i.id',
                'i.item_name',
                'img.image_path',
                'i.description',
                'io.quantity',
                'i.sell_price',
                'sh.region',
                'sh.rate'
            )
            ->get();

        $orderItems = DB::table('order_item as oi')
        ->join('items as i', 'oi.item_id', '=', 'i.id')
        ->where('oi.order_id', $id)
        ->select(
            'i.item_name',
            'oi.quantity',
            'i.sell_price',
            DB::raw('(oi.quantity * i.sell_price) as subtotal')
        )
        ->get();

        $images = DB::table('item_images')->get()->groupBy('item_id');

        $status = DB::table('statuses')
            ->where('id', $id)
            ->value('status');

        $enumColumn = DB::select("SHOW COLUMNS FROM statuses WHERE Field = 'status'");
        preg_match('/^enum\((.*)\)$/', $enumColumn[0]->Type, $matches);
        $statusChoices = [];
        if (isset($matches[1])) {
            foreach (explode(',', $matches[1]) as $value) {
                $statusChoices[] = trim($value, "'");
            }
        }

        $shippingRegion = $orders->isNotEmpty() ? $orders[0]->shipping_region : 'N/A';
        $shippingRate = $orders->isNotEmpty() ? $orders[0]->shipping_rate : 0;

        return view('order.processOrder', compact(
            'customer',
            'orders',
            'images',
            'status',
            'statusChoices',
            'shippingRegion',
            'shippingRate',
            'orderItems'
        ));
    }
    public function orderUpdate(Request $request, $id)
    {
        $statusId = DB::table('statuses')->where('status', $request->status)->value('id');

        $updateData = [
            'status_id' => $statusId,
        ];

        if (strtolower($request->status) === 'shipped') {
            $updateData['date_shipped'] = Carbon::now()->toDateString();
        }

        if (strtolower($request->status) === 'delivered') {
            $updateData['date_delivered'] = Carbon::now()->toDateString();
        }

        $order = Order::where('id', $id)->update($updateData);

        if ($order > 0) {
            $myOrder = DB::table('customers as c')
                ->join('orders as o', 'o.customer_id', '=', 'c.id')
                ->join('shippings as sh', 'o.shipping_id', '=', 'sh.id')
                ->join('order_item as io', 'o.id', '=', 'io.order_id')
                ->join('items as i', 'io.item_id', '=', 'i.id')
                ->leftJoin('item_images as img', 'i.id', '=', 'img.item_id')
                ->join('statuses as s', 'o.status_id', '=', 's.id') // Join with status table
                ->where('o.id', $id)
                ->select(
                    'c.user_id',
                    'io.item_id',
                    'i.item_name',
                    'i.description',
                    'io.quantity',
                    'img.image_path',
                    'i.sell_price',
                    'sh.region as shipping_region',
                    'sh.rate as shipping_rate',
                    's.status as order_status'
                )
                ->get();

            $orders = DB::table('orders')
                ->join('statuses as s', 'orders.status_id', '=', 's.id')
                ->where('orders.id', $id)
                ->select('orders.*', 's.status as order_status')
                ->first();

            $user = DB::table('users as u')
                ->join('customers as c', 'u.id', '=', 'c.user_id')
                ->join('orders as o', 'o.customer_id', '=', 'c.id')
                ->where('o.id', $id)
                ->select('u.id', 'u.email', 'u.name', 'c.addressline', 'c.town', 'c.phone')
                ->first();

            $subtotal = $myOrder->sum(function ($item) {
                return $item->quantity * $item->sell_price;
            });

            $shippingRate = $myOrder->isNotEmpty() ? $myOrder[0]->shipping_rate : 0;
            $grandTotal = $subtotal + $shippingRate;

            Mail::to($user->email)->send(new OrderUpdate($orders, $myOrder, $user, $grandTotal));

            return redirect()->route('admin.orders')->with('success', 'Order updated successfully!');
        }

        return redirect()->route('admin.orders')->with('error', 'Order update failed or email not sent.');
    }

    public function myOrders(Request $request)
    {
        $userId = Auth::id();
        $activeTab = $request->get('status', 'all');

        $query = DB::table('orders as o')
            ->join('customers as c', 'c.id', '=', 'o.customer_id')
            ->leftJoin('shippings as sh', 'sh.id', '=', 'o.shipping_id')
            ->join('statuses as s', 's.id', '=', 'o.status_id')
            ->where('c.user_id', $userId)
            ->select(
                'o.id',
                's.status as order_status',
                'o.date_placed',
                'sh.region as shipping_method',
                'sh.rate as shipping_rate'
            );

        if ($activeTab !== 'all') {
            $query->where('s.status', $activeTab);
        }

        $orders = $query->orderBy('o.id', 'desc')->get();

        $statusCounts = DB::table('orders as o')
            ->join('customers as c', 'c.id', '=', 'o.customer_id')
            ->join('statuses as s', 's.id', '=', 'o.status_id')
            ->where('c.user_id', $userId)
            ->select('s.status', DB::raw('count(*) as count'))
            ->groupBy('s.status')
            ->pluck('count', 's.status')
            ->toArray();

        $statusCounts['all'] = array_sum($statusCounts);

        foreach ($orders as $order) {
            if (!isset($order->shipping_method)) {
                $order->shipping_method = 'Standard shipping';
            }
            if (!isset($order->shipping_rate)) {
                $order->shipping_rate = 0;
            }

            // Get order items
            $orderItems = DB::table('order_item as io')
                ->join('items as i', 'io.item_id', '=', 'i.id')
                ->where('io.order_id', $order->id)
                ->select(
                    'i.id as item_id',
                    'i.item_name',
                    'i.description',
                    'io.quantity',
                    'i.sell_price'
                )
                ->get();

            $itemsWithImages = collect();

            foreach ($orderItems as $item) {
                // Get the first available image for each item
                $image = DB::table('item_images')
                    ->where('item_id', $item->item_id)
                    ->whereNull('deleted_at')
                    ->select('image_path')
                    ->first();

                $item->image_path = $image ? $image->image_path : null;

                $itemsWithImages->push($item);
            }

            $order->items = $itemsWithImages;

            $order->subtotal = $order->items->sum(function ($item) {
                return $item->sell_price * $item->quantity;
            });

            $order->total = $order->subtotal + $order->shipping_rate;
        }

        return view('order.index', compact('orders', 'activeTab', 'statusCounts'));
    }

    public function cancel(Request $request, $orderId)
    {
        $order = DB::table('orders')
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('orders.id', $orderId)
            ->select('orders.*', 'statuses.status as order_status')
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $pendingStatusId = DB::table('statuses')->where('status', 'Pending')->value('id');

        if ($order->status_id !== $pendingStatusId) {
            return redirect()->back()->with('error', 'Only pending orders can be cancelled.');
        }

        try {
            DB::beginTransaction();

            $orderItems = DB::table('order_item')->where('order_id', $orderId)->get();

            foreach ($orderItems as $orderItem) {
                $stock = DB::table('item_stock')->where('item_id', $orderItem->item_id)->first();

                if ($stock) {
                    DB::table('item_stock')
                        ->where('item_id', $orderItem->item_id)
                        ->update(['quantity' => $stock->quantity + $orderItem->quantity]);
                }
            }

            $cancelledStatusId = DB::table('statuses')->where('status', 'Cancelled')->value('id');
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['status_id' => $cancelledStatusId]);

            DB::commit();

            return redirect()->back()->with('success', 'Order cancelled successfully, and stock has been restored.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred while cancelling the order: ' . $e->getMessage());
        }
    }
}
