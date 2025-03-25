<?php

namespace App\Http\Controllers\Api\V2;

use Stripe\Stripe;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Http\Controllers\Controller;


class StripeController extends Controller
{
    public function success(Request $request)
{
    $enrollment = Enrollment::find($request->enrollment_id);
    // dd($enrollment);
    if ($enrollment) {
        $enrollment->update(['status' => 'accepted']);
        return response()->json(['message' => 'Paiement réussi ! Inscription validée.', 'enrollment' => $enrollment]);
    }

    return response()->json(['message' => 'Paiement réussi, mais aucune inscription trouvée.'], 404);
}


    public function cancel()
    {
        return response()->json(['message' => 'Paiement annulé.']);
    }
}
