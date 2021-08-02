@extends('layouts.app')

@section('content')
  <home-component v-bind="{{ json_encode($props) }}" />
@endsection

@push('scripts')
<script src="{{ mix('js/home/home.js') }}"></script>
@endpush