import axios from "axios";

const googleAuth = {
  isLogged() {
    return (
      null !== localStorage.getItem("access_token") &&
      undefined !== localStorage.getItem("access_token")
    );
  },
  async refreshToken() {
    const refreshToken = localStorage.getItem("refresh_token");
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
    const clientId = process.env.VUE_APP_CLIENT_ID;
    const clientSecret = process.env.VUE_APP_CLIENT_SECRET;

    const tokenUri = process.env.VUE_APP_TOKEN_URI;
    const response = await axios.post(
      `${tokenUri}?client_id=${clientId}&client_secret=${clientSecret}&grant_type=authorization_code&code=${code}&redirect_uri=${process.env.VUE_APP_URL}/g-auth/redirect-uri`
    );

    const newToken = response.data;
    
    localStorage.setItem('access_token', newToken['access_token'])
    localStorage.setItem('refresh_token', newToken['refresh_token'])
  }
};

export default googleAuth;
