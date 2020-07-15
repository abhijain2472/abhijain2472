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
                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Category title is required.">
                    <div class="col-2">
                        <label for="first_name">First Name</label>
                    </div>
                    <div class="col">
                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $cust['first_name'] }}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-2"></div>
                    @if ($cust['image'])
                        {!! $cust['image'] !!}
                    @endif
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="icon">Category Icon</label>
                    </div>
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="icon" name="icon">
                            <label class="custom-file-label" for="icon">Choose file</label>
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
                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Category title is required.">
                    <div class="col-2">
                        <label for="first_name">First Name</label>
                    </div>
                    <div class="col">
                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $cust['first_name'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Category title is required.">
                    <div class="col-2">
                        <label for="last_name">Last Name</label>
                    </div>
                    <div class="col">
                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $cust['last_name'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Category title is required.">
                    <div class="col-2">
                        <label for="email ">Email Address</label>
                    </div>
                    <div class="col">
                        <input type="text" name="email " id="email " class="form-control" value="{{ $cust['email'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Category title is required.">
                    <div class="col-2">
                        <label for="password ">Password</label>
                    </div>
                    <div class="col">
                        <input type="text" name="password " id="password " class="form-control" value="{{ $cust['password'] }}">
                    </div>
                </div>

                <div class="row form-group">
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
@endsection

@section('internal-js')
    <script>
        $(document).ready(function () {
            $('#icon').change(function(event) {
                if($(this).val() == "") {
                    $("img#previewImg").fadeIn("fast").attr('src', "");
                    $("#previewDiv").hide();
                }
                var tmppath = URL.createObjectURL(event.target.files[0]);
                $("img#previewImg").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
@endsection
