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
            title: "Elenco",
            class: "white",
            url: "list.html",
            onclose: function () {
                listBox = null;
                return false;
            }
        });
    }
    else {
        listBox.focus();
    }
}
document.getElementsByClassName("list-button")[0].addEventListener("click", function listView() {
    showList();
});
showList();
//# sourceMappingURL=Main.js.map