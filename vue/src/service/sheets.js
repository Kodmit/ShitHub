import axios from "axios";

const sheetsService = {
  async getCells(spreadsheetId, sheetName) {
    const range = "A1:L";
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
  async updateCellsInfos(cells, spreadsheetId, sheetName) {
    const updatedRange = sheetName + '!A1:L'
    const valueRange = {
      range: updatedRange,
      majorDimension: cells.majorDimension,
      values: cells.values.map((row) => {
        if (row[0] === "prêt à l'envoi") row[0] = "envoyé";
        return row;
      }),
    };
    
    const payload = {
      "data": [
        valueRange
      ],
      "includeValuesInResponse": false,
      "valueInputOption": "USER_ENTERED"
    }

    const response = await axios.post(`https://sheets.googleapis.com/v4/spreadsheets/${spreadsheetId}/values:batchUpdate`, payload, {
      headers: {
        Authorization: 'Bearer ' + localStorage.getItem("access_token")
      }
    })
  },
};

export default sheetsService;
