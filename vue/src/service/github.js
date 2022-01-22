import axios from "axios";

const PROJECT_NODE_ID='PN_kwDOBLNM584AAfjC'
const PRIORITY_FIELD_ID='MDE2OlByb2plY3ROZXh0RmllbGQxMjA5Nzkx'

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
  },
  async getTokenFromCode(code) {
    const response = await axios.post(
      process.env.VUE_APP_BASE_URL + "o-auth/access_token?code=" + code
    );

    const newToken = response.data;

    localStorage.setItem("github_access_token", newToken);
    localStorage.setItem("github_refresh_token", newToken["refresh_token"]);
  },
  async getProjects() {
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
    const issueRows = rows
      .filter((row) => row[0] == "prêt à l'envoi")
      .map((row, rowIndex) => {
        let rowObject = {};
        columns.forEach((col, index) => {
          rowObject[col] = row[index];
        });
        rowObject["index"] = rowIndex;
        return rowObject;
      })
      
      issueRows.forEach((issue) => {
        promises.push(
          this.createGithubIssue(issue).then((issueLink) => {
            issue.link = issueLink;
            return issue;
          })
        );
      });

    await Promise.all(promises);
    issueRows.forEach((issueRow) => {
      rows[issueRow.index + 1][rows[issueRow.index].length - 1] = issueRow.link;
    });
  },
  async createGithubIssue(issue) {
    issue.labels = issue.labels ? issue.labels.split(",") : [];
    console.log(issue)
    const issueBody = `### Infos générales \n|Infos||\n|-|-|\n|page|${issue.page}|\n|métier|${issue.job}|\n|rôles|${issue.role}|\n---\n\n### Description\n\n${issue['description objectif']}`
    const response = await axios.post(
      `https://api.github.com/repos/Fogo-Capital/maorie-monolith/issues?` +
        localStorage.getItem("github_access_token"),
      {
        title: issue.titre,
        labels: issue.labels,
        body: issueBody
      },
      {
        headers: {
          Authorization: "Bearer " + this.getToken(),
          Accept: "application/vnd.github.v3+json",
        },
      }
    );
    this.linkIssueToProject(response.data.node_id, PROJECT_NODE_ID, issue['priorité']);

    return response.data.html_url
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

  },
  async linkIssueToProject(issueID, projectID, priority) {
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
    this.addPriority(response.data.data.addProjectNextItem.projectNextItem.id, priority)
  },
  async addPriority(itemId, value) {
    const options = {
      low: 'd6bf4dde',
      medium: 'cc2239e7',
      high: '6ad45e78',
      urgent: '49f4ff9c'
    }
    const optionId = options[value];
    const payload = {
      query: `mutation {updateProjectNextItemField(input: {projectId: "${PROJECT_NODE_ID}" itemId: "${itemId}" fieldId: "${PRIORITY_FIELD_ID}" value: "${optionId}"}) {projectNextItem {id}}}`
    }

    const response = await axios.post(
      "https://api.github.com/graphql",
      payload,
      {
        headers: {
          Authorization: "Bearer " + this.getToken(),
        },
      }
    );

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
