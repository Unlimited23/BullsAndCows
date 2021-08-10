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

    $nums = session()->has('numbers') ? session()->get('numbers') : [];

    return view('home', [
      'props' => compact('secretNumber', 'nums')
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
    $match = $this->checkForMatch($bulls, $match, $secretNumber);

    return response()->json(compact('cows', 'bulls', 'guesses', 'numbers', 'match'));
  }
  
  public function topTen()
  {
    return view('top-ten', [
      'records' => session()->get('topTen') ?? []
    ]);
  }
  
  private function generateSecretNumber(): iterable
  {
    $secretNumber = collect();
    while ($secretNumber->count() < 4) {
        $randomNumber = mt_rand(0, 9);
        $count = $secretNumber->count();
        $notEvenIndex = ($count != 0 && $count != 2);

        if ($secretNumber->isNotEmpty() && $secretNumber->search($randomNumber) === false) {
          if (($randomNumber == 4 || $randomNumber == 5) && $notEvenIndex) {
            $secretNumber->push($randomNumber);
          } else if ($randomNumber != 4 && $randomNumber != 5) {
            $secretNumber->push($randomNumber);
          }
        } else if($secretNumber->isEmpty()) {
          if (($randomNumber == 4 || $randomNumber == 5) && $notEvenIndex) {
            $secretNumber->push($randomNumber);
          } else if ($randomNumber != 4 && $randomNumber != 5) {
            $secretNumber->push($randomNumber);
          }
        }
    }

    $this->checkForOneAndEight($secretNumber);

    return collect($secretNumber)->values();
  }
  
  private function findBullsAndCows($validated, $secretNumber, $cows, $bulls): array
  {
    $number = clone $secretNumber;

    if (!session()->has('guesses')) {
      session()->put('guesses', 0);
    }

    $guesses = session()->get('guesses');

    for ($i = 0; $i < 4; $i++) {
      for ($j = 0; $j < 4; $j++) {
        if ($number->get($i) == $validated['guess'][$j]) {
          $cows++;
          if ($i == $j) {
            $bulls++;
          }
          $number->pull($i);
          $number->put($i, -1);
        }
      }
    }

    session()->put('guesses', ++$guesses);

    return [$cows, $bulls, $guesses];
  }
  
  private function checkForMatch($bulls, $match, $secretNumber): bool
  {
    if ($bulls == 4) {
      $match = true;
      $top10 = collect(session()->get('topTen') ?? [])->sort();
      $guesses = session()->get('guesses');
      $lastGuess = collect($top10)->values()->last() ?? PHP_INT_MAX;
      
      session()->flush();
      
      if ($guesses <= $lastGuess || $top10->count() < 10) {
        $top10->prepend($guesses, $secretNumber->join(''))->sort();
      }
      
      if ($top10->count() > 10) {
        $top10->pop();
      }

      session()->put('topTen', $top10->sort()->toArray());
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
  
  private function checkForOneAndEight($secretNumber)
  {
    $idxOne = $secretNumber->search(1);
    $idxEight = $secretNumber->search(8);

    // Check if one and eight exist in the number and if they're not already next to each other
    if ($idxOne !== false && $idxEight !== false &&
      ((abs($idxOne - $idxEight) !== 1) || (abs($idxEight - $idxOne) !== 1))
    ) {
      foreach ($secretNumber as $k => $num) {
        if ($k === $idxOne) {
          $key = $idxOne < 3 ? $idxOne + 1 : $idxOne - 1;
          // retrieve the sibling number
          $next = $secretNumber->get($key);
          // replace it with the "eight"
          $secretNumber->put($key, $secretNumber->get($idxEight));
          // remove the "eight"
          $secretNumber->forget($idxEight);
          // restore the sibling number
          $secretNumber->push($next);
          break;
        } else if ($k == $idxEight) {
          $key = $idxEight < 3 ? $idxEight + 1 : $idxEight - 1;
          // retrieve the sibling number
          $next = $secretNumber->get($key);
          // replace it with the "one"
          $secretNumber->put($key, $secretNumber->get($idxOne));
          // remove the "one"
          $secretNumber->forget($idxOne);
          // restore the sibling number
          $secretNumber->push($next);
          break;
        }
      }
    }
  }
}
