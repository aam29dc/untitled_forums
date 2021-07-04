function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }
  
  async function waitdirect(time = 2000, dir = "index.php") {
    await sleep(time);
    window.location.href = dir;
  }