export default function() {
    let user = localStorage.getItem("token");
    return JSON.parse(user) !== null;
  }
  