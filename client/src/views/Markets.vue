<template>
  <v-col>
    <v-row justify="space-around">
      <v-col cols="3" class="text-center">
        <div class="subtitle-1">Market Cap</div>
        <div class="title" v-if="!isGlobalStatsLoading">
          <div v-if="'marketCap' in globalStats">
            {{ marketValueFormatter.format(globalStats.marketCap) }}
            <span
              :class="
                'subtitle-1 ' + percentColorClass(globalStats.marketCapChange)
              "
            >
              {{ globalStats.marketCapChange | formatPercent }}
            </span>
          </div>
          <div v-else>No data available</div>
        </div>
        <v-skeleton-loader class="mt-1" v-else type="heading" />
      </v-col>

      <v-col cols="3" class="text-center">
        <div class="subtitle-1">Volume</div>
        <div class="title" v-if="!isGlobalStatsLoading">
          <div v-if="'volume' in globalStats">
            {{ marketValueFormatter.format(globalStats.volume) }}
            <span
              :class="
                'subtitle-1 ' + percentColorClass(globalStats.volumeChange)
              "
            >
              {{ globalStats.volumeChange | formatPercent }}
            </span>
          </div>
          <div v-else>No data available</div>
        </div>
        <v-skeleton-loader class="mt-1" v-else type="heading" />
      </v-col>

      <v-col cols="3" class="text-center">
        <div class="subtitle-1">Bitcoin dominance</div>
        <div class="title" v-if="!isGlobalStatsLoading">
          <span v-if="'bitcoinDominance' in globalStats">
            {{ globalStats.bitcoinDominance }}%
          </span>
          <span v-else>No data available</span>
        </div>
        <v-skeleton-loader class="mt-1" v-else type="heading" />
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-data-table
        class="elevation-0 mt-4"
        :headers="headers"
        :items="coins"
        :items-per-page="perPage"
        disable-sort
        hide-default-footer
        :loading="isCoinsLoading"
      >
        <template v-slot:item.coin="{item}">
          <div class="d-flex">
            <div class="d-flex align-center">
              <v-img
                :src="`http://coinsight.fun${item.icon}`"
                width="2em"
                height="auto"
              />
            </div>
            <div class="ml-4">
              <div class="symbol">{{ item.symbol }}</div>
              <small class="name">{{ item.name }}</small>
            </div>
          </div>
        </template>

        <template v-slot:item.price="{item}">
          {{ priceFormatter.format(item.price) }}
        </template>

        <template v-slot:item.change24H="{item}">
          <span :class="percentColorClass(item.change24H)">
            {{ item.change24H | formatPercent }}
          </span>
        </template>

        <template v-slot:item.marketCap="{item}">
          {{ marketValueFormatter.format(item.marketCap) }}
        </template>

        <template v-slot:item.volume="{item}">
          {{ marketValueFormatter.format(item.volume) }}
        </template>
      </v-data-table>
      <v-row class="mt-2 d-flex justify-end">
        <v-btn
          fab
          color="surface"
          class="mr-1"
          :disabled="page === 1"
          @click="page--"
        >
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <v-btn
          fab
          color="surface"
          class="ml-1"
          :disabled="page === coinsPageCount"
          @click="page++"
        >
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </v-row>
    </v-row>
  </v-col>
</template>

<script>
import {globalStats, coins} from '../api/markets';

export default {
  name: 'Markets',

  async created() {
    this.fetchGlobalStats();
    this.fetchCoins();
  },

  mounted() {
    this.priceFormatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
    });
    this.marketValueFormatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 0,
    });
  },

  data() {
    return {
      priceFormatter: null,
      marketValueFormatter: null,
      isGlobalStatsLoading: false,
      isCoinsLoading: false,
      page: 1,
      perPage: 100,
      headers: [
        {text: '#', value: 'rank', width: '2%'},
        {text: 'Coin', value: 'coin', width: '25%'},
        {text: 'Price', value: 'price'},
        {text: 'Change (24h)', value: 'change24H'},
        {text: 'Market Cap', value: 'marketCap'},
        {text: 'Volume', value: 'volume'},
      ],
      globalStats: {},
      coins: [],
      coinsTotal: 0,
    };
  },

  watch: {
    page: function () {
      this.fetchCoins();
    },
  },

  methods: {
    async fetchGlobalStats() {
      this.isGlobalStatsLoading = true;
      try {
        let result = await globalStats();
        this.globalStats = result.data;
      } catch (e) {
        alert(e);
      }
      this.isGlobalStatsLoading = false;
    },

    async fetchCoins() {
      this.isCoinsLoading = true;
      try {
        let result = await coins({page: this.page, perPage: this.perPage});
        this.coins = result.data.coins;
        this.coinsTotal = result.meta.total;
      } catch (e) {
        alert(e);
      }
      this.isCoinsLoading = false;
    },

    percentColorClass(percent) {
      return (percent > 0 ? 'green' : 'red') + '--text text--lighten-1';
    },
  },

  computed: {
    coinsPageCount() {
      return Math.ceil(this.coinsTotal / this.perPage);
    },
  },

  filters: {
    formatPercent(percent) {
      return (percent > 0 ? '+' + percent : percent) + '%';
    },
  },
};
</script>

<style scoped lang="scss">
.symbol {
  font-family: NunitoSemiBold, sans-serif;
}
.name {
  color: var(--v-text-darken2);
}
</style>
