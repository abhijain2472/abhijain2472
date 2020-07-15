@extends('layout')
@section('page-title', "$addText Product")
@section('page-heading', "$addText Product")
@section('page-description', $description)

@section('active-main-product', 'active')
@section('active-main-product-div', 'show')
@section('active-main-product-add', 'active')

@section('admin-main-content')
<input type="hidden" id="csrfToken" value="{{ csrf_token() }}">
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#download">Download File</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#sheetdata">Fill Sheet Data</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#upload">Upload Data</a>
    </li>
</ul>

<div class="tab-content bg-white">
    <div id="download" class="container tab-pane active">
        <div class="row justify-content-center">
            <div class="col-auto">
                <button class="csv-download btn btn-success" ajax-data="/csv-download/product">Download</button>
            </div>
        </div>
    </div>
    <div id="sheetdata" class="container tab-pane">
        @include('product.import_help')
    </div>

    <div id="upload" class="container tab-pane">
        <div class="row justify-content-center mt-3">
            <div class="col-auto">
                <button class="btn btn-success csv-upload"><i class="fas fa-upload mr-2"></i>Upload File</button>
                <form id="upload_form" class="d-none" enctype="multipart/form-data">
                    <input type="text"  id="csrfToken" name="_token" value="{{ csrf_token() }}">
                    <input type="file" id="file_upload" name="file_upload">
                    <input type="submit"><input type="reset">
                </form>
            </div>
        </div>

        <div class="row justify-content-center mt-3 summary">
            <div class="col-auto text-center">
                <div id="rowsuccess"></div>
            </div>
            <div class="col-auto text-center">
                <div id="rowskipped"></div>
            </div>
        </div>

        <div class="row justify-content-center mt-3 summary">
            <div class="col-12">
                <div id="summary-div" class="table-responsive"></div>
            </div>
        </div>

        <div class="row justify-content-center summary add-btn">
            <div class="col-auto">
                <button class="btn btn-primary ajax-save" id="add-product" redirect="/product-list">Add Data</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('internal-js')
    <script src="{{ DIR_HTTP_JS }}jquery.steps.js"></script>
    <script>
        $("#example-tabs").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            enableFinishButton: false,
            enablePagination: false,
            enableAllSteps: true,
            titleTemplate: "#title#",
            cssClass: "tabcontrol"
        });
    </script>
@endsection
