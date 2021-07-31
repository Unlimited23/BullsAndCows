@extends('layouts.basic')

@section('body')
<body>
  <div id="app" class="app-content content">
    <div class="content-wrapper">
      <div class="content-body">
        <div class="content-header row">
        </div>
        @yield('content')
      </div>
    </div>
  </div>

  <script src="{{ mix('js/manifest.js') }}"></script>
  <script src="{{ mix('js/vendor.js') }}"></script>
  <script src="{{ mix('js/app.js') }}"></script>
  @stack('scripts')
</body>
@endsection
