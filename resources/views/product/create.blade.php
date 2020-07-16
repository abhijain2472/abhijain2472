@extends('layout')
@isset($product['product_id'])
    @php
    foreach ($product as $key => $value) {
        $prod[$key] = $value;
    }
    @endphp
@endisset

@section('page-title', "$addText Product")
@section('page-heading', "$addText Product")
@section('page-description', $description)

@section('active-main-product', 'active')
@section('active-main-product-div', 'show')
@section('active-main-product-add', 'active')

@section('admin-card-header')
    <div class="row justify-content-between">
        <div class="col-auto">
            <span>{{ $addText }} Product Form</span>
        </div>
    </div>
@endsection

@section('admin-card-body')
    <input type="hidden" id="csrf" value="{{ csrf_token() }}">
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif

    @if (Session::get('fail'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{Session::get('fail')}}
        </div>
    @endif
    <div class="row justify-content-between">
        <div class="col-12">
            <form action="/post-form-product/{{ $prod['product_id'] }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Product name is required.">
                    <div class="col-2">
                        <label for="product_name">Product Name</label>
                    </div>
                    <div class="col">
                        <input type="text" name="product_name" id="product_name" class="form-control" value="{{ $prod['product_name'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Product price is required.">
                    <div class="col-2">
                        <label for="product_price">Product Price</label>
                    </div>
                    <div class="col">
                        <input type="text" name="product_price" id="product_price" class="form-control" value="{{ $prod['product_price'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="discount">Product Discount</label>
                    </div>
                    <div class="col">
                        <input type="text" name="discount" id="discount" class="form-control" value="{{ $prod['discount'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Product details is required.">
                    <div class="col-2">
                        <label for="product_details">Product Details</label>
                    </div>
                    <div class="col">
                        <textarea name="product_details" id="product_details" class="form-control">{{ $prod['product_details'] }}</textarea>
                    </div>
                </div>

                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Please select category.">
                    <div class="col-2">
                        <label for="product_category_id">Product Category</label>
                    </div>
                    <div class="col">
                        <select name="product_category_id" id="product_category_id" class="custom-select" value="{{ $prod['product_category_id'] }}">
                            <option value="">Select Category</option>
                            @foreach ($catrgories as $item)
                                @php
                                    $selected = "";
                                @endphp
                                @if ($item->category_id == $prod['product_category_id'])
                                    @php
                                        $selected = "selected='selected'";
                                    @endphp
                                @endif
                                <option value="{{ $item->category_id }}" {{ $selected }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row form-group imgPreview">
                    <div class="col-2"></div>
                    @if ($prod['product_image'])
                        {!! $prod['product_image'] !!}
                    @endif
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="product_image">Product Icon</label>
                    </div>
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="product_image" name="product_image">
                            <label class="custom-file-label" for="product_image">Choose file</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label>Hot Product</label>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_hot_product" name="is_hot_product" {{ $prod['is_hot_product'] }}>
                            <label class="custom-control-label" for="is_hot_product"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label>New Arrival</label>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_new_arrival" name="is_new_arrival" {{ $prod['is_new_arrival'] }}>
                            <label class="custom-control-label" for="is_new_arrival"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label>Status</label>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="status" name="status" {{ $prod['status'] }}>
                            <label class="custom-control-label" for="status"></label>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-auto">
                        <input type="submit" value="{{ $addText }} Category" class="btn btn-outline-primary">
                        <input type="reset" class="btn btn-outline-secondary">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal" id="cropmodal">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Upload & Crop Image</h3>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div id="image_crop" style="width: 350px;margin-top: 30px"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="uploadimage">Upload Image</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('internal-js')
    <script src="{{ DIR_HTTP_JS }}croppie.js"></script>
    <script>
        $(document).ready(function () {
            $image_crop = $("#image_crop").croppie({
                enableExif: true,
                viewport: {
                    width:200,
                    height:200,
                    type:'square' //circle
                },
                boundary:{
                    width:300,
                    height:300
                }
            });

            $('#product_image').change(function() {
                var reader = new FileReader();
                reader.onload = function (event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function() {
                        console.log("Image binding complete");
                    });
                }
                reader.readAsDataURL(this.files[0]);
                $("#cropmodal").modal('show');
                // if($(this).val() == "") {
                //     $("img#previewImg").fadeIn("fast").attr('src', "");
                //     $("#previewDiv").hide();
                // }
                // var tmppath = URL.createObjectURL(event.target.files[0]);
                // $("img#previewImg").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
            });

            $('#uploadimage').click(function(event){
                $image_crop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(response) {
                    $.ajax({
                        url:"/upload-crop-image",
                        type: "POST",
                        data:{"image": response, '_token':$("#csrf").val()},
                        success:function(data) {
                            $('#cropmodal').modal('hide');
                            $('#previewImg').attr('src', data);
                        }
                    });
                });
            });
        });
    </script>
@endsection
