<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     * Hämta alla reviews
     */
    public function index()
    {
        $reviews = Review::with('user')->get();
        $reviews->each(function ($review) {
            $review->user_name = $review->user->name;
        });
        return response()->json($reviews);
    }

    /**
     * Hämta alla recensioner för en bok
     */
    public function getReviewsForBook(string $id)
    {
        // Läs in alla recentioner där id matchar bok-id
        $reviews = Review::with('user')->where('book_id', $id)->get();
        //Inga recensioner - 404
        if ($reviews->isEmpty()) {
            return response()->json(['message' => 'Det finns inga recensioner för denna bok'], 404);
        }
        $reviews->each(function ($review) {
            $review->user_name = $review->user->name;
        });
        // returnera recensioner
        return response()->json($reviews);
    }


    /**
     * Hämta alla recensioner för en användare
     */
    public function getReviewsByUser(string $id)
    {
        // Läs in alla recentioner där id matchar user-id
        $reviews = Review::where('user_id', $id)->get();

        //Inga recensioner - 404
        if ($reviews->isEmpty()) {
            return response()->json(['message' => 'Denna användare har inte recenserat några böcker än'], 404);
        }
        // returnera recensioner
        return response()->json($reviews);
    }

    /**
     * Display the specified resource.
     * Hämta en recension med valt id
     */
    public function show(string $id)
    {
        $review = Review::with('user')->find($id);
        if (!$review) {
            return response()->json([
                'message' => 'Ingen recension med detta id hittades'
            ], 404);
        }

        $review->user_name = $review->user->name;
        return response()->json($review);
    }

    /**
     * Store a newly created resource in storage.
     * Skapa en ny recension
     */
    public function store(Request $request)
    {
        // Hämta autentiserad användare
        $user = $request->user();

        // Validera input
        $request->validate([
            'book_id' => 'required|string',
            'rating' => 'required|numeric|integer|between:1,5',
            'comment' => 'required|string'
        ]);

        // Skapa recension
        $newReview = Review::create([
            'user_id' => $user->id,
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'user_id' => $newReview->user_id,
            'user_name' => $user->name,
            'book_id' => $newReview->book_id,
            'rating' => $newReview->rating,
            'comment' => $newReview->comment,
            'created_at' => $newReview->created_at,
            'updated_at' => $newReview->updated_at,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json([
                'message' => 'Ingen recension med detta id hittades'
            ], 404);
        }

        // Hämta autentiserad användare
        $user = $request->user();

        // Om användare är admin eller författare av recensionen - Uppdatera
        if ($user->isAdmin() || $user->id === $review->user_id) {
            $request->validate([
                'rating' => 'required|numeric|integer|between:1,5',
                'comment' => 'required|string'
            ]);

            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            return $review;
        } else {
            // Användare är inte admin/användare har inte skrivit recensionen. 403 - forbidden
            return response()->json([
                'message' => 'Du saknar behörighet att utföra denna åtgärd'
            ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json([
                'message' => 'Ingen recension med detta id hittades'
            ], 404);
        }

        // Hämta autentiserad användare
        $user = $request->user();

        // Om användare är admin eller författare av recensionen - Uppdatera
        if ($user->isAdmin() || $user->id === $review->user_id) {
            $review->delete();
            return response()->json([
                'message' => 'Recension raderad'
            ]);
        }
    }
}
