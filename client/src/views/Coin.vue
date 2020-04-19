<template>
  <v-col>
    <v-overlay
      absolute
      :value="
        isProfileLoading || isMarketDataLoading || isHistoricalDataLoading
      "
    >
      <v-progress-circular
        size="60"
        indeterminate
        color="primary"
      ></v-progress-circular>
    </v-overlay>
    <v-row>
      <v-col v-if="!isProfileLoading">
        <div class="d-flex align-center">
          <div>
            <v-img :src="profile.icon" width="4em" height="4em" />
          </div>
          <div class="ml-3">
            <div class="display-1">
              {{ profile.name }} ({{ profile.symbol.toUpperCase() }})
            </div>
            <div class="muted">{{ profile.tagline }}</div>
          </div>
        </div>
      </v-col>
      <v-col v-if="!isMarketDataLoading">
        <div class="d-flex justify-space-between">
          <div class="price">
            {{ marketData.price | formatMarketValue }}
          </div>
          <div
            class="d-flex justify-center flex-column"
            v-for="(priceChange, index) in priceChanges"
            :key="index"
          >
            <div class="text-center">{{ priceChange.title }}</div>
            <div :class="percentColorClass(priceChange.value)">
              {{ priceChange.value | formatPercent }}
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
    <v-row class="mt-4" v-if="!isMarketDataLoading && !isProfileLoading">
      <v-col
        cols="3"
        class="text-center"
        v-for="(marketDataItem, index) in marketDataCards"
        :key="index"
      >
        <v-card elevation="0">
          <v-card-text class="subtitle-1">
            <div>{{ marketDataItem.title }}</div>
            <div v-if="!isMarketDataLoading">
              <div v-if="marketDataItem.value">
                {{ marketDataItem.value }}
                <span
                  v-if="marketDataItem.percentChange"
                  :class="percentColorClass(marketDataItem.percentChange)"
                >
                  {{ marketDataItem.percentChange | formatPercent }}
                </span>
              </div>
              <div v-else>No data available</div>
            </div>
            <v-skeleton-loader class="mt-1" v-else type="heading" />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row justify="end" class="mt-4 px-3">
      <v-btn
        class="mx-1"
        :input-value="period === value"
        small
        v-for="(value, index) in periods"
        :key="index"
        @click="period = value"
        color="surface"
      >
        {{ value }}
      </v-btn>
    </v-row>
    <v-row>
      <v-col>
        <div id="chart-container"></div>
      </v-col>
    </v-row>
    <v-row v-if="!isProfileLoading">
      <v-col cols="7">
        <h2>About {{ profile.name }}</h2>
        <p class="mt-2">{{ profile.description }}</p>
        <div>
          <h2>Links</h2>
          <div class="d-flex flex-wrap space-between pt-3">
            <v-btn
              small
              outlined
              :href="link.link"
              target="_blank"
              v-for="(link, index) in links"
              :key="index"
              color="text"
              class="mb-3 mr-3"
            >
              <v-icon small>{{ link.icon }}</v-icon>
              <span class="ml-1">{{ link.title }}</span>
            </v-btn>
          </div>
        </div>
      </v-col>
      <v-col>
        <h2>Technical details</h2>
        <v-simple-table>
          <template v-slot:default>
            <tbody>
              <tr>
                <td>Token Type</td>
                <td>
                  {{ profile.type }}
                </td>
              </tr>
              <tr>
                <td>Genesis Date</td>
                <td>
                  {{ profile.genesisDate | prettifyDate }}
                </td>
              </tr>
              <tr>
                <td>Hashing Algorithm</td>
                <td>{{ profile.hashingAlgorithm }}</td>
              </tr>
              <tr>
                <td>Consensus Mechanism</td>
                <td>{{ profile.consensusMechanism }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
  </v-col>
</template>

<script>
import {stockChart} from 'highcharts/highstock';
import {profile, marketData, historicalData} from '../api/coin';
import {formatMarketValue, formatPercent, prettifyDate} from '../filters';

export default {
  name: 'Coin',

  data() {
    return {
      isProfileLoading: false,
      isMarketDataLoading: false,
      isHistoricalDataLoading: false,
      profile: {},
      marketData: {},
      historicalData: [],
      periods: ['1d', '1w', '1m', '6m', '1y', 'all'],
      period: '1d',
      linkIconMapper: {
        twitter: 'mdi-twitter',
        telegram: 'mdi-telegram',
        reddit: 'mdi-reddit',
        github: 'mdi-github',
        website: 'mdi-web',
        whitepaper: 'mdi-file-document',
        explorer: 'mdi-compass',
      },
    };
  },

  created() {
    this.fetchCoinProfile();
    this.fetchCoinMarketData();
    this.fetchCoinHistoricalData();
  },

  watch: {
    period() {
      this.fetchCoinHistoricalData();
    },

    historicalData() {
      this.drawChart();
    },
  },

  methods: {
    async fetchCoinProfile() {
      this.isProfileLoading = true;
      try {
        let result = await profile(this.$route.params.id);
        this.profile = result.data;
      } catch (e) {
        alert(e);
      }
      this.isProfileLoading = false;
    },

    async fetchCoinMarketData() {
      this.isMarketDataLoading = true;
      try {
        let result = await marketData(this.$route.params.id);
        this.marketData = result.data;
      } catch (e) {
        alert(e);
      }
      this.isMarketDataLoading = false;
    },

    async fetchCoinHistoricalData() {
      this.isHistoricalDataLoading = true;
      try {
        let result = await historicalData(this.$route.params.id, {
          period: this.period,
        });
        this.historicalData = result.data.historicalData;
      } catch (e) {
        alert(e);
      }
      this.isHistoricalDataLoading = false;
    },

    drawChart() {
      stockChart('chart-container', {
        chart: {
          backgroundColor: 'transparent',
        },

        credits: {
          enabled: false,
        },

        navigator: {
          enabled: false,
        },

        scrollbar: {
          enabled: false,
        },

        plotOptions: {
          area: {
            lineWidth: 3,
          },
        },

        rangeSelector: {
          enabled: false,
        },

        series: [
          {
            name: 'Price',
            data: this.chartData.prices,
            type: 'area',
            index: 1,
            tooltip: {
              valueDecimals: 2,
            },
            color: {
              linearGradient: {x1: 0, x2: 1, y1: 0, y2: 0},
              stops: [
                [0, '#f869d5'],
                [1, '#5650de'],
              ],
            },
            fillColor: {
              linearGradient: {x1: 0, x2: 1, y1: 0, y2: 0},
              stops: [
                [0, 'rgba(248,105,213,0.1)'],
                [1, 'rgba(86,80,222,0.1)'],
              ],
            },
          },
          {
            name: 'Volume',
            data: this.chartData.volumes,
            type: 'column',
            yAxis: 1,
            index: 0,
            color: '#313051',
          },
        ],

        tooltip: {
          backgroundColor: '#313051',
          borderWidth: 0,
          hideDelay: 50,
          shared: true,
          followPointer: true,
          split: false,
          style: {
            color: '#dcdbf2',
          },
          valuePrefix: '$',
        },

        xAxis: {
          crosshair: {
            color: '#4e4e7c',
          },
          lineColor: 'transparent',
          tickColor: 'transparent',
          labels: {
            style: {
              color: '#dcdbf2',
            },
          },
        },

        yAxis: [
          {
            labels: {
              format: '${value}',
              style: {
                color: '#dcdbf2',
              },
            },
            gridLineWidth: 1,
            gridLineColor: '#282941',
            min: this.lowestPriceForPeriod,
            max: this.highestPriceForPeriod,
          },
          {
            labels: {
              align: 'left',
            },
            height: '20%',
            top: '80%',
            offset: 0,
            visible: false,
            gridLineWidth: 0,
          },
        ],
      });
    },

    formatSupply(supply) {
      return (
        supply.toLocaleString('en-US') + ' ' + this.profile.symbol.toUpperCase()
      );
    },

    percentColorClass(percent) {
      return (percent > 0 ? 'green' : 'red') + '--text text--lighten-1';
    },
  },

  computed: {
    chartData() {
      let data = {prices: [], volumes: []};

      for (let i = 0; i < this.historicalData.length - 1; i++) {
        data.prices.push([
          this.historicalData[i].timestamp,
          this.historicalData[i].price,
        ]);
        data.volumes.push([
          this.historicalData[i].timestamp,
          this.historicalData[i].volume,
        ]);
      }

      return data;
    },

    lowestPriceForPeriod() {
      return this.historicalData.reduce(
        (min, item) => (item.price < min ? item.price : min),
        this.historicalData[0].price,
      );
    },

    highestPriceForPeriod() {
      return this.historicalData.reduce(
        (max, item) => (item.price > max ? item.price : max),
        this.historicalData[0].price,
      );
    },

    marketDataCards() {
      return [
        {
          title: 'Market Cap',
          value: formatMarketValue(this.marketData.marketCap),
          percentChange: this.marketData.marketCapChange24H,
        },
        {
          title: 'Volume (24h)',
          value: formatMarketValue(this.marketData.volume),
          percentChange: this.marketData.volumeChange24H,
        },
        {
          title: 'Circulating Supply',
          value: this.formatSupply(this.marketData.circulatingSupply),
        },
        {
          title: 'Max Supply',
          value: this.formatSupply(this.marketData.maxSupply),
        },
      ];
    },

    priceChanges() {
      return [
        {
          title: '1H',
          value: this.marketData.priceChange1H,
        },
        {
          title: '1D',
          value: this.marketData.priceChange24H,
        },
        {
          title: '1W',
          value: this.marketData.priceChange7D,
        },
        {
          title: '1M',
          value: this.marketData.priceChange30D,
        },
        {
          title: '1Y',
          value: this.marketData.priceChange1Y,
        },
      ];
    },

    links() {
      return this.profile.links.map(link => {
        let icon;
        if (link.type.toLowerCase() in this.linkIconMapper) {
          icon = this.linkIconMapper[link.type.toLowerCase()];
        } else {
          icon = this.linkIconMapper.website;
        }

        return {
          title: link.type,
          link: link.link,
          icon: icon,
        };
      });
    },
  },

  filters: {
    formatPercent(percent) {
      return formatPercent(percent);
    },

    formatMarketValue(value) {
      return formatMarketValue(value);
    },

    prettifyDate(date) {
      return prettifyDate(date);
    },
  },
};
</script>

<style scoped lang="scss">
#chart-container {
  width: 100%;
}
.muted {
  color: var(--v-text-darken2);
}
.price {
  font-size: 2rem;
}
</style>
