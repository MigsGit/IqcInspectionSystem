
  {{-- @if(Auth::user()->is_password_changed == 0)
    <script type="text/javascript">
      window.location = "{{ url('change_pass_view') }}";
    </script>
  @endif

  @if(Auth::user()->status == 2)
    <script type="text/javascript">
      window.location = "{{ url('login') }}";
    </script>
  @endif --}}
  {{-- @if(Auth::user()->user_level_id != 1)
    <script type="text/javascript">
      window.location = "{{ url('dashboard') }}";
    </script>
  @endif --}}


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IQC | @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/png" href="{{ asset('public/images/favicon.ico') }}">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js" ></script> --}}

  @include('shared.css_links.css_links')

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  @include('shared.pages.header')

  @include('shared.pages.admin_nav')

  @yield('content_page')

  @include('shared.pages.footer')
</div>

@include('shared.js_links.js_links')
@yield('js_content')


@include('shared.pages.common')

</body>
</html>
<script>
  $(document).ready(function(){
      $('#btnLogout').click(function(){
          SignOut();
      });
  });
</script>
