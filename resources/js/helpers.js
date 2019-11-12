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

export function setPage(url, title, state) {
    let data = _.extend({
        pageTitle: title
    }, state);
    window.history.pushState(data, title, url);
}

export function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}

let call = {};
export const axios_one = (config = {}, requestType = 'unnamed') => {
    if (call[requestType]) {
        call[requestType].cancel("Only one request allowed at a time. Cancelling first.");
    }
    call[requestType] = axios.CancelToken.source();
    config.cancelToken = call[requestType].token;
    return axios(config);
};

export const create_element_from_html = htmlString => {
    let div = document.createElement('div');
    div.innerHTML = htmlString.trim();

    // Change this to div.childNodes to support multiple top-level nodes
    return div.firstChild;
};

export const is_true = val => {
    return (val == 'true' || val === true);
};

export const is_false = val => {
    return !is_true(val);
};
