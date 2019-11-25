@extends('layout')

@section('title', 'Sign Up for an Account')

@section('content')

<?php  

$option_array = array();

$option_array[0]['id'] = 0;
$option_array[0]['name'] = 'Select';

$option_array[1]['id'] = 1;
$option_array[1]['name'] = 'Mr.';

$option_array[2]['id'] = 2;
$option_array[2]['name'] = 'Ms.';

?>

<div class="container">
    <div class="auth-pages">
        <div>
            @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
            @endif @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h2>Create Account</h2>
            <div class="spacer"></div>

            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <div class="block_title">
                    <span>Title: </span>
                    <select class="form-control" name="title">
                        <?php foreach ($option_array as $key => $value) { ?>
                            <option name="title" value="<?php echo $value['id'] ?>" ><?php echo $value['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name" required autofocus>

                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
                <input id="mobil_phone" name="mobil_phone" class="form-controll" placeholder="+380" type="text">
                <input id="password" type="password" class="form-control" name="password" placeholder="Password" placeholder="Password" required>

                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password"
                    required>                
                <div class="avatar_block">
                    <span class="label_avatar">Avatar: </span>
                    <input id="avatar" class="form-control" name="avatar" type="file" style="border:none;" />
                </div>                            
                <div class="login-container">
                    <button type="submit" class="auth-button">Create Account</button>
                    <div class="already-have-container">
                        <p><strong>Already have an account?</strong></p>
                        <a href="{{ route('login') }}">Login</a>
                    </div>
                </div>

            </form>
        </div>

        <div class="auth-right">
            <h2>New Customer</h2>
            <div class="spacer"></div>
            <p><strong>Save time now.</strong></p>
            <p>Creating an account will allow you to checkout faster in the future, have easy access to order history and customize your experience to suit your preferences.</p>

            &nbsp;
            <div class="spacer"></div>
            <p><strong>Loyalty Program</strong></p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt debitis, amet magnam accusamus nisi distinctio eveniet ullam. Facere, cumque architecto.</p>
        </div>
    </div> <!-- end auth-pages -->
</div>
@endsection
