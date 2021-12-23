<template>
    <v-app>
        <v-data-table
            :headers="headers"
            :items="currencies"
            :items-per-page="5"
            class="elevation-1"
            :footer-props="{
              'items-per-page-text':'Ilość wierszy na stronie',
              'items-per-page-all-text':'Wszystkie',
              'page-text': ''
            }"
        >
        <template v-slot:ask="{ item }">
          <span>{{ item.toFixed(2) }}</span>
        </template>
      </v-data-table>
    </v-app>
</template>

<script>
  export default {
    props:['currencies'],
    data () {
      return {
        headers: [
          {
            text: 'Nazwa',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Kod', value: 'code' },
          { text: 'Średni kurs', value: 'mid' },
          { text: 'Sprzedaż', value: 'bid' },
          { text: 'Kupno', value: 'ask' },
        ],
      }
    },
  }
</script>
<style scoped>
* { margin: 20 !important; }
body { margin: 20px !important; }
</style>