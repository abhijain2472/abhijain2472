<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Login | Blogger</title>
    <!-- Custom fonts for this template-->
    <link href="{{ DIR_HTTP_VENDOR }}fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{DIR_HTTP_CSS}}sb-admin-2.min.css" rel="stylesheet">
</head>
@php
    $username = $password = $rememberme = "";
    if(!empty($postdata['username'])) {
        $username = $postdata['username'];
        $password = $postdata['password'];
        $rememberme = "checked";
    }
    $redirecturl = "";
    if(isset($postdata['redirecturl'])) {
        $redirecturl = $postdata['redirecturl'];
    }
@endphp
<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
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
                                    <form class="user" method="POST" action="/admin-login">
                                        @csrf
                                        <input type="text" name="redirecturl" value="{{ $redirecturl }}" class="d-none">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Username" value="{{ $username }}">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" value="{{ $password }}">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="rememberme" name="rememberme" {{ $rememberme }}>
                                                <label class="custom-control-label" for="rememberme">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="{{DIR_HTTP_VENDOR}}jquery/jquery.min.js"></script>
    <script src="{{DIR_HTTP_VENDOR}}bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{DIR_HTTP_VENDOR}}jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ DIR_HTTP_JS }}sb-admin-2.min.js"></script>
    <script src="{{DIR_HTTP_VENDOR}}jquery/jquery.cookie.js"></script>
    <script>
        $(document).ready(function () {
            $("button.btn-user").click(function () {
                var rememberme = 0;
                if($("#rememberme").prop('checked') == true) {
                    rememberme = 1;
                }
                $.ajax({
                    url: '/login-auth',
                    type: 'POST',
                    data: {username: $("#username").val(), password: $("#password").val(), "_token":"{{ csrf_token() }}", rememberme: rememberme},
                    success: function(data) {
                        if(data == "success") {
                            window.location.href = "/dashboard";
                        } else {
                            alert(data);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
