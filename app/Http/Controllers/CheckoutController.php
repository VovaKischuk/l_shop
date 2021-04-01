<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\LiqPay as LiqPay;
use App\OrderProduct;
use App\Mail\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;
use App\Wishlist;

use function GuzzleHttp\json_decode;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Cart::instance('default')->count() == 0) {
            return redirect()->route('shop.index');
        }
        
        if (auth()->user() && request()->is('guestCheckout')) {
            return redirect()->route('checkout.index');
        }

        $wishlist = new Wishlist;

        $gateway = new \Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchantId'),
            'publicKey' => config('services.braintree.publicKey'),
            'privateKey' => config('services.braintree.privateKey')
        ]);

        try {
            $paypalToken = $gateway->ClientToken()->generate();
        } catch (\Exception $e) {
            $paypalToken = null;
        }

        // START

        $list_np_city = $this->nova_posta();
        
        // END

        return view('checkout')->with([
            'paypalToken' => $paypalToken,
            'discount' => getNumbers()->get('discount'),
            'newSubtotal' => getNumbers()->get('newSubtotal'),
            'newTax' => getNumbers()->get('newTax'),
            'list_np_city' => $list_np_city,
            'newTotal' => getNumbers()->get('newTotal'),
            'wishlist' => $wishlist
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {
        // Check race condition when there are less items available to purchase
        if ($this->productsAreNoLongerAvailable()) {
            return back()->withErrors('Sorry! One of the items in your cart is no longer avialble.');
        }
        
        $contents = Cart::content()->map(function ($item) {
            return $item->model->slug.', '.$item->qty;
        })->values()->toJson();
        
        try {        
            $public_key = 'sandbox_i9354876144';
            $private_key = 'sandbox_IVYuRhbKf3nD6Nqq97OHOGd8fCCENtRScLPje5MX';
            
            $liqpay = new LiqPay($public_key, $private_key);
                                        
            $html = $liqpay->cnb_form(array(
                'action'         => 'pay',
                'amount'         =>  getNumbers()->get('newTotal'),
                'currency'       => 'UAH',
                'description'    => 'Payment for the product',
                'order_id'       => $this->addToOrdersTables($request, null)->id,
                'version'        => '3',
                'result_url'    => 'http://127.0.0.1:8000/thanks_checkout'
            ));

            // $request = $liqpay->api($request, $data);                        
            
            return view('form_liqpay')->with([
                'html' => $html
            ]);

            die;
            $order = $this->addToOrdersTables($request, null);
            
            Mail::send(new OrderPlaced($order));

            // decrease the quantities of all the products in the cart
            $this->decreaseQuantities();

            Cart::instance('default')->destroy();
            session()->forget('coupon');

            return redirect()->route('confirmation.index')->with('success_message', 'Thank you! Your payment has been successfully accepted!');
        } catch (CardErrorException $e) {
            $this->addToOrdersTables($request, $e->getMessage());
            return back()->withErrors('Error! ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paypalCheckout(Request $request)
    {
        // Check race condition when there are less items available to purchase
        if ($this->productsAreNoLongerAvailable()) {
            return back()->withErrors('Sorry! One of the items in your cart is no longer avialble.');
        }

        $gateway = new \Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchantId'),
            'publicKey' => config('services.braintree.publicKey'),
            'privateKey' => config('services.braintree.privateKey')
        ]);

        $nonce = $request->payment_method_nonce;

        $result = $gateway->transaction()->sale([
            'amount' => round(getNumbers()->get('newTotal') / 100, 2),
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $transaction = $result->transaction;

        if ($result->success) {
            $order = $this->addToOrdersTablesPaypal(
                $transaction->paypal['payerEmail'],
                $transaction->paypal['payerFirstName'].' '.$transaction->paypal['payerLastName'],
                null
            );

            Mail::send(new OrderPlaced($order));

            // decrease the quantities of all the products in the cart
            $this->decreaseQuantities();

            Cart::instance('default')->destroy();
            session()->forget('coupon');

            return redirect()->route('confirmation.index')->with('success_message', 'Thank you! Your payment has been successfully accepted!');
        } else {
            $order = $this->addToOrdersTablesPaypal(
                $transaction->paypal['payerEmail'],
                $transaction->paypal['payerFirstName'].' '.$transaction->paypal['payerLastName'],
                $result->message
            );

            return back()->withErrors('An error occurred with the message: '.$result->message);
        }
    }

    protected function addToOrdersTables($request, $error)
    {
        // Insert into orders table
        $order = Order::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'billing_email' => $request->email,
            'billing_name' => $request->name,
            'billing_address' => $request->address,
            'billing_city' => $request->city,
            'billing_province' => $request->province,
            'billing_postalcode' => $request->postalcode,
            'billing_phone' => $request->phone,
            'billing_name_on_card' => $request->name_on_card,
            'billing_discount' => getNumbers()->get('discount'),
            'billing_discount_code' => getNumbers()->get('code'),
            'billing_subtotal' => getNumbers()->get('newSubtotal'),
            'billing_tax' => getNumbers()->get('newTax'),
            'billing_total' => getNumbers()->get('newTotal'),
            'error' => $error,
        ]);

        // Insert into order_product table
        foreach (Cart::content() as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->model->id,
                'quantity' => $item->qty,
            ]);
        }

        return $order;
    }

    protected function addToOrdersTablesPaypal($email, $name, $error)
    {
        // Insert into orders table
        $order = Order::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'billing_email' => $email,
            'billing_name' => $name,
            'billing_discount' => getNumbers()->get('discount'),
            'billing_discount_code' => getNumbers()->get('code'),
            'billing_subtotal' => getNumbers()->get('newSubtotal'),
            'billing_tax' => getNumbers()->get('newTax'),
            'billing_total' => getNumbers()->get('newTotal'),
            'error' => $error,
            'payment_gateway' => 'paypal',
        ]);

        // Insert into order_product table
        foreach (Cart::content() as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->model->id,
                'quantity' => $item->qty,
            ]);
        }

        return $order;
    }

    protected function decreaseQuantities()
    {
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);

            $product->update(['quantity' => $product->quantity - $item->qty]);
        }
    }

    protected function productsAreNoLongerAvailable()
    {
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);
            if ($product->quantity < $item->qty) {
                return true;
            }
        }

        return false;
    }

    protected function nova_posta() {
        $api = '9416c9cecb4652cd73eb5967581f6db2';

        $ch = curl_init();
        $json = '{
            "apiKey": "'.$api.'",
            "modelName": "Address",
            "calledMethod": "getCities",
            "methodProperties": {
                "Page": "1"
            }
        }';

        curl_setopt($ch, CURLOPT_URL, 'https://api.novaposhta.ua/v2.0/json/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: json"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $response = json_decode($response);
        curl_close($ch);

        return $response->data;
    }
    

    public function list_np_vd(Request $request) {
        
        $input = $request->all();
        $api = '9416c9cecb4652cd73eb5967581f6db2';
        $np_city_ref = $input['ref_city'];

        $json = '{
            "apiKey": "'.$api.'",
            "modelName": "Address",
            "calledMethod": "getWarehouses",
            "methodProperties": {
                "CityRef": "'.$np_city_ref.'"
            }
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.novaposhta.ua/v2.0/json/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: json"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);        
        curl_close($ch);
        $response = json_decode($response);
                
        $option_string = '';
        foreach ($response->data as $key => $value) {
            $option_string .= '<option value='.$value->CityRef.' >'.$value->Description.'</option>';     
        }

        echo $option_string;

        die;
    }

    // public_key sandbox_i9354876144
    // private_key sandbox_IVYuRhbKf3nD6Nqq97OHOGd8fCCENtRScLPje5MX

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function thanks_checkout() {     
        
        $wishlist = new Wishlist;

        return view('thanks_checkout')->with([
            'wishlist' => $wishlist
        ]);
    }

}
