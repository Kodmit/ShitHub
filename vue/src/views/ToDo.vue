<template>
  <div>
    <h2>Envoi d'issues sur GitHub</h2>
    <v-btn v-if="!isLogged" @click="redirectToAuth()"
      >S'authentifier avec google</v-btn
    >
    <v-btn v-if="!isLoggedGithub" @click="redirectToGithubAuth()"
      >S'authentifier avec github</v-btn
    >
    <div v-if="isLogged && isLoggedGithub">
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
      <div>
        <v-switch
          v-if="rowCount > 0"
          label="Preview des items"
          v-model="unfolded"
        ></v-switch>
        <ul v-show="unfolded">
          <li v-for="(cell, index) in issueCells" :key="index">
            {{ cell[1] }}
          </li>
        </ul>
      </div>
      <v-autocomplete
        v-if="0 !== orgaProjects.length"
        label="Nom du projet"
        :items="orgaProjects"
        :item-text="(item) => item.properties.title"
        :item-value="(item) => item.properties.title"
        v-model="selectedProject"
      >
      </v-autocomplete>
      <v-btn
        :disabled="rowCount === 0 || null === selectedSheet"
        @click="sendToGithubProject()"
        >Valider l'importation</v-btn
      >
      <v-snackbar v-model="snackbar.display">
        {{ snackbar.text }}

        <template v-slot:action="{ attrs }">
          <v-btn
            color="red"
            text
            v-bind="attrs"
            @click="snackbar.display = false"
          >
            Fermer
          </v-btn>
        </template>
      </v-snackbar>
    </div>
  </div>
</template>

<script>
import googleAuth from "@/service/googleAuth";
import axios from "axios";
import sheetService from "@/service/sheets";
import github from "@/service/github";
export default {
  data() {
    return {
      rowCount: 0,
      isLogged: false,
      isLoggedGithub: false,
      spreadsheets: [],
      selectedSheet: null,
      selectedSheetDetail: {},
      selectedSubsheetName: null,
      orgaProjects: [],
      selectedProject: null,
      cells: [],
      unfolded: false,
      snackbar: {
        display: false,
        text: "",
      },
    };
  },
  methods: {
    redirectToAuth() {
      window.location.href = `${process.env.VUE_APP_AUTH_URL}?access_type=offline&scope=https%3A//www.googleapis.com/auth/spreadsheets https%3A//www.googleapis.com/auth/drive.readonly&include_granted_scopes=true&response_type=code&state=state_parameter_passthrough_value&redirect_uri=${process.env.VUE_APP_URL}/g-auth/redirect-uri&client_id=${process.env.VUE_APP_CLIENT_ID}`;
    },
    redirectToGithubAuth() {
      window.location.href = `${process.env.VUE_APP_GITHUB_AUTH_URL}?scope=user,workflow,codespace,admin:org,repo,notifications&client_id=${process.env.VUE_APP_GITHUB_CLIENT_ID}`;
    },
    refreshViewData() {
      if (false === this.isLogged) return setTimeout(this.refreshViewData, 500);

      this.getSpreadSheets();
      if (this.selectedSheet !== null) {
        this.getSpreadSheet(this.selectedSheet);
        this.triggerRowCount();
      }

      if (false === this.isLoggedGithub)
        return setTimeout(this.refreshViewData, 500);
      this.getOrgaProjects();
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
      this.cells = await sheetService.getCells(
        this.selectedSheet,
        this.selectedSubsheetName
      );

      this.rowCount =
        this.cells.values?.filter((row) => row[0] === "prêt à l'envoi")
          .length ?? 0;
    },
    async sendToGithubProject() {
      await github.createAndConvertIssues(this.cells.values);
      this.snackbar.text = "Issues créées avec succès !";
      this.snackbar.display = true;

      sheetService.updateCellsInfos(
        this.cells,
        this.selectedSheet,
        this.selectedSubsheetName
      );

      this.selectedSheet = null;
      this.refreshViewData();
    },
    async getOrgaProjects() {
      this.orgaProjects = await github.getProjects();
    },
  },
  mounted() {
    setTimeout(() => {
      this.isLogged = googleAuth.isLogged();
      this.isLoggedGithub = github.isLogged();
    }, 1000);
    this.refreshViewData();
  },
  computed: {
    issueCells() {
      if (!this.cells) return [];

      if (!this.cells.values.length) return [];
      return this.cells.values.filter((row) => row[0] === "prêt à l'envoi");
    },
  },
};
</script>

<style></style>
