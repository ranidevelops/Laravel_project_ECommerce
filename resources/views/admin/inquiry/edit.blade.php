@extends('admin.layouts.app')
@section('content')
<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Edit Inquiry</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
					<form action="" method="post" id="EditContactForm" name="EditContactForm">
						<div class="card">
							<div class="card-body">								
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Name</label>
											<input type="text" value = "{{ $contact->name}}" name="name" id="name" class="form-control" placeholder="Name">
											<p></p>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="email">email</label>
											<input readonly  value = "{{ $contact->email}}" type="text" name="email" id="email" class="form-control" placeholder="Email">
											<p></p>		
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="name">Phone</label>
											<input type="text" value = "{{ $contact->phone}}" name="phone" id="phone" class="form-control" placeholder="phone">
											<p></p>	
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="name">Message</label>
											<input type="text" value = "{{ $contact->message}}" name="message" id="message" class="form-control" placeholder="Your message">
											<p></p>	
										</div>
									</div>
									 							
								</div>
							</div>							
						</div>
						<div class="pb-5 pt-3">
							<button type="submit" class="btn btn-primary">Update</button>
							<a href="brands.html" class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
						</form>
					</div>
					<!-- /.card -->
				</section>
				<!-- /.content -->
				
				
@endsection
@section('customjs')  
<script>
$('#EditContactForm').submit(function(event){
    console.log("hello");
    event.preventDefault();
    var element =$(this);
	$("button[type=submit]").prop('disable',true);

    $.ajax({
        url:'{{ route('inquiry.update',$contact->id)}}',
        type:'put',
        data:element.serializeArray(),
        datatype:'json',
        success:function(response){
				$("button[type=submit]").prop('disable',false);

			if(response['status']== true){
				window.location.href="{{ route('inquiry.index')}}";

				$('#name').removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");

				

			}else{
				if(response['notFound']== true){
					window.location.href="{{ route('inquiry.index')}}";
				}
				var errors= response['errors'];
				if(errors['name']){
					$('#name').addClass('is-invalid')
				    .siblings('p')
				    .addClass('invalid-feedback').html(errors['name']);
				}else{
					$('#name').removeClass('is-invalid')
				   .siblings('p')
				   .removeClass('invalid-feedback').html("");

				}

				if(errors['phone']){
					$('#phone').addClass('is-invalid')
				    .siblings('p')
				    .addClass('invalid-feedback').html(errors['phone']);
				}else{
					$('#phone').removeClass('is-invalid')
				   .siblings('p')
				   .removeClass('invalid-feedback').html("");
				}

                if(errors['message']){
					$('#message').addClass('is-invalid')
				    .siblings('p')
				    .addClass('invalid-feedback').html(errors['message']);
				}else{
					$('#message').removeClass('is-invalid')
				   .siblings('p')
				   .removeClass('invalid-feedback').html("");
				}

				

			}

        },error:function(jqXHR,exception){
            console.log("something went wrong");

        }

    });

});
	
 
</script> 
@endsection             