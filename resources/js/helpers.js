/**
 * Add JavaScript files to the <head>
 * @param urls
 */
export const script2Head = function (urls) {
    let script;

    if (urls.constructor !== Array) {
        urls = [urls];
    }

    for (var i = 0; i < urls.length; i++) {
        script = document.createElement('script');
        script.setAttribute('src', urls[i]);
        document.getElementsByTagName('head')[0].appendChild(script);
    }
};


export const empty = function (subject) {
    return (typeof subject === 'undefined' || subject === '' || subject === null || subject === false);
};

/**
 * Return an object that works with mapbox's lat/lng parameter
 *
 * @param listing
 * @returns {{lng: *, lat: *}}
 */
export const mapbox_latlng = function (listing) {
    return {
        lat: listing.latitude,
        lng: listing.longitude
    }
}

export function setPage(url, title) {
    console.log(url);
    window.history.pushState({'pageTitle': title}, title, url);
}

export function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
        return uri + separator + key + "=" + value;
    }
}
