<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuessRequest;

class HomeController extends Controller
{
  public function index()
  {
    if (!session()->has('secretNumber')) {
      $secretNumber = $this->generateSecretNumber();
      session()->put('secretNumber', $secretNumber);
    } else {
      $secretNumber = session()->get('secretNumber');
    }
    return view('home', [
      'props' => compact('secretNumber')
    ]);
  }
  
  public function guess(GuessRequest $request)
  {
    $cows = 0;
    $bulls = 0;
    $match = false;
    $secretNumber = session()->get('secretNumber');
    $validated = $request->validated();
    
    if (!session()->has('guesses')) {
      session()->put('guesses', 0);
      $guesses = 0;
    } else {
      $guesses = session()->get('guesses');

      for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < 4; $j++) {
          if ($validated['guess'][$i] == $secretNumber->get($j)) {
            $cows++;
            if ($i == $j) {
              $bulls++;
            }
          }
        }
      }
      
      session()->put('guesses', ++$guesses);
    }

    if ($bulls == 4) {
      $match = true;
      session()->put('secretNumber',  $this->generateSecretNumber());
      session()->put('guesses', 0);
    }
        
    return view('home', ['props' => compact('cows', 'bulls', 'guesses')]);
  }
  
  public function generateSecretNumber()
  {
    $secretNumber = collect();
    while ($secretNumber->count() < 4) {
        $randomNumber = mt_rand(0, 9);
        if ($secretNumber->search($randomNumber) === false || $secretNumber->count() == 0) {
            $secretNumber->push($randomNumber);
        }
    }
    return $secretNumber;
  }
}
