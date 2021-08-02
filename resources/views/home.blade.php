@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Playground</div>

                <div class="card-body">
                  <form action="/guess" method="post">
                    @csrf
                    <div class="input-group mb-3">
                      <input 
                        type="text"
                        id="guess"
                        name="guess"
                        class="form-control"
                        maxlength="4"
                        pattern="^(?!.*(.).*\1)\d{4}$"
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                        placeholder="Insert a four digit number..."
                        required
                      />
                      <button type="submit" class="btn btn-outline-secondary btn-lg">
                        Guess!
                      </button>
                    </div>
                  </form>
                  <div class="border rounded p-3">
                    @if(isset($cows) && isset($bulls))
                      <span class="text-right">Guesses {{ $guesses }}</span>
                      <p>You have {{ $cows }} cows.</p>
                      <p>and {{ $bulls }} bulls.</p>
                    @endif
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
