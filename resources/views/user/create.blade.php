@extends('layout')

@isset($user['id'])
    @php
    foreach ($user as $key => $value) {
        $cust[$key] = $value;
    }
    @endphp
@endisset

@section('page-title', "$addText Customer")
@section('page-heading', "$addText Customer")
@section('page-description', $description)

@section('active-main-customer', 'active')
@section('active-main-customer-div', 'show')
@section('active-main-customer-add', 'active')

@section('admin-card-header')
    <div class="row justify-content-between">
        <div class="col-auto">
            <span>{{ $addText }} Customer Form</span>
        </div>
    </div>
@endsection
@section('admin-card-body')
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
            <form action="/post-form-customer/{{ $cust['id'] }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="id" value="{{ $cust['id'] }}">
                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="First name is required.">
                    <div class="col-2">
                        <label for="first_name">First Name</label>
                    </div>
                    <div class="col">
                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $cust['first_name'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="last_name">Last Name</label>
                    </div>
                    <div class="col">
                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $cust['last_name'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center validate" data-validation="required_email" validation-msg="Email address is required.|Provide valid email address">
                    <div class="col-2">
                        <label for="email">Email Address</label>
                    </div>
                    <div class="col">
                        <input type="text" name="email" id="email" class="form-control" value="{{ $cust['email'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center validate" data-validation="conditional_required" data-element="#id" data-value="*" validation-msg="Password is required.">
                    <div class="col-2">
                        <label for="password">Password</label>
                    </div>
                    <div class="col">
                        <input type="text" name="password" id="password" class="form-control" value="" data-min-length="6" data-max-length="18" >
                    </div>
                </div>

                <div class="row form-group imgPreview">
                    <div class="col-2"></div>
                    @if ($cust['image'])
                        {!! $cust['image'] !!}
                    @endif
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="image">Image</label>
                    </div>
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label>Status</label>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="status" name="status" {{ $cust['status'] }}>
                            <label class="custom-control-label" for="status"></label>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-auto">
                        <input type="submit" value="{{ $addText }} Customer" class="btn btn-outline-primary">
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
            $('#image').change(function() {
                if($(this).val() != "") {
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        $image_crop.croppie('bind', {
                            url: event.target.result
                        }).then(function() {
                            console.log("image is ready for cropping");
                        });
                    }
                    reader.readAsDataURL(this.files[0]);
                    $("#cropmodal").modal('show');
                //     $("img#previewImg").fadeIn("fast").attr('src', "");
                //     $("#previewDiv").hide();
                }
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
