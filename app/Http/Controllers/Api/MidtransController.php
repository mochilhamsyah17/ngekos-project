<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.serverKey');
        $hashedKey = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json([
                'message' => 'Invalid signature key'
            ], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;
        $transaction = Transaction::where('code', $orderId)->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        $sid    = env('TIWILIO_SID');
        $token  = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);

        $message =
            "Halo. " . $transaction->name . "!" . PHP_EOL .
            "Kami telah menerima pembayaran anda dengan kode booking: " . $transaction->code . PHP_EOL .
            "Total pembayaran: " . $transaction->total_amount . PHP_EOL .
            "Anda bisa datang ke boarding house untuk melakukan survey." . PHP_EOL .
            "Alamat: " . $transaction->boarding_house->address . ", " . $transaction->boarding_house->city->name . PHP_EOL .
            "Mulai tanggal: " . $transaction->start_date->format('d M Y') . PHP_EOL .
            "Terimakasih atas kepercayaan anda.";

        switch ($transactionStatus) {
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $transaction->update([
                            'payment_status' => 'pending',
                        ]);
                    } else {
                        $transaction->update([
                            'payment_status' => 'success',
                        ]);
                    }
                }
                break;
            case 'settlement':
                $transaction->update([
                    'payment_status' => 'success',
                ]);

                $twilio->messages->create(
                    "whatsapp:+" . $transaction->phone,
                    array(
                        "from" => "whatsapp:+14155238886",
                        "body" => $message
                    )
                );
                break;
            case 'pending':
                $transaction->update([
                    'payment_status' => 'pending'
                ]);
                break;
            case 'deny':
                $transaction->update([
                    'payment_status' => 'failed',
                ]);
                break;
            case 'expire':
                $transaction->update([
                    'payment_status' => 'failed',
                ]);
                break;
            case 'canceled':
                $transaction->update([
                    'payment_status' => 'canceled',
                ]);
                break;
            default:
                $transaction->update([
                    'payment_status' => 'unknown',
                ]);
                break;
        }

        return response()->json([
            'headers' => [
                'Content-Type' => 'application/json',
                'ngrok-skip-browser-warning' => true,
            ],
            'message' => 'callback berhasil dilakukan'
        ]);
    }
}
