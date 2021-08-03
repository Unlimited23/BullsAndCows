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

    if (collect(session()->get('numbers'))->has($validated['guess'])) {
      return response()->json(['error' => 'You have already tried this number!']);
    }
    
    [$cows, $bulls, $guesses] = [...$this->findBullsAndCows($validated, $secretNumber, $cows, $bulls)];
    $numbers = $this->storeNumbers($validated, $cows, $bulls);
    $match = $this->checkForMatch($bulls, $match);
    
    
    return response()->json(compact('cows', 'bulls', 'guesses', 'numbers', 'match'));
  }
  
  private function generateSecretNumber(): iterable
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
  
  private function findBullsAndCows($validated, $secretNumber, $cows, $bulls): array
  {
    $number = clone $secretNumber;

    if (!session()->has('guesses')) {
      $guesses = 1;
      session()->put('guesses', $guesses);
    } else {
      $guesses = session()->get('guesses');

      for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < 4; $j++) {
          if ($secretNumber->get($i) == $validated['guess'][$j]) {
            $cows++;
            if ($i == $j) {
              $bulls++;
            }
            $secretNumber->pull($i);
            $secretNumber->put($i, -1);
          }
        }
      }
      
      session()->put('guesses', ++$guesses);
    }
    
    return [$cows, $bulls, $guesses];
  }
  
  private function checkForMatch($bulls, $match): bool
  {
    if ($bulls == 4) {
      $match = true;
      session()->flush();
    }
    
    return $match;
  }
  
  private function storeNumbers($validated, $cows, $bulls): array
  {
    $numbers = session()->get('numbers');
    $numbers = collect($numbers)->put($validated['guess'], compact('cows', 'bulls'))->toArray();
    session()->put('numbers', $numbers);
    
    return $numbers;
  }
}
