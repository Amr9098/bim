<?php

namespace Modules\Payment\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{




public function store(PaymentRequest $request)
{
    try {
        $validatedData = $request->validated();
        $user_id = Transaction::find($validatedData['transaction_id'])->value('user_id');

         Payment::create([
            'transaction_id' => $validatedData['transaction_id'],
            'user_id' =>  $user_id,
            'amount' => $validatedData['amount'],
            'paid_on' => now(),
            'details' => $validatedData['details'],
        ]);
/// all transaction money doing in model using boot function
        return response()->json(['message' => 'Payment created successfully.'], 201);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error creating payment. Please try again.', 'error' => $e->getMessage()], 500);
    }
}

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('payment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
