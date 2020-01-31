import _ from 'lodash';
import {serializeArray} from './helpers';

/**
 * Submit a form via ajax and return the promise from axios
 *
 * @param {HTMLElement} form
 * @param {string} url
 * @param {Object} extraConfig
 * @param {Object} extraParams
 * @returns {*}
 */
export const ajaxForm = function (form, url = null, extraConfig = {}, extraParams = {}) {
    const formData = serializeArray(form);
    const params = new FormData();
    url = url || form.getAttribute('action');

    if (!url) {
        return new Promise().reject('URL was not provided: ');
    }

    console.log({formData, extraParams});

    formData.forEach((item) => {
        console.log(item);
        params.append(item.name, item.value);
    });

    console.log({params});

    // Object.keys(extraParams).forEach(function (item) {
    //     console.log('extra', item);
    //     params.append(item, extraParams[item]);
    // });


    // params.append('_token', window.csrf_token);

    console.log({url, params, form, formData, extraParams});

    let config = {
        method: 'post',
        url,
        data: params
    };

    console.log(config);

    config = _.extend(config, extraConfig);

    return axios(config);
};
