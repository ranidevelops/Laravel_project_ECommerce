@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Register</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">    
            <form action="/process-register" method="post" name="registrationForm" id="registrationForm">
                @csrf <!-- Add CSRF token for Laravel -->
                <h4 class="modal-title">Register Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="cpassword" name="cpassword">
                </div>
                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div> 
                <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
            </form>			
            <div class="text-center small">Already have an account? <a href="login.php">Login Now</a></div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
    $(document).ready(function() {
        $("#registrationForm").submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get form data
            var formData = new FormData(this);

            // Submit form data using Fetch API
            fetch('/process-register', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}' // Add CSRF token for Laravel
                }
            })
            .then(response => {
                if (response.ok) {
                    // Handle successful response
                    alert('Registration successful!');
                } else {
                    // Handle error response
                    alert('Registration failed!');
                }
            })
            .catch(error => {
                // Handle network errors
                console.error('Error:', error);
            });
        });
    });
</script>
@endsection
