"use strict";
{
    var triptych = document.getElementById("triptych");
    sendRequest("GET", "sitemap.json").then(function (r) {
        var sitemap = JSON.parse(r);
        for (var subsite of sitemap) {
            triptych.appendChild(createElement("div", { className: "tab", style: { backgroundColor: subsite.color } },
                [
                    createElement("div", { className: "head" }, [
                        createElement("img", { src: subsite.img, width: "128", alt: subsite.title }),
                        createElement("span", { className: "title" }, subsite.title),
                    ]),
                    createElement("div", { className: "detail" }, subsite.content.map(function (subsite) {
                        return createElement("a", { href: subsite.link || "#" }, subsite.title);
                    }).concat(
                        subsite.git ? createElement("a", { className: "git", href: subsite.git }, [
                            createElement("img", { src: "assets/git.png", height: "32", alt: "git" })
                        ]) : []
                    ))
                ]
            ));
        }
    });
}