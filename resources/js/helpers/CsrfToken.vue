<template>
  <input type="hidden" name="_token" :value="token">
</template>

<script>
  export default {
      name: 'CsrfToken',
      data() {
        return {
          token: document.getElementsByName('csrf-token')[0].getAttribute('content')
        }
      }
  }
</script>
