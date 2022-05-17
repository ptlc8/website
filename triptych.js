"use strict";
window.addEventListener("load", function() {
    var triptych = document.getElementById("triptych");
    sendRequest("GET", "sitemap.json").then(function(r) {
        var sitemap = JSON.parse(r);
        for (var subsite of sitemap) {
            triptych.appendChild(createElement("div", {className:"tab",style:{backgroundColor:subsite.color}},
                [
                    createElement("img", {src:subsite.img}),
                    createElement("span", {className:"title"}, subsite.title),
                    createElement("div", {className:"content"}, subsite.content.map(function(subsite) {
                        return createElement("a", {className:"inner-link",href:subsite.link}, subsite.title);
                    }))
                ]
            ));
        }
    });
});