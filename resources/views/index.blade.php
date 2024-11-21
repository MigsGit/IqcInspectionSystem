{{-- @if(isset(Auth::user()->id) && Auth::user()->status == 1)
  <script type="text/javascript">
    window.location = "{{ url('dashboard') }}";
  </script>
@elseif((isset(Auth::user()->id) && Auth::user()->status == 2) || !isset(Auth::user()->id)) --}}
<!DOCTYPE html>
<html >
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PATS - PPD | Sign In</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  @include('shared.css_links.css_links')
</head>
<body class="hold-transition login-page" style="background: url('{{ asset('public/images/pats_bg.gif') }}'); background-repeat: no-repeat; background-size: cover; ">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card" style="box-shadow: 1px 1px 50px black;">
    <br>
    <div class="login-logo">
      <a href="{{ route('login') }}"><h2>Product Automated Traceability System <br><b>PPD-CN171 </b></h2></a>
    </div>
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      {{-- <form action="{{ route('sign_in') }}" method="post" id="formSignIn"> --}}
      <form method="post" id="formSignIn">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username" id="txtSignInUsername">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" id="txtSignInPass">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block" id="btnSignIn"><i class="fa fa-check" id="iBtnSignInIcon"></i> Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

@include('shared.js_links.js_links')

<script type="text/javascript">
  $(document).ready(function(){
    $("#formSignIn").submit(function(event){
      event.preventDefault();
      SignIn();
    //   SignInAdmin();
    });

    $.ajax({
        type: 'GET',
        url: 'check_user',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if(response.is_success === 'true'){
            }else{
            }
        },error: function (data, xhr, status){
           toastr.error(`Error: ${data.status}`);
        }
    });
    // Sign In
    function SignIn(){
        window.location = "{{ url('dashboard') }}";
        return;
        toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        };

        $.ajax({
            url: "sign_in",
            method: "post",
            data: $('#formSignIn').serialize(),
            dataType: "json",
            beforeSend: function(){
                $("#iBtnSignInIcon").addClass('fa fa-spinner fa-pulse');
                $("#btnSignIn").prop('disabled', 'disabled');
            },
            success: function(JsonObject){
                // if(JsonObject['result'] == 1){
                //     // console.log('login success pats_ppd', JsonObject['username']);
                //     SignInAdmin(JsonObject['username']); //CLARK ADMIN LOGIN RAPIDX
                //     window.location = "dashboard";
                // }
                // else if(JsonObject['result'] == 2){
                //     window.location = "change_pass_view";
                // }
                // else{
                //     toastr.error('Login Failed!');

                //     if(JsonObject['error']['username'] === undefined){
                //         $("#txtSignInUsername").removeClass('is-invalid');
                //         $("#txtSignInUsername").attr('title', '');
                //     }
                //     else{
                //         $("#txtSignInUsername").addClass('is-invalid');
                //         $("#txtSignInUsername").attr('title', JsonObject['error']['username']);
                //     }

                //     if(JsonObject['error']['password'] === undefined){
                //         $("#txtSignInPass").removeClass('is-invalid');
                //         $("#txtSignInPass").attr('title', '');
                //     }
                //     else{
                //         $("#txtSignInPass").addClass('is-invalid');
                //         $("#txtSignInPass").attr('title', JsonObject['error']['password']);
                //     }
                // }

                // $("#iBtnSignInIcon").removeClass('fa fa-spinner fa-pulse');
                // $("#btnSignIn").removeAttr('disabled');
                // $("#iBtnSignInIcon").addClass('fa fa-check');
            },
            error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                $("#iBtnSignInIcon").removeClass('fa fa-spinner fa-pulse');
                $("#btnSignIn").removeAttr('disabled');
                $("#iBtnSignInIcon").addClass('fa fa-check');
            }
        });
    }

    function SignInAdmin(username){
        // let _token = "{{ csrf_token() }}";
        // let data = {
        //         'username' : username,
        //         '_token' : _token,
        //       }
        let _token = "{{ csrf_token() }}";
        let data = $.param({ _token, username});
        $.ajax({
            url: 'rapidx_sign_in_admin',
            method: 'post',
            data: data,
            dataType: "json",
            beforeSend: function(){
                $("#iBtnSignInIcon").addClass('fa fa-spinner fa-pulse');
                $("#btnSignIn").prop('disabled', 'disabled');
            },
            success: function(JsonObject){
                if(JsonObject['result'] == 1){
                    console.log('success login rapidx');
                    // $("#spanErrorUsername").text('');
                    // $("#spanErrorPassword").text('');
                    // window.location = "{{ route('dashboard') }}";
                    // alert(JSON.stringify(JsonObject));
                }
            }
        });
    }
  });

</script>
</body>
</html>
{{-- @endif --}}
