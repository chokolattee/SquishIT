<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Stock;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemStockImport;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Cart;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shipping;
use App\Models\Review;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\Mail;
use App\Models\ItemImage;
use App\Models\ReviewImage;

class ItemController extends Controller
{
    public function index()
    {
        $items = DB::table('items')
            ->join('item_stock', 'items.id', '=', 'item_stock.item_id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.*', 'item_stock.quantity', 'categories.description as category_description')
            ->get();

        $images = DB::table('item_images')->get()->groupBy('item_id');

        return view('item.index', compact('items', 'images'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required|min:4',
            'images.*' => 'required|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'qty' => 'required|integer',
            'item_name' => 'required|string|min:4',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $item = Item::create([
            'item_name' => trim($request->item_name),
            'description' => trim($request->description),
            'cost_price' => $request->cost_price,
            'sell_price' => $request->sell_price,
            'category_id' => $request->category_id
        ]);

        Stock::create([
            'item_id' => $item->id,
            'quantity' => $request->qty
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = Storage::putFileAs(
                    'public/images',
                    $image,
                    $image->hashName()
                );

                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.items')->with('success', 'Item added successfully!');
    }

    public function show($id)
    {
        $item = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->where('items.id', $id)
            ->select('items.*', 'categories.description as category')
            ->first();

        $images = DB::table('item_images')
            ->where('item_id', $id)
            ->whereNull('deleted_at')
            ->get();

        $reviews = DB::table('reviews')
            ->join('customers', 'reviews.customer_id', '=', 'customers.id')
            ->join('users as u', 'customers.user_id', '=', 'u.id')
            ->where('reviews.item_id', $id)
            ->whereNull('reviews.deleted_at')
            ->select('reviews.*', 'u.profile_image', DB::raw("CONCAT(customers.fname, ' ', customers.lname) as customer_name"))
            ->latest()
            ->get();

        $averageRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;

        $reviewMedia = DB::table('review_images')
            ->whereIn('review_id', $reviews->pluck('id'))
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('review_id');

        return view('item.show', compact('item', 'images', 'reviews', 'reviewMedia', 'averageRating'));
    }

    public function edit($id)
    {
        $item = DB::table('items')
            ->join('item_stock', 'items.id', '=', 'item_stock.item_id')
            ->select('items.*', 'item_stock.quantity as stock_quantity')
            ->where('items.id', $id)
            ->first();

        return view('item.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        $stock = Stock::where('item_id', $id)->first();

        if (!$item || !$stock) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $rules = [
            'description' => 'required|min:4',
            'images.*' => 'nullable|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'qty' => 'required|integer|min:0',
            'item_name' => 'required|string|min:4',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $item->update([
            'item_name' => trim($request->item_name),
            'description' => trim($request->description),
            'cost_price' => $request->cost_price,
            'sell_price' => $request->sell_price,
            'category_id' => $request->category_id
        ]);

        $stock->update([
            'quantity' => $request->qty
        ]);

        if ($request->hasFile('images')) {
            $oldImages = ItemImage::where('item_id', $id)->get();
            foreach ($oldImages as $oldImage) {
                Storage::delete($oldImage->image_path);
                $oldImage->delete();
            }

            foreach ($request->file('images') as $image) {
                $path = $image->store('public/images');
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.items')->with('success', 'Item updated successfully!');
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $item->images()->delete();
        $item->delete();

        return redirect()->route('admin.items')->with('success', 'Item deleted successfully!');
    }

    public function restore($id)
    {
        $item = Item::onlyTrashed()->where('id', $id)->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found in trash.');
        }

        $item->restore();
        $item->images()->onlyTrashed()->restore();

        return redirect()->route('admin.items')->with('success', 'Item restored successfully!');
    }

    public function import()
    {
        Excel::import(
            new ItemStockImport,
            request()
                ->file('item_upload')
                ->storeAs(
                    'files',
                    request()
                        ->file('item_upload')
                        ->getClientOriginalName()
                )
        );

        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }

    public function getItems(Request $request)
    {
        $query = DB::table('items')
            ->join('item_stock', 'items.id', '=', 'item_stock.item_id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->whereNull('items.deleted_at')
            ->whereNull('categories.deleted_at')
            ->select('items.*', 'item_stock.quantity', 'categories.description as category_description');

        if ($request->filled('category_id')) {
            $query->where('items.category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('items.sell_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('items.sell_price', '<=', $request->max_price);
        }

        $items = $query->get();

        $images = DB::table('item_images')
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('item_id');

        $reviews = DB::table('reviews')
            ->join('customers', 'reviews.customer_id', '=', 'customers.id')
            ->select(
                'reviews.*',
                DB::raw("CONCAT(customers.fname, ' ', customers.lname) as customer_name")
            )
            ->get()
            ->groupBy('item_id');

        $reviewMedia = DB::table('review_images')->get()->groupBy('review_id');

        $categories = DB::table('categories')->get();

        return view('shop.index', compact('items', 'images', 'reviews', 'reviewMedia', 'categories'));
    }

    public function addToCart($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect('/')->with('error', 'Item not found.');
        }

        $stock = Stock::where('item_id', $id)->first();

        if (!$stock || $stock->quantity <= 0) {
            return redirect()->back()->with('error', 'Item is out of stock.');
        }

        $oldCart = Session::get('cart', new Cart(null));
        $cart = new Cart($oldCart);

        $cart->add($item, $id);
        Session::put('cart', $cart);

        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function getCart(Request $request)
    {
        $user = User::find(Auth::id());
        $customer = Customer::where('user_id', $user->id)->firstOrFail();

        $shipping = Shipping::all();
        $rate = 0;

        if ($request->has('shipping_id')) {
            $shipping = Shipping::find($request->id);
            $rate = $shipping->rate;
        }

        // dump(Session::get('cart'));

        if (!Session::has('cart')) {
            return view('shop.shopping-cart', [
                'products' => [],
                'totalPrice' => 0,
                'customer' => $customer,
                'shippingOptions' => $shipping,
                'rate' => $rate
            ]);
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        // dd($cart);
        return view('shop.shopping-cart', [
            'products' => $cart->items,
            'totalPrice' => $cart->totalPrice,
            'customer' => $customer,
            'shippingOptions' => $shipping,
            'rate' => $rate
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        $cart = Session::get('cart');

        if (!$cart || !isset($cart->items[$id])) {
            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        $newQty = max(1, intval($request->quantity)); // Ensure quantity is at least 1

        $cart->items[$id]['qty'] = $newQty;
        $cart->items[$id]['sell_price'] = $cart->items[$id]['item']['sell_price'] * $newQty;

        $cart->totalPrice = array_sum(array_column($cart->items, 'sell_price'));

        Session::put('cart', $cart); // Save back to session

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function removeItem($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        $cart->remove($id);

        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function postCheckout(Request $request)
    {
        if (!Session::has('cart')) {
            return redirect()->route('getCart');
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        try {
            DB::beginTransaction();

            $customer = Customer::where('user_id', Auth::id())->first();

            $order = new Order();
            $order->date_placed = now();
            $order->status_id = 1;
            $order->customer_id = $customer->id;

            $rules = [
                'shipping_id' => 'required|exists:shippings,id',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->route('getCart')->with('error', 'Invalid shipping method.');
            }

            $shippingID = $request->shipping_id;
            $order->shipping_id = $shippingID;

            $shipping = Shipping::find($shippingID);

            $order->save();


            foreach ($cart->items as $items) {
                $id = $items['item']['id'];

                DB::table('order_item')->insert([
                    'item_id' => $id,
                    'order_id' => $order->id,
                    'quantity' => $items['qty'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $stock = Stock::find($id);
                if ($stock && $stock->quantity >= $items['qty']) {
                    $stock->quantity -= $items['qty'];
                    $stock->save();
                } else {
                    throw new \Exception("Not enough stock for item: " . $items['item']['item_name']);
                }
            }
            DB::commit();

            Session::forget('cart');

            Mail::to(Auth::user()->email)->send(new OrderMail($order, $cart, $shipping, $customer));

            return redirect('/')->with('success', 'Successfully purchased your products! Order receipt sent to your email.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('getCart')->with('error', $e->getMessage());
        }
    }
}