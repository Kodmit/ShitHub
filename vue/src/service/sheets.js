import axios from "axios";

const sheetsService = {
  async getCells(spreadsheetId, sheetName) {
    const range = "A1:H";
    const response = await axios.get(
      `https://sheets.googleapis.com/v4/spreadsheets/${spreadsheetId}/values/${sheetName}!${range}`,
      {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("access_token"),
        },
      }
    );
    return response.data;
  },
};

export default sheetsService;
