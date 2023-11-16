<?php

namespace Modules\Transaction\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Transaction\Http\Requests\TransactionRequest;
use Modules\Transaction\Transformers\TransactionResource;

class TransactionController extends Controller
{


    public function index()
    {
        try {
            $transactions = Transaction::with('payments')->get();

            return response()->json(TransactionResource::collection($transactions), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving transactions.'], 500);
        }
    }
    public function store(TransactionRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $transaction = Transaction::create([
                'amount' => $validatedData['amount'],
                'user_id' => $validatedData['user_id'],
                'due_on' => $validatedData['due_on'],
                'vat' => $validatedData['vat'],
                'is_vat_inclusive' => $validatedData['is_vat_inclusive'],
                'status' => 'Outstanding',
            ]);
            $transaction->remaining_amount = $transaction->getTotalAmountAttribute();
            $transaction->save();

            return response()->json(['message' => 'Transaction created successfully.'], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error.', 'errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating transaction. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }


    public function transactions_for_user_by_admin(TransactionRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $userTransactions = Transaction::where('user_id', $validatedData['user_id'])->with('payments')->get();

            if ($userTransactions->isEmpty()) {
                return response()->json(["message" => "No transactions found for the user."], 404);
            }

            return response()->json(TransactionResource::collection($userTransactions), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving transactions.'], 500);
        }
    }
    public function transactions_for_user()
    {
        try {

            $userTransactions = Transaction::where('user_id', Auth::id())->with('payments')->get();

            if ($userTransactions->isEmpty()) {
                return response()->json(["message" => "No transactions found for you."], 404);
            }

            return response()->json(TransactionResource::collection($userTransactions), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving transactions.'], 500);
        }
    }
}
