"use strict";
var toggleBalloons = (() => {
    let lastActivityTime = Date.now();
    let intervalId = null;
    ['mousemove', 'keydown', 'click'].forEach(event => document.addEventListener(event, () => lastActivityTime = Date.now()));
    function toggle() {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        } else {
            intervalId = setInterval(() => {
                if (Date.now() - lastActivityTime > 20000) {
                    let time = 9000 + Math.random() * 2000;
                    let balloon = document.createElement("span");
                    balloon.innerText = ['🎈', '💜', '💛'][Math.floor(Math.random() * 3)];
                    balloon.style.fontSize = '2rem';
                    balloon.style.zIndex = '1000000';
                    balloon.style.position = 'fixed';
                    balloon.style.userSelect = 'none';
                    balloon.style.pointerEvents = 'none';
                    balloon.style.left = `${Math.random() * 100}vw`;
                    balloon.style.top = `${Math.random() * 20 + 110}vh`;
                    balloon.style.opacity = 1;
                    balloon.style.transition = `opacity ${time}ms ease-out, top ${time}ms linear`;
                    document.body.appendChild(balloon);
                    getComputedStyle(balloon).opacity; // force reflow
                    balloon.style.opacity = 0;
                    balloon.style.top = `${Math.random() * -200 - 10}vh`;
                    setTimeout(() => {
                        document.body.removeChild(balloon);
                    }, time);
                }
            }, 1000);
        }
    }
    toggle();
    return toggle;
})();