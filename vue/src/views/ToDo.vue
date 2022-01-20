<template>
  <div>
    <v-btn v-if="!isLogged" @click="redirectToAuth()"
      >S'authentifier avec google</v-btn
    >
    <v-btn @click="refreshViewData"><v-icon>mdi-refresh</v-icon></v-btn>
    <v-autocomplete
      label="Nom du classeur"
      :items="spreadsheets"
      item-text="name"
      item-value="id"
      v-model="selectedSheet"
      @change="getSpreadSheet(selectedSheet)"
    >
    </v-autocomplete>
    <v-autocomplete
      v-if="undefined !== selectedSheetDetail.sheets"
      label="Nom de la feuille"
      :items="selectedSheetDetail.sheets"
      :item-text="(item) => item.properties.title"
      :item-value="(item) => item.properties.title"
      v-model="selectedSubsheetName"
      @change="triggerRowCount()"
    >
    </v-autocomplete>
    <p v-if="rowCount !== 0">Lignes à importer : {{ rowCount }}</p>
    <p v-else-if="null != selectedSheet">Aucune ligne à importer</p>
  </div>
</template>

<script>
import googleAuth from "@/service/googleAuth";
import axios from "axios";
import sheetService from "@/service/sheets";
export default {
  data() {
    return {
      rowCount: 0,
      isLogged: true,
      spreadsheets: [],
      selectedSheet: null,
      selectedSheetDetail: {},
      selectedSubsheetName: null,
    };
  },
  methods: {
    redirectToAuth() {
      window.location.href = `${process.env.VUE_APP_AUTH_URL}?access_type=offline&scope=https%3A//www.googleapis.com/auth/spreadsheets https%3A//www.googleapis.com/auth/drive.readonly&include_granted_scopes=true&response_type=code&state=state_parameter_passthrough_value&redirect_uri=${process.env.VUE_APP_URL}/g-auth/redirect-uri&client_id=${process.env.VUE_APP_CLIENT_ID}`;
    },
    refreshViewData() {
      if (false === this.isLogged) return setTimeout(this.refreshViewData, 500);

      this.getSpreadSheets();
      if (this.selectedSheet !== null) {
        this.getSpreadSheet(this.selectedSheet);
        this.triggerRowCount();
      }
    },
    async getSpreadSheets() {
      const accessToken = localStorage.getItem("access_token");
      const response = await axios.get(
        "https://www.googleapis.com/drive/v3/files?q=mimeType='application/vnd.google-apps.spreadsheet'",
        {
          headers: {
            Authorization: "Bearer " + accessToken,
          },
        }
      );

      this.spreadsheets = response.data.files;
    },
    async getSpreadSheet(spreadsheetId) {
      const accessToken = localStorage.getItem("access_token");

      const response = await axios.get(
        `https://sheets.googleapis.com/v4/spreadsheets/${spreadsheetId}`,
        {
          headers: {
            Authorization: "Bearer " + accessToken,
          },
        }
      );

      this.selectedSheetDetail = response.data;
    },
    async triggerRowCount() {
      const cells = await sheetService.getCells(
        this.selectedSheet,
        this.selectedSubsheetName
      );

      this.rowCount =
        cells.values?.filter((row) => row[0] === "prêt à l'envoi").length ?? 0;
    },
  },
  mounted() {
    setTimeout(() => {
      this.isLogged = googleAuth.isLogged();
    }, 1000);
    this.refreshViewData();
  },
};
</script>

<style></style>
