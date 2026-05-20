<div class="header-right">

    @guest

        <div class="header-auth-links">

            <a href="/login">
                Login
            </a>

            <a href="/register">
                Register
            </a>

        </div>

    @endguest

    @auth

        <div class="admin-profile">

            {{-- AVATAR --}}

            <div class="admin-avatar">

                @if(auth()->user()->image)

                    <img
                        src="{{ asset('storage/' . auth()->user()->image) }}"
                        alt=""
                    >

                @else

                    <span>

                        {{
                            strtoupper(
                                substr(
                                    auth()->user()->name,
                                    0,
                                    1
                                )
                            )
                        }}

                    </span>

                @endif

            </div>

            {{-- INFO --}}

            <div class="admin-meta">

                <h4>

                    {{ auth()->user()->name }}

                </h4>

                <span>

                    {{ auth()->user()->role }}

                </span>

            </div>

            {{-- LOGOUT --}}

            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-btn"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Logout

                </button>

            </form>

        </div>

    @endauth

</div>