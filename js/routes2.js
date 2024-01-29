var page = "pages/home";

localStorage.getItem("pages") ? loadPage(localStorage.getItem("pages")) : loadPage(page);

$(".link").click(function (e) {
  
  e.preventDefault();
  page = $(this).attr("href");
  localStorage.setItem("pages", page);

  loadPage(page);
});

function loadPage(pages) {
  $("#app").load(pages + ".html");
}
