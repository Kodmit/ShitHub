<template>
  <v-card
    max-width="300"
  >
    <v-img
      src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
      height="180px"
    ></v-img>

    <v-card-title>
      Marco Polo
    </v-card-title>

    <v-card-subtitle>
      GigaFit Compiegne
    </v-card-subtitle>

    <v-card-text>
      <v-chip-group
        v-model="selection"
        active-class="deep-purple accent-3 white--text"
        column
      >
        <v-chip>Lundi 18h</v-chip>

        <v-chip>Mercredi 16h</v-chip>

        <v-chip>Vendredi 17h</v-chip>

        <v-chip>Dimanche 17h</v-chip>
      </v-chip-group>
    </v-card-text>
    <v-card-actions>
      <v-btn
        color="orange lighten-2"
        text
      >
        Voir le profil
      </v-btn>

      <v-spacer></v-spacer>

      <v-btn
        icon
        @click="show = !show"
      >
        <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
      </v-btn>
    </v-card-actions>

    <v-expand-transition>
      <div v-show="show">
        <v-divider></v-divider>

        <v-card-text>
          I'm a thing. But, like most politicians, he promised more than he could deliver. You won't have time for sleeping, soldier, not with all the bed making you'll be doing. Then we'll go with that data file! Hey, you add a one and two zeros to that or we walk! You're going to do his laundry? I've got to find a way to escape.
        </v-card-text>
      </div>
    </v-expand-transition>
  </v-card>
</template>

<script>
  export default {
    name: 'UserCard',
    props: {
        user: {
            type: Object,
            required: false
        }
    },
    data: () => ({
        show: false
    })
  }
</script>
