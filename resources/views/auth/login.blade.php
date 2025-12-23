@extends('layouts.layout')
@section('content')
<section class="forms">
    <div class="form login">
        <div class="form-content">
            <h2>Login</h2>
            <form action="#" id="login">
                {!! csrf_field() !!}

                <div class="field input-field">
                    <input type="email" placeholder="Email" class="input email" name="email" id="email">
                </div>
                <div class="field input-field">
                    <input type="password" placeholder="Password" name="password" id="password" required>
                    <!-- <i class="fa-solid fa-eye eye-icon" id="togglePassword" style="cursor: pointer;"></i> -->
                </div>
                <div class="field button-field">
                    <button id="login-btn" type="submit" name="button">Login</button>
                </div>
            </form>
        </div>
    </div>
</section>

@section('scripts')
<script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", () => {
        // Toggle password visibility
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }

        // Toggle the eye icon
        togglePassword.classList.toggle("fa-eye-slash");
    });
</script>
@endsection
@endsection
