<?php

namespace App\Services;
use Razorpay\Api\Api;
use RuntimeException;

class RazorpayService
{
    private Api $api;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $key=config('services.razorpay.key');
        $secret=config('services.razorpay.secret');

        if(empty($key) || empty($secret)){
            throw new RuntimeException(
                'Razorpay API credentials  are not configured'
            );
        }
        $this->api=new Api($key,$secret);
    }
    public function createOrder(int $amountInPaise,string $receipt,array $notes=[]){
        if($amountInPaise <= 0){
            throw new RuntimeException('payment  amount must be  greater than 0');


        }
        return $this->api->order->create([
            'amount'=>$amountInPaise,
            'currency'=>'INR',
            'receipt'=>$receipt,
            'notes'=>$notes,    
        ]);

    }
    public function verifyPayment(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $razorpaySignature
    ): bool {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
            ]);

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }
    public function fetchPayment(string $razorpayPaymentId)
    {
        try {
            return $this->api->payment->fetch($razorpayPaymentId);
            
        } catch (\Exception $e){
            throw new RuntimeException(
                'Unable to retrive Razorpay payment details : '.$e->getMessage()
            );

        }
    }
}
