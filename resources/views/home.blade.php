@extends('layouts.app')

@section('content')
  <p class="text-center">{{ session()->get('secretNumber')->join('') }}</p>
  <home-component v-bind="{{ json_encode($props) }}" />
@endsection

@push('scripts')
<script src="{{ mix('js/home/home.js') }}"></script>
@endpush