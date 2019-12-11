import moment from 'moment';
import axios from 'axios';
import _ from 'lodash';

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
export const mapboxLatLng = function (listing) {
    return {
        lat: listing.latitude,
        lng: listing.longitude
    };
};

export function setPage(url, title, state) {
    let data = _.extend({
        pageTitle: title
    }, state);
    window.history.pushState(data, title, url);
}

export function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
    var separator = uri.indexOf('?') !== -1 ? '&' : '?';
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + '=' + value + '$2');
    } else {
        return uri + separator + key + '=' + value;
    }
}

let call = {};
export const axiosOne = (config = {}, requestType = 'unnamed') => {
    if (call[requestType]) {
        call[requestType].cancel('Only one request allowed at a time. Cancelling first.');
    }
    call[requestType] = axios.CancelToken.source();
    config.cancelToken = call[requestType].token;
    return axios(config);
};

export const createElementFromHtml = htmlString => {
    let div = document.createElement('div');
    div.innerHTML = htmlString.trim();

    // Change this to div.childNodes to support multiple top-level nodes
    return div.firstChild;
};

export const isTrue = val => {
    return (val == 'true' || val === true);
};

export const is_false = val => {
    return !isTrue(val);
};

/**
 * Checks if a listing is happening today
 *
 * @param listingDates Expects listing dates object from the Laravel model
 */
export const isListingToday = (...listingDates) => {
    const today = new moment();
    let start;
    let end;
    let isToday = false;

    listingDates.some(listingDate => {

        start = new moment(listingDate.start);
        end = new moment(listingDate.end);

        if (!start.isSame(end, 'd')) {
            isToday = today.isBetween(start, end);
        } else {
            isToday = today.isSame(start, 'd');
        }

        return isToday;
    });

    return isToday;
};

/**
 * Checks if a given date is on the weekend (including Friday)
 * @param date Moment()
 * @returns {boolean}
 */
const isWeekend = function (date) {
    if ([5, 6, 7].includes(date.day())) {
        return true;
    }
    return false;
};

/**
 * Checks if a listing is happening (inclusively) this weekend
 * If the current day is a Friday, Saturday, or Sunday then this
 * will return true for the closest date that is today or in the future
 * and within that same weekend
 *
 * Example:
 * Current Date: Friday Oct 7
 *  Dates:
 *      Friday Oct 7 - true
 *      Saturday Oct 8 - true
 *      Sunday Oct 9 - true
 *
 *      Friday Oct 14 - false
 *      Saturday Oct 15 - false
 *      Sunday Oct 16 - true
 *
 * Current Date: Saturday Oct 8
 *  Dates:
 *      Friday Oct 7 - false
 *      Saturday Oct 8 - true
 *      Sunday Oct 9 - true
 *
 *      Friday Oct 14 - false
 *      Saturday Oct 15 - false
 *      Sunday Oct 16 - false
 *
 *
 * Current Date: Sunday Oct 9
 *  Dates:
 *      Friday Oct 7 - false
 *      Saturday Oct 8 - false
 *      Sunday Oct 9 - true
 *
 *      Friday Oct 14 - false
 *      Saturday Oct 15 - false
 *      Sunday Oct 16 - false
 *
 * @param listingDates Expects listing dates object from the Laravel model
 */
export const isListingThisWeekend = (...listingDates) => {
    let start;
    let end;
    let isThisWeekend = false;
    const today = new moment();

    listingDates.some(listingDate => {
        start = new moment(listingDate.start);
        end = new moment(listingDate.end);

        if (!start.isSame(end, 'd')) {
            isThisWeekend = today.isBetween(start, end);
        } else if (isWeekend(start)) {
            const upcomingWeekend = getUpcomingWeekend();

            if (today.isBetween(upcomingWeekend[0], upcomingWeekend[2])) {
                isThisWeekend = true;
            }
        }

        return isThisWeekend;
    });

    return isThisWeekend;
};

// globally stores the upcoming weekend to prevent recalculating many times
let thisWeekend = [];

const getUpcomingWeekend = (today = null) => {
    if (!thisWeekend.length) {
        const fridayDayNumber = 5;
        let friday = new moment();
        let saturday;
        let sunday;
        // if current day is in the week
        let daysToWeekend = 0;
        // if current day is in the weekend
        let daysFromFriday = 0;

        if (!today) {
            today = new moment().startOf('day');
        }

        if (!isWeekend(today)) {
            daysToWeekend = fridayDayNumber - today.day();
            friday = today;
            friday.add(daysToWeekend, 'd');
        } else {
            daysFromFriday = today.day() - fridayDayNumber;
            friday = today;
            friday.subtract(daysFromFriday, 'd');
        }

        saturday = (new moment(friday)).add(1, 'd');
        sunday = (new moment(friday)).add(2, 'd');

        thisWeekend = [friday, saturday, sunday];
    }

    return thisWeekend;
};
