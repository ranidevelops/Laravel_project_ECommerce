@extends('admin.layouts.app')
@section('content')
<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Add Response</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="brands.html" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
    <div class="container-fluid">
    <form action="" method="post" id="AddReplyForm" name="AddReplyForm">
        <div class="card">
            <div class="card-body">								
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Reply To {{$contact->name}}</label>
                            <input type="hidden" name="name" value={{$contact->name}}>
                            <input type="hidden" name="userId" value={{$contact->id}}>
                            <textarea name="reply_message" id="reply_message" class="form-control" placeholder="Add your response"></textarea>
                            <p></p>	
                        </div>
                        <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" readonly name="email" id="email" class="form-control" placeholder="Email" value={{$contact->email}}>
                            <p></p>	
                        </div>
                    </div>
                    </div>
                                                    
                </div>
            </div>							
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="brands.html" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
        </form>
    </div>
    
</section>

@endsection
@section('customjs')  
<script>
$('#AddReplyForm').submit(function(event){
    event.preventDefault();
    var element =$(this);
	$("button[type=submit]").prop('disable',true);

    $.ajax({
        url: '{{ route("reply.store", $contact->id) }}',
        type:'post',
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
				var errors= response['errors'];
				if(errors['reply_message']){
					$('#reply_message').addClass('is-invalid')
				    .siblings('p')
				    .addClass('invalid-feedback').html(errors['reply_message']);
				}else{
					$('#reply_message').removeClass('is-invalid')
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