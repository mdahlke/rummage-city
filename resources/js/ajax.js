import $ from 'jquery';

import {empty} from './helpers';
import {getTargetFromInitiator, confirmDialog} from './ajax-helpers';

export const axios = require('axios');

const rc = {
	_property: {},
	setProp: function (key, val) {
		this._property[key] = val;
	},
	/**
	 * Get a property
	 *
	 * @param key
	 * @param def Return a default value if the key is not found
	 * @returns {*}
	 */
	getProp: function (key, def = null) {
		if (this._property.hasOwnProperty(key)) {
			return this._property[key];
		}
		
		return def;
	}
};

export const ajaxAction = function ($target, data) {
	if (!data) data = [];
	
	let $form = $target;
	if ($target.data('form')) {
		$form = $($target.data('form'));
	} else if (!$form.is('.ajax-form,form,.ajax-link')) {
		$form = $form.closest('.ajax-form');
	}
	
	let formData = new FormData();
	$.each($form.find(':input:not([type=file])').serializeArray(), function () {
		formData.append(this.name, this.value);
	});
	$.each($target.data(), function (key, val) {
		formData.append(key, val);
	});
	$.each(data, function (key, val) {
		formData.append(key, val);
	});
	
	let that = this;
	confirmDialog($target).then(function () {
			let method = null;
			let url = ($target.attr('data-href') ? $target.attr('data-href') : '');
			if ($form.is('form,.ajax-form')) {
				if (empty(method)) method = $form.find('input[name="_method"]').val() ? $form.find('input[name="_method"]').val() : 'POST';
				if (empty(url)) url = $form.attr('action');
			} else {
				if (empty(method)) method = $target.attr('data-method') ? $target.attr('data-method') : 'GET';
				if (empty(url)) url = $target.attr('href');
			}
			$target.addClass('ajax-in-progress');
		},
		function () {
			throw 'Request not sent';
		});
	
	
};

/**
 * Parse an AJAX response
 *
 * @since v3.0.0
 *
 * @param response
 */
const parseAjaxResponse = function (response) {
	'use strict';
	
	if (typeof response !== 'object') {
		// if the response isn't a JSON object we can't do anything with it
		return;
	}
	
	if (!empty(response.triggers)) {
		$.each(response.triggers, function (i, e) {
			$('body').trigger(e.event, e.args);
		});
	}
	
	if (!empty(response.flash)) {
		
		if (response.flash.constructor !== Array) {
			response.flash = [response.flash];
		}
		$.each(response.flash, function () {
			if (typeof this.message !== 'undefined' && this.message !== '') {
				rc.fn.flash.show(this);
			}
		});
	}
	
	if (!empty(response.redirect)) {
		if (response.redirect === 'reload') {
			window.location.reload();
		} else {
			window.location = response.redirect;
		}
	} else if (!empty(response.html)) {
		if (typeof response.html === 'object') {
			for (let r in response.html) {
				let target = response.html[r].target || false;
				let placement = response.html[r].placement || false;
				let html = response.html[r].html || false;
				
				if (!target || !placement || !html) {
					console.warn('Missing required arguments for AJAX HTML DOM manipulation.', {
						target,
						placement,
						html
					});
				}
				
				console.log({html, target, placement});
				
				viewMutation(html, target, placement);
			}
		} else {
			let target = typeof response.target !== 'undefined' && response.target !== '' ? response.target : rc.getProp('ajaxTarget');
			let placement = typeof response.placement !== 'undefined' && response.placement !== '' ? response.placement : rc.getProp('ajaxPlacement');
			viewMutation(response.html, target, placement);
		}
	}
};

const viewMutation = function (html = '', target = 'body', placement = 'prepend', modalAction = false) {
	if (placement === 'popup') {
		// sr.call('popup', [html]);
	} else if (placement === 'overwrite') {
		$(target).html(html);
	} else if (placement === 'append') {
		$(target).append(html);
	} else if (placement === 'replace') {
		$(target).replaceWith(html);
	} else if (placement === 'modal') {
		let $html = $(html);
		if ($('#' + $html.attr('id')).length) {
			$('#' + $html.attr('id')).replaceWith($html);
		} else {
			$('body').append($html);
		}
		
		$('#' + $html.attr('id')).modal('show').on('hidden.bs.modal', function (e) {
			if (modalAction) {
				let _this = this;
				$('body').trigger('ajax_action/modal/' + modalAction, [e, _this]);
			}
		});
	} else {
		$(target).prepend(html);
	}
};
/**
 *
 * @since v3.1.1
 */
// const showAjaxFormErrors = function (errors) {
//     'use strict';
//
//     $('.form-group .form-error').remove();
//     $('.form-group').removeClass('has-errors');
//
//     if (typeof errors !== 'undefined' && errors.length) {
//         $.each(errors, function (i, e) {
//             let $el = $('[name="' + e.input + '"]');
//
//             if ($el.length) {
//                 $el.closest('.form-group').addClass('has-errors').append('<div class="form-error">' + e.error + '</div>');
//             }
//         });
//     }
//
// }

/**
 * Serialize a form
 * Serialize a form
 *
 * @since v3.0.0
 *
 * @param formArray takes the serialized version of a form
 *          $('form').serialize()
 * @returns {{}}
 */
export const objectifyForm = function (formArray) {
	let returnArray = {};
	for (let i = 0; i < formArray.length; i++) {
		returnArray[formArray[i]['name']] = formArray[i]['value'];
	}
	return returnArray;
};

/**
 * Takes a query string and turns it into an objects
 *      test=1&action=test --> {test: 1, action: 'test'}
 *
 * @param query
 * @returns {{}}
 */
export const deparam = function (query) {
	if (typeof query === 'undefined' || typeof query === 'object') {
		return typeof query === 'object' ? query : {};
	}
	let pairs, i, keyValuePair, key, value, map = {};
	// remove leading question mark if its there
	if (query.slice(0, 1) === '?') {
		query = query.slice(1);
	}
	if (query !== '') {
		pairs = query.split('&');
		for (i = 0; i < pairs.length; i += 1) {
			keyValuePair = pairs[i].split('=');
			key = decodeURIComponent(keyValuePair[0]);
			value = (keyValuePair.length > 1) ? decodeURIComponent(keyValuePair[1].replace(/\+/g, '%20')) : undefined;
			map[key] = value;
		}
	}
	return map;
};

/**
 * Update a URL parameter
 * @param url
 * @param param
 * @param value
 */
export const updateUrlParameter = function (url, param, value) {
	const regex = new RegExp('(' + param + '=)[^&]+');
	return url.replace(regex, '$1' + value);
};

/**
 * Trigger the form action trigger
 *
 * This will trigger ajax_form_action/{form_action} and pass along:
 *      response
 *      params
 *      $form
 * @param action action name of the submitted form
 * @param response response of the AJAX call
 * @param params parameters that were sent in the AJAX call
 * @param $form the form object
 */
export const triggerFormAction = function (action, response, params, $form) {
	const data = [response, params, $form];
	
	$('body').trigger('ajax_form_action/' + action, data);
};

export const triggerLinkAction = function (action, response, params, form) {
	const data = {response, params, form};
	$('body').trigger('ajax_link_action/' + action, data);
};

// local storage
export const storage = function (db) {
	this.store = rc.getProp('localforage_' + db);
	
	// add a record
	this.store.set = (key, value) => {
		return this.store.setItem(key, value);
	};
	// retrieve a record
	this.store.get = (key) => {
		return this.store.getItem(key);
	};
	// get all the records
	this.store.all = () => {
		const store = this.store;
		let records = [];
		
		return new Promise(function (resolve, reject) {
			store.keys().then(function () {
				store.iterate(function (value, key) {
					records[key] = value;
				}).then(function () {
					resolve(records);
					records = null;
				}).catch(function (err) {
					records = null;
					reject(err);
				});
			});
		});
	};
	
	return this.store;
	
};


/**
 * Form auto-submit
 */
$(document)
	.on('submit', 'form.ajax-form', function (e) {
		e.preventDefault();
		
		const $self = $(this);
		let formData = $(this).serialize();
		
		if (!$self.find('input[name="action"]').length) {
			formData += '&action=' + $self.attr('data-action');
		}
		const CancelToken = axios.CancelToken;
		const source = CancelToken.source();
		
		// this is the element that we want the loading overlay to be attached to
		const $wrapTarget = ($self.closest('#me-popup').length ? [$self.closest('#me-popup')] : [false]);
		// trigger the form disable and pass the wrap target
		$self.trigger('form/disable', $wrapTarget);
		
		axios.get({
			type: ($self.attr('method') ? $self.attr('method') : 'GET'),
			data: formData
		}, {
			cancelToken: source.token
		}).then(function (data) {
			rc.triggerFormAction(data.params.action, data.response, data.params, $self);
			
			// re-enable the form after the ajax request is finished.
			$self.trigger('form/enable', $wrapTarget);
		}).catch((err, message) => {
			$self.trigger('form/enable', $wrapTarget);
			console.log(err, message);
		});
		return false;
	})
	.on('click', 'a.ajax-link', function (e) {
		e.preventDefault();
		const $self = $(this);
		
		let data = {
			action: $self.attr('data-action'),
			id: $self.attr('data-id')
		};
		
		// this is the element that we want the loading overlay to be attached to
		const $wrapTarget = ($self.data('show-load') ? ($self.data('target') ? [$($self.data('target'))] : [$self]) : [false]);
		$self.trigger('ajax_link/disable', $wrapTarget);
		
		/**
		 * Allow extra data to be sent in the AJAX request
		 *
		 * Value needs to be set as a JSON Object
		 *  so it can be parsed and sent as form data (key/value pairs)
		 **/
		if ($self.attr('data-send')) {
			data = $.extend({}, data, $.parseJSON($self.attr('data-send')));
		}
		
		const nonce = $self.data('nonce');
		if (nonce) {
			data['_nonce'] = nonce;
		}
		const CancelToken = axios.CancelToken;
		const source = CancelToken.source();
		const url = ($self.attr('href') && $self.attr('href') !== '#' ? $self.attr('href') : false);
		const method = ($self.attr('data-method') ? $self.attr('data-method') : 'GET').toLowerCase();
		const params = data;
		
		if(!url){
			console.error('Missing required parameter: URL. Specify a url using the "href" attribute"');
		}
		triggerLinkAction(('before/' + params.action), data, params, $self, source);
		
		rc.setProp('ajaxTarget', $self.data('target'));
		rc.setProp('ajaxPlacement', $self.data('placement'));
		
		axios[method](url, {params}, {
			cancelToken: source.token
		}).then(function (response) {
			const data = response.data.data;
			triggerLinkAction(params.action, data, params, $self);
			
			if (data.html === false) {
				triggerLinkAction(('after/' + params.action), data, params, $self);
				return false;
			}
			
			parseAjaxResponse(data);
			
			if ($self.data('show-load')) {
				if ($('#me-popup').length) {
					$('#me-popup').popup('hide');
				}
			}
			
			$self.trigger('ajax_link/enable', $wrapTarget);
			triggerLinkAction(('after/' + params.action), data, params, $self);
			
		}).catch(function () {
			$self.trigger('ajax_link/enable', $wrapTarget);
		});
		
		return false;
	})
	/**
	 * Show that the form is doing something
	 *
	 * @since v0.0.1
	 */
	.on('form/disable', 'form', function (e, wrapTarget = false) {
		if (wrapTarget === false) {
			wrapTarget = $(e.target);
		}
		let targetDisabledClass = $(e.target).data('target-disabled-class');
		targetDisabledClass = typeof targetDisabledClass === 'undefined' ? '' : targetDisabledClass;
		const wrapTargetClass = 'form-ajax-submit-disabled ' + targetDisabledClass;
		$(e.target).addClass('disabled');
		wrapTarget.addClass(wrapTargetClass).prepend($('<span class="form-disabled-icon"><i class="fa fa-circle-o-notch fa-spin-velocity"></i></span>'));
	})
	/**
	 * Re-enable a form that was previously "disabled"
	 *
	 * @since v0.0.1
	 *
	 * @param object e jQuery Event
	 * @param jQueryElement|null wrapTarget
	 */
	.on('form/enable', 'form', function (e, wrapTarget = false) {
		if (wrapTarget === false) {
			wrapTarget = $(e.target);
		}
		$(e.target).removeClass('disabled');
		wrapTarget.removeClass('form-ajax-submit-disabled').find('.form-disabled-icon').remove();
	})
	.on('')
	/**
	 * Show that the ajax link is is doing something
	 *
	 * @since v0.0.1
	 */
	.on('ajax_link/disable', '.ajax-link', function (e, wrapTarget) {
		if (wrapTarget === false) {
			wrapTarget = $(e.target);
		}
		
		const btn = $(e.currentTarget);
		let targetDisabledClass = $(e.target).data('target-disabled-class');
		targetDisabledClass = typeof targetDisabledClass === 'undefined' ? '' : targetDisabledClass;
		const wrapTargetClass = 'link-ajax-disabled ' + targetDisabledClass;
		const css = {
			borderRadius: btn.css('border-radius'),
			height: btn.css('height'),
			width: btn.css('width'),
			backgroundColor: btn.css('background-color')
		};
		$(e.target).addClass('disabled');
		const $iconWrap = $('<span />', {
			'class': 'link-disabled-icon'
		}).css({
			lineHeight: css.height
		});
		wrapTarget.addClass(wrapTargetClass).prepend($iconWrap.append('<i class="fa fa-circle-o-notch fa-spin-velocity"></i>'));
		wrapTarget.find('.link-disabled-icon').css(css);
	})
	/**
	 * Re-enable an ajax link that was previously "disabled"
	 *
	 * @since v0.0.1
	 *
	 * @param object e jQuery Event
	 * @param jQueryElement|null wrapTarget
	 */
	.on('ajax_link/enable', '.ajax-link', function (e, wrapTarget) {
		if (wrapTarget === false) {
			wrapTarget = $(e.target);
		}
		$(e.target).removeClass('disabled');
		wrapTarget.removeClass('link-ajax-disabled').find('.link-disabled-icon').remove();
	});

export function updateQueryString(key, value, url) {
	if (!url) url = window.location.href;
	var re = new RegExp('([?&])' + key + '=.*?(&|#|$)(.*)', 'gi'),
		hash;
	
	if (re.test(url)) {
		if (typeof value !== 'undefined' && value !== null) {
			return url.replace(re, '$1' + key + '=' + value + '$2$3');
		} else {
			hash = url.split('#');
			url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
			if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
				url += '#' + hash[1];
				return url;
			}
		}
	} else {
		if (typeof value !== 'undefined' && value !== null) {
			var separator = url.indexOf('?') !== -1 ? '&' : '?';
			hash = url.split('#');
			url = hash[0] + separator + key + '=' + value;
			
			if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
				url += '#' + hash[1];
				return url;
			} else {
				return url;
			}
		} else {
			return url;
		}
	}
}

export function setPage(url, title) {
	console.log(url);
	window.history.pushState({'pageTitle': title}, '', url);
}

// $(document)
// 	.on('submit', '.ajax-form', function () {
//
// 	})
// 	.on('click', '.ajax-link', function (e) {
// 		e.preventDefault();
// 		ajaxAction($(this));
// 	});
