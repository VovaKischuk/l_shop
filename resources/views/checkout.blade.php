@extends('layout')

@section('title', 'Checkout')

@section('extra-css')
    <style>
        .mt-32 {
            margin-top: 32px;
        }
    </style>

    <!-- <script src="https://js.stripe.com/v3/"></script> -->

@endsection

@section('content')

    <div class="container">

        @if (session()->has('success_message'))
            <div class="spacer"></div>
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="spacer"></div>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="checkout-heading stylish-heading">Checkout</h1>
        <div class="checkout-section">
            <div>
                <form action="{{ route('checkout.store') }}" method="POST" id="payment-form">
                    {{ csrf_field() }}
                    <h2>Billing Details</h2>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        @if (auth()->user())
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                        @else
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="province">Province</label>
                            <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="postalcode">Postal Code</label>
                        <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{ old('postalcode') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}" required>
                    </div>

                    <div class="spacer"></div>

                    <!-- <h2>Payment Details</h2> -->

                    <!-- <div class="stripe_payment">
                                                
                        <div class="form-group" style="display:flex; margin-top: 15px;">
                            <input type="radio" name="radio_payment_group" value="1" style="max-width: 20px; margin-left: 0px; margin-top: 7px;" />  
                            <label for="card-element">
                                Cash on delivery
                            </label>                                                      
                        </div>

                        <div class="form-group" style="display:flex; margin-top: 15px;">
                            <input type="radio" name="radio_payment_group" value="2" style="max-width: 20px; margin-left: 0px; margin-top: 7px;">
                            <label>
                                LIQPAY
                            </label>
                        </div>

                    </div> -->

                    <h2>Shipping Details</h2>
                    
                    <div class="nova_posta">
                        <img src="{{asset('/img/nova_p.jpg')}}" />
                        <div class="form-group">
                            <label for="shipping-element">
                                City
                            </label>
                            <div class="shipping_list">
                                <select class="load_list_department">
                                    <?php foreach ($list_np_city as $key => $value) { ?>
                                        <option value="<?php echo $value->Ref; ?>" ><?php echo $value->Description; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>      

                        <div class="form-group department">
                            <label for="shipping-elements">
                                Departmen
                            </label>
                            <div class="load_list_departmant_2">
                                <select class="list_department_2">
                                        
                                </select>
                            </div>
                        </div>
                    </div>   

                    <div class="spacer"></div>                    

                    <button type="submit" id="complete-order" class="button-primary full-width">Complete Order</button>
                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">

                </form>

                <form method="POST" accept-charset="utf-8" action="https://www.liqpay.ua/api/3/checkout">
                    <input type="hidden" name="data" value="eyJ2ZXJzaW9uIjozLCJhY3Rpb24iOiJwYXkiLCJwdWJsaWNfa2V5IjoiaTkwNjI2NDY4OTgzIiwiYW1vdW50IjoiNSIsImN1cnJlbmN5IjoiVUFIIiwiZGVzY3JpcHRpb24iOiLQnNC+0Lkg0YLQvtCy0LDRgCIsInR5cGUiOiJidXkiLCJsYW5ndWFnZSI6InJ1In0=" />
                    <input type="hidden" name="signature" value="0yOSOO1GW9FQmdNp8H0zX+Matq8=" />
                    
                </form>

                
{{--                @if ($paypalToken)--}}
{{--                    <div class="mt-32">or</div>--}}
{{--                    <div class="mt-32">--}}
{{--                        <h2>Pay with PayPal</h2>--}}

{{--                        <form method="post" id="paypal-payment-form" action="{{ route('checkout.paypal') }}">--}}
{{--                            @csrf--}}
{{--                            <section>--}}
{{--                                <div class="bt-drop-in-wrapper">--}}
{{--                                    <div id="bt-dropin"></div>--}}
{{--                                </div>--}}
{{--                            </section>--}}

{{--                            <input id="nonce" name="payment_method_nonce" type="hidden" />--}}
{{--                            <button class="button-primary" type="submit"><span>Pay with PayPal</span></button>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>

            <div class="checkout-table-container">
                <h2>Your Order</h2>

                <div class="checkout-table">
                    @foreach (Cart::content() as $item)
                    <div class="checkout-table-row">
                        <div class="checkout-table-row-left">
                            <img src="{{ productImage($item->model->image) }}" alt="item" class="checkout-table-img">
                            <div class="checkout-item-details">
                                <div class="checkout-table-item">{{ $item->model->name }}</div>
                                <!-- <div class="checkout-table-description">{{ $item->model->details }}</div> -->
                                <div class="checkout-table-price">{{ $item->model->presentPrice() }}</div>
                            </div>
                        </div> <!-- end checkout-table -->

                        <div class="checkout-table-row-right">
                            <div class="checkout-table-quantity">{{ $item->qty }}</div>
                        </div>
                    </div> <!-- end checkout-table-row -->
                    @endforeach

                </div> <!-- end checkout-table -->

                <div class="checkout-totals">
                    <div class="checkout-totals-left">
                        Subtotal <br>
                        @if (session()->has('coupon'))
                            Discount ({{ session()->get('coupon')['name'] }}) :
                            <br>
                            <hr>
                            New Subtotal <br>
                        @endif
                        Tax ({{config('cart.tax')}}%)<br>
                        <span class="checkout-totals-total">Total</span>

                    </div>

                    <div class="checkout-totals-right">
                        {{ presentPrice(Cart::subtotal()) }} <br>
                        @if (session()->has('coupon'))
                            -{{ presentPrice($discount) }} <br>
                            <hr>
                            {{ presentPrice($newSubtotal) }} <br>
                        @endif
                        {{ presentPrice($newTax) }} <br>
                        <span class="checkout-totals-total">{{ presentPrice($newTotal) }}</span>

                    </div>
                </div> <!-- end checkout-totals -->
            </div>

        </div> <!-- end checkout-section -->
    </div>

@endsection

@section('extra-js')
    <!-- <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script> -->

    <!-- <script>
        (function(){
            // Create a Stripe client
            var stripe = Stripe('{{ config('services.stripe.key') }}');

            // Create an instance of Elements
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
              base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Roboto", Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                  color: '#aab7c4'
                }
              },
              invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
              }
            };

            // Create an instance of the card Element
            var card = elements.create('card', {
                style: style,
                hidePostalCode: true
            });

            // Add an instance of the card Element into the `card-element` <div>
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function(event) {
              var displayError = document.getElementById('card-errors');
              if (event.error) {
                displayError.textContent = event.error.message;
              } else {
                displayError.textContent = '';
              }
            });

            // Handle form submission
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
              event.preventDefault();

              // Disable the submit button to prevent repeated clicks
              document.getElementById('complete-order').disabled = true;

              var options = {
                name: document.getElementById('name_on_card').value,
                address_line1: document.getElementById('address').value,
                address_city: document.getElementById('city').value,
                address_state: document.getElementById('province').value,
                address_zip: document.getElementById('postalcode').value
              }

              stripe.createToken(card, options).then(function(result) {
                if (result.error) {
                  // Inform the user if there was an error
                  var errorElement = document.getElementById('card-errors');
                  errorElement.textContent = result.error.message;

                  // Enable the submit button
                  document.getElementById('complete-order').disabled = false;
                } else {
                  // Send the token to your server
                  stripeTokenHandler(result.token);
                }
              });
            });

            function stripeTokenHandler(token) {
              // Insert the token ID into the form so it gets submitted to the server
              var form = document.getElementById('payment-form');
              var hiddenInput = document.createElement('input');
              hiddenInput.setAttribute('type', 'hidden');
              hiddenInput.setAttribute('name', 'stripeToken');
              hiddenInput.setAttribute('value', token.id);
              form.appendChild(hiddenInput);

              // Submit the form
              form.submit();
            }

            // PayPal Stuff
            var form = document.querySelector('#paypal-payment-form');
            var client_token = " //$paypalToken //}}";

            braintree.dropin.create({
              authorization: client_token,
              selector: '#bt-dropin',
              paypal: {
                flow: 'vault'
              }
            }, function (createErr, instance) {
              if (createErr) {
                console.log('Create Error', createErr);
                return;
              }

              // remove credit card option
              var elem = document.querySelector('.braintree-option__card');
              elem.parentNode.removeChild(elem);

              form.addEventListener('submit', function (event) {
                event.preventDefault();

                instance.requestPaymentMethod(function (err, payload) {
                  if (err) {
                    console.log('Request Payment Method Error', err);
                    return;
                  }

                  // Add the nonce to the form and submit
                  document.querySelector('#nonce').value = payload.nonce;
                  form.submit();
                });
              });
            });

        })();
    </script> -->

    <script>
        
        $('select.load_list_department').on('change', function() {
            
            var ref_city = $('.load_list_department option:selected').val();
            var _token = $("input[name='_token']").val();
            
            $.ajax({
                type: 'GET',
                url: "/list_np_vd",
                data: {
                    'ref_city': ref_city,
                    _token:_token
                },

                success: function (data, textStatus) {
                    $('.list_department_2').html(data);
                    $('.department').show();
                }
            });            
        });

    </script>

@endsection
