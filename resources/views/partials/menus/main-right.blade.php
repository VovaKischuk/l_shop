<div class="header_rigth_menu">
    <img src="/img/prf.png">
    <ul>
        @guest
            <li><a href="{{ route('register') }}">Sign Up</a></li>
            <li><a href="{{ route('login') }}">Login</a></li>
        @else
            <li>
                <a href="{{ route('users.edit') }}">My Account</a>
            </li>
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    Logout
                </a>
            </li>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        @endguest
    </ul>
</div>

