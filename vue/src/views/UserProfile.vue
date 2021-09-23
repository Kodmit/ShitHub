<template>
  <div class="home">
    <h3>Profil</h3>

  

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UserProfile',
  data() {
    return {
      user: {}
    }
  },
  mounted() {
    axios.get('/users/' + this.$route.params.id).then((response) => {
      this.user = response.data;
    }).catch(() => {
      console.log('error');
    });
  }
}
</script>
