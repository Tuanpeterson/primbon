// SETUP
var hash = window.location.hash;
const mainElement = $("#app");
const pagesDir = "pages/";
const homePage = "home";
const extensionFile = ".html";
// END SETUP

if (hash === "") {
  mainElement.load(pagesDir + homePage + extensionFile);
} else {
  var hash = window.location.hash;
  hash = hash.replace("#", "");
  mainElement.load(pagesDir + hash + extensionFile);
}

addEventListener("hashchange", (event) => {});

onhashchange = (event) => {
  var newUrl = event.newURL;

  var slice = newUrl.split("#");
  var halaman = pagesDir + slice[1];
  mainElement.load(halaman + extensionFile);
};
