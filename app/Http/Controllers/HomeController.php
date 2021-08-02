<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    return view('home', compact('secretNumber'));
  }
  
  public function guess()
  {
    $cows = 0;
    $bulls = 0;
    $match = false;
    
    if (!session()->has('guesses')) {
      session()->put('guesses', 0);
      $guesses = 0;
    } else {
      $guesses = session()->get('guesses');

      session()->put('guesses', ++$guesses);
    }
        
    return view('home', compact('cows', 'bulls', 'guesses'));
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
