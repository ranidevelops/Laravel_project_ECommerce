@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Edit Product</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
                    <form action="" method="post" name="productForm" id="productForm">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card mb-3">
                                        <div class="card-body">								
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="title">Title</label>
                                                        <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $product->title }}">	
                                                        <p class="error"></p>
                                                    </div>
                                                </div>
                                                    <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="title">Slug</label>
                                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="slug" value="{{ $product->slug }}">
                                                        <p class="error"></p>	
                                                    </div>
                                                </div>
                                                 <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="description"> Short Description</label>
                                                        <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder=" short Description" value="{{ $product->short_description }}"></textarea>
                                                    </div>
                                                </div>  
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="description">Description</label>
                                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description" value="{{ $product->description }}"></textarea>
                                                    </div>
                                                </div> 
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="description">Shipping and Returns</label>
                                                        <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Description" value="{{ $product->shipping_returns }}"></textarea>
                                                    </div>
                                                </div>    
                                                                                        
                                            </div>
                                        </div>	                                                                      
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h2 class="h4 mb-3">Media</h2>								
                                            <div id="image" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">    
                                                    <br>Drop files here or click to upload.<br><br>                                            
                                                </div>
                                            </div>
                                        </div>	                                                                      
                                    </div>
                                    <div class="row" id="product-gallery">

                                    
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h2 class="h4 mb-3">Pricing</h2>								
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="price">Price</label>
                                                        <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{ $product->price }}">
                                                        <p class="error"></p>	
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="compare_price">Compare at Price</label>
                                                        <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" value="{{ $product->compare_price}}">
                                                        <p class="text-muted mt-3">
                                                            To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                                        </p>	
                                                    </div>
                                                </div>                                            
                                            </div>
                                        </div>	                                                                      
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h2 class="h4 mb-3">Inventory</h2>								
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="sku" value="{{ $product->sku }}">	
                                                        <p class="error"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="barcode">Barcode</label>
                                                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{ $product->barcode }}">	
                                                    </div>
                                                </div>   
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="hidden" name="track_qty" value="No">
                                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                            <p class="error"></p>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{ $product->slug}}">	
                                                    </div>
                                                </div>                                         
                                            </div>
                                        </div>	                                                                      
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mb-3">
                                        <div class="card-body">	
                                            <h2 class="h4 mb-3">Product status</h2>
                                            <div class="mb-3">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1">Active</option>
                                                    <option value="0">Block</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="card">
                                        <div class="card-body">	
                                            <h2 class="h4  mb-3">Product category</h2>
                                            <div class="mb-3">
                                                <label for="category">Category</label>
                                                <select name="category" id="category" class="form-control">
                                                    <option value="">Select a Category</option>

                                                    @if($categories->isNotEmpty())
                                                       @foreach($categories as $category)
                                                       <option value={{$category->id}}>{{ $category->name}}</option>
                                                       @endforeach
                                                    @endif   
                                                    
                                                    
                                                </select>
                                                <p class="error"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="category">Sub category</label>
                                                <select name="sub_category" id="sub_category" class="form-control">
                                                    <option value="">select subcategory</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="card mb-3">
                                        <div class="card-body">	
                                            <h2 class="h4 mb-3">Product brand</h2>
                                            <div class="mb-3">
                                                <select name="brand" id="brand" class="form-control">
                                                <option value="">Select a Brand</option>

                                                    @if($brands->isNotEmpty())
                                                       @foreach($brands as $brand)
                                                       <option value={{$brand->id}}>{{ $brand->name}}</option>
                                                       @endforeach
                                                    @endif  
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="card mb-3">
                                        <div class="card-body">	
                                            <h2 class="h4 mb-3">Featured product</h2>
                                            <div class="mb-3">
                                                <select name="is_featured" id="is_featured" class="form-control">
                                                    <option value="No">No</option>
                                                    <option value="Yes">Yes</option>                                                
                                                </select>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                    </div>                                 
                                </div>
                            </div>
                            
                            <div class="pb-5 pt-3">
                                <button  class="btn btn-primary">Create</button>
                                <a href="{{route('products.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                            </div>
                        </div>
                    </form>
					<!-- /.card -->
				</section>

@endsection

@section('customjs')
<script>
$('#title').change(function(){
    var	element =$(this);
	$("button[type=submit]").prop('disable',true);

	$.ajax({
        url:'{{ route('getSlug')}}',
        type:'get',
        data:{title: element.val()},
        datatype:'json',
        success:function(response){
				$("button[type=submit]").prop('disable',false);

			if(response['status']== true){
				$('#slug').val(response['slug'])
			}

		}
   });
});

$('#productForm').submit(function(event){
    event.preventDefault();
    var formArray = $(this).serializeArray();
    {{-- $("button[type=submit]").prop('disable',true); --}}


    $.ajax({
        url:'{{route("products.store")}}',
        type:'post',
        data:formArray,
        datatype:'json',
        success:function(response){
            if(response['status'] == true){
            window.location.href="{{ route('products.index')}}";


            }else{
                var errors =response['errors'];
                $(".error").removeClass('invalid-feedback').html('');
                $("input[type='text'],select,input[type='number']").removeClass('is-invalid');

                $.each(errors,function(key,value){
                    $('#' + key).addClass('is-invalid')  // Corrected class name
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(value);


                });
            }

        },
        error:function(){
            console.log('something went wrong');
        }

    });
});

$('#category').change(function(){
    console.log("hello");
    var category_id = $(this).val();
    $.ajax({
        url:'{{ route("products-subcategories.index")}}',
        type:'get',
        data:{category_id:category_id},
        datatype:'json',
        success:function(response){
            $("#sub_category").find('option').not(":first").remove();
            $.each(response['subCategories'], function(key, item){
                $("#sub_category").append('<option value="'+item.id+'">'+item.name+'</option>');
            });     
        },
        error:function(){
            console.log('something went wrong');
        }
    });
});
Dropzone.autoDiscover = false;    
const dropzone = $("#image").dropzone({ 
    
    url:  "{{ route('temp-images.create') }}",
    maxFiles: 10,
    paramName: 'image',
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }, success: function(file, response) {
    

    var html = `<div class="col-md-3">
        <div class="card">
            <input type="hidden" name="image_array[]" value="${response.image_id}">
            <img src="${response.ImagePath}" class="card-img-top" alt="">
            <div class="card-body">
                <a href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>`;

    $("#product-gallery").append(html);
}

});		

</script>

@endsection