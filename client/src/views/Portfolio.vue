<template>
  <v-col>
    <v-overlay
      absolute
      :value="isPortfoliosLoading || isPortfolioReportLoading"
    >
      <v-progress-circular
        size="60"
        indeterminate
        color="primary"
      ></v-progress-circular>
    </v-overlay>
    <div v-if="portfolios.length !== 0">
      <v-row justify="space-between">
        <v-col>{{ currentPortfolio.name }}</v-col>
        <v-col>{{ currentReport.totalValue }}, {{ currentReport.totalValueChange }}</v-col>
      </v-row>
      <v-row></v-row>
      <v-row></v-row>
    </div>
    <div v-else>
      <v-row align="center" justify="center">
        <v-btn @click="createPortfolio">Create New Portfolio</v-btn>
      </v-row>
    </div>
  </v-col>
</template>

<script>
import {getUserPortfolios, getPortfolioReport} from '../api/portfolio';

export default {
  name: 'Portfolio',

  data() {
    return {
      isPortfoliosLoading: false,
      isPortfolioReportLoading: false,
      portfolios: [],
      currentPortfolio: {},
      reports: [],
      page: 1,
      perPage: 15,
      total: 0,
    };
  },

  created() {
    this.getUserPortfolios();
  },

  methods: {
    async getUserPortfolios() {
      this.isPortfoliosLoading = true;
      try {
        let result = await getUserPortfolios({
          page: this.page,
          perPage: this.perPage,
        });
        this.portfolios = result.data.portfolios;
        this.total = result.meta.total;
        this.page++;

        if (result.data.portfolios.length !== 0) {
          this.currentPortfolio = result.data.portfolios[0];
          this.getPortfolioReport(this.currentPortfolio.id);
        }
      } catch (e) {
        alert(e);
      }

      this.isPortfoliosLoading = false;
    },

    async getPortfolioReport(id) {
      this.isPortfolioReportLoading = true;
      try {
        let result = await getPortfolioReport(id);
        this.reports.push([id, result.data]);
      } catch (e) {
        alert(e);
      }
      this.isPortfolioReportLoading = false;
    },

    createPortfolio() {
      alert('creating');
    },
  },

  computed: {
    currentReport() {
      return this.reports[this.currentPortfolio.id];
    }
  }
};
</script>

<style scoped></style>
