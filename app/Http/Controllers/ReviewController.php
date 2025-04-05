<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\ReviewImage;
use App\Models\Item;


class ReviewController extends Controller
{
    private function filterBadWords($text)
    {
        if (empty($text)) {
            return $text;
        }

        $badWords = DB::table('bad_words')->pluck('word')->toArray();
        
        if (empty($badWords)) {
            return $text;
        }

        foreach ($badWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $replacement = str_repeat('*', strlen($word));
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }
    public function index()
    {
        $customer_id = auth()->user()->customer->id;

        $reviews = Review::withTrashed()
            ->with(['item', 'images'])
            ->where('customer_id', $customer_id)
            ->latest()
            ->get();

        return view('review.index', compact('reviews'));
    }


    public function addReview($orderId)
    {
        $order = DB::table('orders')->where('id', $orderId)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $items = DB::table('order_item')
            ->join('items', 'order_item.item_id', '=', 'items.id')
            ->where('order_item.order_id', $orderId)
            ->select('items.id', 'items.item_name')
            ->get();

        foreach ($items as $item) {
            $item->images = DB::table('item_images')
                ->where('item_id', $item->id)
                ->whereNull('deleted_at')
                ->pluck('image_path');
        }

        return view('review.create', compact('order', 'items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.rating' => 'required|integer|min:1|max:5',
            'items.*.review_text' => 'nullable|string',
            'items.*.media_files.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,webm,mov|max:20480',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $customer_id = auth()->user()->customer->id;
        $order_id = $request->order_id;


        foreach ($request->items as $itemData) {
            $filteredReviewText = isset($itemData['review_text']) 
                ? $this->filterBadWords(trim($itemData['review_text'])) 
                : null;

            $itemData['review_text'] = $filteredReviewText;

            $review = Review::create([
                'customer_id'   => $customer_id,
                'order_id'  => $order_id,
                'item_id'       => $itemData['item_id'],
                'rating'        => $itemData['rating'],
                'review_text'   => isset($itemData['review_text']) ? trim($itemData['review_text']) : null,
            ]);

            if (isset($itemData['media_files']) && is_array($itemData['media_files'])) {
                foreach ($itemData['media_files'] as $file) {
                    $path = Storage::putFileAs(
                        'public/review_media',
                        $file,
                        $file->hashName()
                    );

                    ReviewImage::create([
                        'review_id'  => $review->id,
                        'item_id'    => $itemData['item_id'],
                        'image_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('reviews.index')->with('success', 'All reviews submitted successfully!');
    }

    public function edit(string $id)
    {
        $review = DB::table('reviews')
            ->join('items', 'reviews.item_id', '=', 'items.id')
            ->where('reviews.id', $id)
            ->select(
                'reviews.id',
                'reviews.item_id',
                'reviews.rating',
                'reviews.review_text',
                'items.item_name'
            )
            ->first();


        return view('review.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
            'media_files.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240'
        ]);

        $review = Review::findOrFail($id);
        $review->rating = $request->rating;
        $review->review_text = $this->filterBadWords($request->review_text);
        $review->save();

        if ($request->hasFile('media_files')) {
            $oldMedia = ReviewImage::where('review_id', $review->id)->get();
            foreach ($oldMedia as $media) {
                if (Storage::exists($media->image_path)) {
                    Storage::delete($media->image_path);
                }
            }

            ReviewImage::where('review_id', $review->id)->delete();

            foreach ($request->file('media_files') as $file) {
                $path = $file->store('public/review_media');
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image_path' => $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(string $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return redirect()->back()->with('error', 'Review not found.');
        }

        $review->images()->delete();
        $review->delete();

        $redirectRoute = auth()->user()->role === 'Admin' ? 'admin.reviews' : 'reviews.index';

        return redirect()->route($redirectRoute)->with('success', 'Review deleted successfully!');
    }

    public function restore($id)
    {
        $review = Review::onlyTrashed()->where('id', $id)->first();

        if (!$review) {
            return redirect()->back()->with('error', 'Review not found in trash.');
        }

        $review->restore();
        $review->images()->onlyTrashed()->restore();

        $redirectRoute = auth()->user()->role === 'Admin' ? 'admin.reviews' : 'reviews.index';

        return redirect()->route($redirectRoute)->with('success', 'Review restored successfully!');
    }
}
