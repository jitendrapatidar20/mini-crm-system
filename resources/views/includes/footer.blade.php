<footer>
    <div class="social-icons mb-2">
      <a href="{{ config('constants.SOCIAL_FACEBOOK_URL') }}"><i class="fab fa-facebook"></i></a>
      <a href="{{ config('constants.SOCIAL_TWITTER_URL') }}"><i class="fab fa-twitter"></i></a>
      <a href="{{ config('constants.SOCIAL_LINKEDIN_URL') }}"><i class="fab fa-linkedin"></i></a>
    </div>
    <p>{{ config('constants.COPYRIGHT') }}</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById("year").textContent = new Date().getFullYear();

    // Toggle password visibility
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('fa-eye-slash');
    });
  </script>