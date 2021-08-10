<template>
  <div class="table-responsive" v-if="!emptyNumbers">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Number</th>
          <th>Cows</th>
          <th>Bulls</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(guess, number) in numbers" :key="number">
          <td>{{ number }}</td>
          <td>{{ guess.cows }}</td>
          <td>{{ guess.bulls }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import type from '@/helpers/type';
  
  export default {
    props: {
      numbers: type.object(),
    },
    computed: {
      emptyNumbers() {
        return Object.keys(this.numbers).length == 0;
      }
    }
  }
  
</script>