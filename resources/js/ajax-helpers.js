export const getTargetFromInitiator = function ($initiator) {
	let $target = null;
	if ($initiator.data('target')) $target = $($initiator.data('target'));
	if (!$target && $initiator.is('.ajax-target')) return $initiator;
	if (!$target) $target = $initiator.closest('.ajax-target');
	if (!$target) $target = $initiator;
	return $target;
	
};

export const confirmDialog = function ($initiator) {
	return new Promise(function (resolve) {
		resolve();
	});
};
