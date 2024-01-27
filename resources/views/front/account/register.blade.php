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
        <div id="message-container"></div>

           
            <form action="/process-register" method="post" name="registrationForm" id="registrationForm">
                @csrf <!-- Add CSRF token for Laravel -->
                <h4 class="modal-title">Register Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                </div>
                <span style="color:red;" id="error-name" class="error-message"></span> <!-- Error container for name field -->

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                </div>
                    <span style="color:red;" id="error-email" class="error-message"></span> <!-- Error container for name field -->

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                </div>
                <span style="color:red;" id="error-phone" class="error-message"></span> <!-- Error container for name field -->

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                </div>
                    <span style="color:red;" id="error-password" class="error-message"></span> <!-- Error container for name field -->

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="cpassword" name="cpassword">
                </div>

                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div> 
                <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
            </form>			
            <div class="text-center small">Already have an account? <a href="{{route('account.login')}}">Login Now</a></div>
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
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    document.getElementById('message-container').innerHTML = '<div class="alert alert-success">Registration successful!</div>';
                    // Redirect to login page after a short delay
                    setTimeout(function() {
                        window.location.href = "/login";
                    }, 5000);
                    
                } else {
                    // Registration failed, display error messages
                    if (data.errors.hasOwnProperty('name')) {
                        document.getElementById('error-name').innerHTML = data.errors.name[0]; // Display error for name field
                    } else {
                        document.getElementById('error-name').innerHTML = ''; // Clear error if no error for name field
                    }
                    
                    if (data.errors.hasOwnProperty('email')) {
                        document.getElementById('error-email').innerHTML = data.errors.email[0]; // Display error for name field
                    } else {
                        document.getElementById('error-email').innerHTML = ''; // Clear error if no error for name field
                    }
                    if (data.errors.hasOwnProperty('phone')) {
                        document.getElementById('error-phone').innerHTML = data.errors.phone[0]; 
                    } else {
                        document.getElementById('error-phone').innerHTML = ''; 
                    }
                    if (data.errors.hasOwnProperty('password')) {
                        document.getElementById('error-password').innerHTML = data.errors.password[0]; 
                    } else {
                        document.getElementById('error-password').innerHTML = ''; 
                    }
                    
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
