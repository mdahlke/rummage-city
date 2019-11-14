document.addEventListener("DOMContentLoaded", function () {
    var lazyBackgrounds = [].slice.call(document.querySelectorAll(".lazy-background"));

    console.log({lazyBackgrounds});

    if ("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype) {
        let lazyBackgroundObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    console.log({entry});
                    entry.target.style.backgroundImage = 'url(' + entry.target.getAttribute('data-lazy-image') + ')';
                    lazyBackgroundObserver.unobserve(entry.target);
                }
            });
        });

        lazyBackgrounds.forEach(function (lazyBackground) {
            lazyBackgroundObserver.observe(lazyBackground);
        });
    }
});
