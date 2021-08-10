@extends('layouts.app')

@section('content')
  @forelse($records as $number => $guesses)
    <p class="text-center">{{ ($loop->index + 1) . '. ' . $number . ' - ' . $guesses }}</p>
  @empty
    <p class="text-center"><strong>You don't have any guesses yet!<strong></p>
  @endforelse
@endsection
