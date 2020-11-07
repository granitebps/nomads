<?php

namespace App\Http\Controllers;

use App\Mail\TransactionSuccess;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        // Set Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $notif = new \Midtrans\Notification();

        // Split order_id
        $order = explode('-', $notif->order_id);

        $status = $notif->transaction_status;
        $type = $notif->payment_type;
        $fraud = $notif->fraud_status;
        $order_id = $order[1];

        $transaction = Transaction::findOrFail($order_id);

        if ($status === 'capture') {
            if ($type === 'credit_card') {
                if ($fraud === 'challenge') {
                    $transaction->transaction_status = 'CHALLENGE';
                } else {
                    $transaction->transaction_status = 'SUCCESS';
                }
            }
        } else if ($status === 'settlement') {
            $transaction->transaction_status = 'SUCCESS';
        } else if ($status === 'pending') {
            $transaction->transaction_status = 'PENDING';
        } else if ($status === 'deny') {
            $transaction->transaction_status = 'FAILED';
        } else if ($status === 'expire') {
            $transaction->transaction_status = 'EXPIRED';
        } else if ($status === 'cancel') {
            $transaction->transaction_status = 'FAILED';
        }

        $transaction->save();

        // Send E-Ticket to User
        if ($transaction) {
            if ($status === 'capture' && $fraud === 'accept') {
                Mail::to($transaction->user)->send(new TransactionSuccess($transaction));
            } else if ($status === 'settlement') {
                Mail::to($transaction->user)->send(new TransactionSuccess($transaction));
            } else if ($status === 'success') {
                Mail::to($transaction->user)->send(new TransactionSuccess($transaction));
            } else if ($status === 'capture' && $fraud === 'challenge') {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'message' => 'Midtrans Payment Challenge'
                    ]
                ]);
            } else {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'message' => 'Midtrans Payment Not Settlement'
                    ]
                ]);
            }
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Notification Success'
                ]
            ]);
        }
    }

    public function finishRedirect()
    {
        return view('pages.success');
    }

    public function unfinishRedirect()
    {
        return view('pages.unfinish');
    }

    public function errorRedirect()
    {
        return view('pages.failed');
    }
}
