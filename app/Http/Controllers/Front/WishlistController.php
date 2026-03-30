<?php

namespace App\Http\Controllers\Front;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        return redirect()->route('user.wishlist');
    }

    /**
     * Toggle the wishlist status of a product for the authenticated user.
     */
    public function toggle(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return response()->json(['error' => 'You must be logged in to manage your wishlist.'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::guard('web')->id();
        $productId = $request->product_id;

        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $inWishlist = false;
            $message = 'Product removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $inWishlist = true;
            $message = 'Product added to wishlist.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'in_wishlist' => $inWishlist,
        ]);
    }
}
