<x-guest-layout>
    <x-slot name="links">
        <style>
            .Rectangle_4 {
                border-radius: 6px;
                background-image: -moz-linear-gradient( 30deg, rgb(122,98,21) 0%, rgb(214,161,0) 100%);
                background-image: -webkit-linear-gradient( 30deg, rgb(122,98,21) 0%, rgb(214,161,0) 100%);
                background-image: -ms-linear-gradient( 30deg, rgb(122,98,21) 0%, rgb(214,161,0) 100%);
            }
            .btn-primary {
                border-color: #7a6215 !important;
            }
            
        </style>
    </x-slot>
    
            <h3 class="mb-1 fw-bold text-center">Welcome to UZIP!</h3>
            <p class="mb-4 text-center">Please sign-in to your account</p>

            <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3 form-group">
                    <label for="email" class="form-label">Email</label>
                    <input
                            type="text"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            autofocus
                    />
                </div>
                <div class="mb-3 form-group form-password-toggle">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                        <input
                                type="password"
                                id="password"
                                class="form-control"
                                name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password"
                        />
                        <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility()"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>
                <div class="mb-3 form-group">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100 Rectangle_4">Sign in</button>
            </form>

</x-guest-layout>