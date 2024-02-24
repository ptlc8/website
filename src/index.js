"use strict";

function createElement(tag, properties = {}, inner = [], eventListeners = {}) {
    let el = document.createElement(tag);
    for (let p of Object.keys(properties)) if (p != "style" && p != "dataset") el[p] = properties[p];
    if (properties.style) for (let p of Object.keys(properties.style)) el.style[p] = properties.style[p];
    if (properties.dataset) for (let p of Object.keys(properties.dataset)) el.dataset[p] = properties.dataset[p];
    if (typeof inner == "object") for (let i of inner) el.appendChild(typeof i == "string" ? document.createTextNode(i) : i);
    else el.innerText = inner;
    for (let l of Object.keys(eventListeners)) el.addEventListener(l, eventListeners[l]);
    return el;
}


fetch("sitemap.json")
    .then(r => r.json())
    .then(sitemap => {
        for (var subsite of sitemap) {
            let id = subsite.title.toLowerCase().replace(/[^a-z0-9]+/g, "-");
            document.getElementById("projects")
                .appendChild(createElement("div", { className: "card", id, style: { backgroundColor: subsite.color } },
                    [
                        createElement("img", { className: "preview", alt: "", ...(subsite.preview ? { src: subsite.preview } : {}) }),
                        createElement("div", { className: "head" }, [
                            createElement("img", { src: subsite.img, width: "128", alt: subsite.title }),
                            createElement("h2", { className: "title" }, subsite.title),
                            subsite.git ? createElement("a", { className: "git", href: subsite.git }, [
                                createElement("img", { src: "assets/git.png", height: "32", alt: "git" })
                            ]) : ""
                        ]),
                        createElement("div", { className: "detail" }, subsite.content.map(subsite =>
                            createElement("a", { href: subsite.link || "#" }, subsite.title)
                        ))
                    ]
                ));
        }
    });
