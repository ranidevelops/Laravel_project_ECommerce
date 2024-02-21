@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Contact</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">  
        <div id="message-container"></div>

           
            <form action="/process-contact" method="post" name="ContactForm" id="ContactForm">
                @csrf <!-- Add CSRF token for Laravel -->
                <h4 class="modal-title">Contact Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                </div>
                <span style="color:red;" id="error-name" class="error-message"></span> 

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                </div>
                    <span style="color:red;" id="error-email" class="error-message"></span> 

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                </div>
                <span style="color:red;" id="error-phone" class="error-message"></span> 

               <div class="form-group">
                   <textarea class="form-control" placeholder="Your message" name="message"></textarea>
               </div>
               <span style="color:red;" id="error-message" class="error-message"></span> 

                <div>
                <button style="padding-top:10px;" type="submit" class="btn btn-dark btn-block btn-lg" value="contact">submit</button>
                </div>
            </form>			
        </div>
    </div>
</section>

@endsection
@section('customJs')
<script type="text/javascript">
    $(document).ready(function() {
         $("#ContactForm").submit(function(event) {
            {{-- alert("hello"); --}}
            // Prevent default form submission
            event.preventDefault();

            // Get form data
            var formData = new FormData(this);

            // Submit form data using Fetch API
            fetch('/process-contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}' // Add CSRF token for Laravel
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    document.getElementById('message-container').innerHTML = '<div class="alert alert-success">Message sent successfully!</div>';
                    // Redirect to login page after a short delay
                    document.getElementById("ContactForm").reset();

                    setTimeout(function() {
                        window.location.href = "/";
                    }, 5000);
                    
                } else {
                    // Registration failed, display error messages
                    if (data.errors.hasOwnProperty('name')) {
                        document.getElementById('error-name').innerHTML = data.errors.name[0]; 
                    } else {
                        document.getElementById('error-name').innerHTML = ''; 
                    }
                    
                    if (data.errors.hasOwnProperty('email')) {
                        document.getElementById('error-email').innerHTML = data.errors.email[0]; 
                    } else {
                        document.getElementById('error-email').innerHTML = ''; 
                    }
                    if (data.errors.hasOwnProperty('phone')) {
                        document.getElementById('error-phone').innerHTML = data.errors.phone[0]; 
                    } else {
                        document.getElementById('error-phone').innerHTML = ''; 
                    }
                    if (data.errors.hasOwnProperty('message')) {
                        document.getElementById('error-message').innerHTML = data.errors.message[0]; 
                    } else {
                        document.getElementById('error-message').innerHTML = ''; 
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