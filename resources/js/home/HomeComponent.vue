<template>
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Playground</div>

                  <div class="card-body">
                      <div v-if="match" class="alert alert-success">
                        You have successfully guessed the secret number!
                      </div>
                      <div v-if="error" class="alert alert-danger">
                        {{ error }}
                      </div>
                      <div class="input-group mb-3">
                        <input 
                          type="text"
                          id="guess"
                          name="guess"
                          ref="guess"
                          class="form-control"
                          maxlength="4"
                          pattern="^(?!.*(.).*\1)\d{4}$"
                          onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                          placeholder="Insert a four digit number..."
                          required
                        />
                        <button 
                          type="button"
                          class="btn btn-outline-secondary btn-lg"
                          @click="guess()"
                        >
                          Guess!
                        </button>
                      </div>
                    <div class="border rounded p-3">
                        <p>
                          <span class="text-success ml-1 mr-1">{{ cows }}</span> cows
                          <span class="text-danger ml-1 mr-1">{{ bulls }}</span> bulls
                        </p>
                        <p><span class="text-right">Guesses: {{ guesses }}</span></p>
                    </div>
                    <numbers-table :numbers="numbers" />
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
  import type from '@/helpers/type';
  import CsrfToken from '@/helpers/CsrfToken';
  import NumbersTable from './NumbersTable';
  
  export default {
    components: {
      CsrfToken,
      NumbersTable,
    },
    data: function() {
      return {
        cows: 0,
        bulls: 0,
        guesses: 0,
        numbers: {},
        match: false,
        error: '',
      };
    },
    methods: {
      async guess() {
        const resp = (await axios.post('/guess', {guess: this.$refs.guess.value})).data;
        this.cows = resp.cows;
        this.bulls = resp.bulls;
        this.guesses = resp.guesses;
        this.numbers = resp.numbers;
        this.match = resp.match;
        this.error = resp.error ?? '';
      }
    },
  }
</script>
