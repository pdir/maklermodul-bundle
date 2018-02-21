
var apiUrl = "https://pdir.de/api/maklermodul/";

window.addEvent("domready", function() {

	// dev log
    var devlogUrl = "https://www.maklermodul.de/share/devlog.xml";
    var devlog = $("devlog");

    if(devlog) {
        var req = new Request.HTML({
            method: "get",
            url: devlogUrl,
            onSuccess: function(tree, elements, html) {
                var temp = new Element("div").set("html", html);
                var i = 0;
                temp.getElements("item").each(function(el) {
                    if(++i > 5)
                        return false;

                    var d = new Date(el.getElements("pubdate")[0].innerText);
                    var currDate = ((d.getDate()<10)? "0"+d.getDate(): d.getDate());
                    var currMonth = ((d.getMonth()<10)? "0"+(d.getMonth()+1): (d.getMonth()+1));
                    var currYear = d.getFullYear();
                    var itemHTML = "<span>"+currDate + "." + currMonth + "." + currYear+"</span>";
                    itemHTML += "<span>"+el.getElements("title")[0].innerText+"</span>";
                    // itemHTML += "<a href=""+el.getElements("guid")[0].innerText+"" target="_blank"> [ lesen ] </a>";
                    itemHTML += "<div>"+el.getElements("description")[0].innerText.replace("]]>", "")+"</div>";

                    var newItem = new Element("div").addClass("item").set("html", itemHTML);
                    devlog.adopt(newItem);
                    console.log(i);
                });
            }
        }).send();
    }
});
