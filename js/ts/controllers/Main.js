var helloBox;
var listBox;
helloBox = new WinBox({
    "title": "Benvenutə",
    "class": "white",
    "url": "welcome.html"
});
helloBox.focus();
function showList() {
    if (!listBox) {
        listBox = new WinBox({
            title: "Libri",
            class: "white",
            url: "books.html",
            onclose: function () {
                listBox = null;
                return false;
            }
        });
    }
    else {
        listBox.minimize(false);
        listBox.focus();
    }
}
document.getElementsByClassName("books")[0].addEventListener("click", function listView() {
    showList();
});
showList();
//# sourceMappingURL=Main.js.map