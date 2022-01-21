import axios from "axios";
import { relativeTimeRounding } from "moment";
const github = {
  isLogged() {
    return (
      null !== localStorage.getItem("github_access_token") &&
      undefined !== localStorage.getItem("github_access_token")
    );
  },
  async refreshToken() {
    const refreshToken = localStorage.getItem("github_refresh_token");
    const clientId = process.env.VUE_APP_CLIENT_ID;
    const clientSecret = process.env.VUE_APP_CLIENT_SECRET;

    const tokenUri = process.env.VUE_APP_TOKEN_URI;
    const response = await axios.post(
      `${tokenUri}?client_id=${clientId}&client_secret=${clientSecret}&grant_type=refresh_token&refresh_token=${refreshToken}`
    );

    const newToken = response.data;
    console.log("new token : ", newToken);
  },
  async getTokenFromCode(code) {
    const response = await axios.post(
      process.env.VUE_APP_BASE_URL + "o-auth/access_token?code=" + code
    );
    console.log("github response : ", response.data);

    const newToken = response.data;

    localStorage.setItem("github_access_token", newToken);
    localStorage.setItem("github_refresh_token", newToken["refresh_token"]);
  },
  async getProjects() {
    // project id = PN_kwDOBLNM584AAfjC
    const org = "Fogo-Capital";
    const response = await axios.get(
      `https://api.github.com/orgs/${org}/projects?per_page=20&page=1`,
      {
        headers: {
          Authorization: "Bearer " + this.getToken(),
          Accept: "application/vnd.github.v3+json",
        },
      }
    );

    return response.data;
  },
  async createAndConvertIssues(rows) {
    const columns = rows[0];
    let promises = [];
    rows
      .filter((row) => row[0] == "prêt à l'envoi")
      .map((row) => {
        let rowObject = {};
        columns.forEach((col, index) => {
          rowObject[col] = row[index];
        });
        return rowObject;
      })
      .forEach((issue) => {
        promises.push(this.createGithubIssue(issue));
      });

    return await Promise.all(promises);
  },
  async createGithubIssue(issue) {
    issue.labels = issue.labels ? issue.labels.split(",") : [];
    const response = await axios.post(
      `https://api.github.com/repos/Fogo-Capital/maorie-monolith/issues?` +
        localStorage.getItem("github_access_token"),
      {
        title: issue.titre,
        labels: issue.labels,
      },
      {
        headers: {
          Authorization: "Bearer " + this.getToken(),
          Accept: "application/vnd.github.v3+json",
        },
      }
    );
    this.linkIssueToProject(response.data.node_id, "PN_kwDOBLNM584AAfjC");
  },
  async getGithubIssue(project) {
    const response = await axios.get(
      `https://api.github.com/repos/${project}/issues`,
      {
        headers: {
          Authorization: "Bearer " + this.getToken(),
          Accept: "application/vnd.github.v3+json",
        },
      }
    );

    console.log(response.data);
  },
  async linkIssueToProject(issueID, projectID) {
    const payload = {
      query: `mutation {addProjectNextItem(input: {projectId: "${projectID}" contentId: "${issueID}"}) {projectNextItem {id}}}`,
    };

    const response = await axios.post(
      "https://api.github.com/graphql",
      payload,
      {
        headers: {
          Authorization: "Bearer " + this.getToken(),
        },
      }
    );

    console.log(response);
  },
  getToken() {
    const tokenInfos = localStorage.getItem("github_access_token");
    let token = tokenInfos.split("=")[1];
    token = token.split("&");
    token = token[0];

    return token;
  },
};

export default github;
