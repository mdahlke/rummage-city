<?php
/**
 * Created by PhpStorm.
 * User: briankolstad
 * Date: 5/24/18
 * Time: 8:21 AM
 */

namespace App;

use App\Services\SessionService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use Psr\Http\Message\StreamInterface;

class Ajax {
	const

		PLACEMENT_DEFAULT = 'default',
		PLACEMENT_MODAL = 'modal',
		PLACEMENT_POPUP = 'popup',
		PLACEMENT_BUBBLE = 'bubble',
		PLACEMENT_INNER = 'inner',
		PLACEMENT_APPEND = 'append',
		PLACEMENT_PREPEND = 'prepend',
		PLACEMENT_BEFORE = 'before',
		PLACEMENT_AFTER = 'after',
		PLACEMENT_REMOVE = 'remove',
		PLACEMENT_REPLACE = 'replace',

		EVENT_SUCCESS = 'success',
		EVENT_FAILED = 'error',
		EVENT_DIALOG_OPENED = 'dialog_opened',
		EVENT_DIALOG_CLOSED = 'dialog_closed',
		EVENT_MESSAGE_SENT = 'message_sent',

		FILE_SELECTED = 'file-selected',

		LIBRARY_VIDEO_ADDED = 'video-added',
		LIBRARY_VIDEO_PROCESSED = 'video-processed',
		LIBRARY_VIDEO_FAILED = 'video-failed',
		LIBRARY_VIDEO_ABORTED = 'video-aborted',

		AJAX_TARGET_MAIN = '#app',

		MAX_INLINE_FILE_SIZE = 1024 * 1024,

		AJAX_DATA_LAST_CONST = 'last constant so no worry about closing syntax';

	private $data = ['pageTitle' => '', 'url' => '', 'triggers' => [], 'html' => [], 'attributes' => [], 'alerts' => [], 'data' => [], 'css' => [], 'downloads' => []];

	public function __construct(string $pageTitle = '', string $url = '') {
		$this->setPageTitle($pageTitle);
		$this->setUrl($url);
	}

	/**
	 * @param string $attr
	 * @param string $value
	 * @param string $target
	 */
	public function addAttr(string $attr, string $value, $target = '') {
		$attribute = ['attr' => $attr, 'value' => $value];
		if (!empty($target)) {
			$attribute['target'] = $target;
		}
		$this->data['attributes'][] = $attribute;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @param string $target
	 */
	public function addData(string $name, string $value, $target = '') {
		$attribute = ['name' => $name, 'value' => $value];
		if (!empty($target)) {
			$attribute['target'] = $target;
		}
		$this->data['data'][] = $attribute;
	}

	/**
	 * @param $content
	 * @param $fileName
	 * @param string $type
	 * @return string|null  // url of download file or null if inline
	 * @throws Exceptions\DomainDoesNotExistException
	 */
	public function addDownload($content, $fileName, $type = '') {
		if (get_class($content) == StreamInterface::class) {
			/** @var StreamInterface $content */
			if ($content->getSize() > self::MAX_INLINE_FILE_SIZE) {
				$drive = new FileSystem(FileSystem::DOWNLOAD_TMP, SessionService::getDomain());
				$drive->putStream($fileName, $content);
				$url = route('download', ['type' => FileSystem::DOWNLOAD_TMP, 'filename' => $fileName]);
				$this->addFileDownload($url, $fileName, $type);
				return $url;
			}
			else {
				$content = $content->getContents();
			}
		}
		$this->data['downloads'][] = ['content' => base64_encode($content), 'fileName' => $fileName, 'type' => $type];
		return null;
	}

	public function addFileDownload($url, $fileName, $type = '') {
		$this->data['downloads'][] = ['url' => $url, 'fileName' => $fileName, 'type' => $type];
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @param string $target
	 */
	public function addDataLayer(string $name, string $value) {
		$attribute = ['name' => $name, 'value' => $value];
		$this->data['dataLayer'][] = $attribute;
	}

	/**
	 * @param $obj
	 * @param bool $success
	 */
	public function addGtm($obj, $category = null, $action = null, $label = null, $event = null) {
		$gtm = new GoogleTagManager();

		$tagData = $gtm->gtmCreate($obj, $category, $action, $label, $event);

		foreach ($tagData AS $key => $value) {
			$this->addDataLayer($key, $value);
		}
	}

	//    /**
	//     * @param $obj
	//     * @param bool $success
	//     */
	//    public function addGtm($obj, $errorType = null, $errorMsg=null){
	//        $gtm = new GoogleTagManager();
	//
	//        $tagData = ($errorType === null ? $gtm->gtmSuccess($obj) : $gtm->gtmError($obj, $errorType));
	//
	//        foreach($tagData AS $key=>$value){
	//            $this->addDataLayer($key, $value);
	//        }
	//    }

	/**
	 * @param string $name
	 * @param string $value
	 * @param string $target
	 */
	public function addCSS(string $name, string $value, $target = '') {
		$attribute = ['name' => $name, 'value' => $value];
		if (!empty($target)) {
			$attribute['target'] = $target;
		}
		$this->data['css'][] = $attribute;
	}

	/**
	 * @param string $alert
	 * @param string $target
	 * @param string $status
	 * @param string $actionUrl
	 * @param string $actionText
	 */
	public function addAlert(string $alert, string $target = '', string $status = 'success', string $actionUrl = '', string $actionText = '') {
		$alert = ['message' => $alert, 'status' => $status];
		if (!empty($target)) {
			$alert['target'] = $target;
		}
		if (!empty($actionUrl)) {
			$alert['url'] = $actionUrl;
		}
		if (!empty($actionText)) {
			$alert['buttonText'] = $actionText;
		}
		$this->data['alerts'][] = $alert;
	}

	/**
	 * @param string $event
	 * @param string $target
	 */
	public function addEvent(string $event, string $target = '', array $args = []) {
		$trigger = ['event' => $event];
		if (!empty($target)) {
			$trigger['target'] = $target;
		}
		if (!empty($args)) {
			$trigger['args'] = $args;
		}
		$this->data['triggers'][] = $trigger;
	}

	/**
	 * @param string $html
	 * @param string|null $placement
	 * @param string $target
	 * @param array $options
	 */
	public function addHTML(string $html, string $placement = null, string $target = '', array $options = []) {
		if ($placement === self::PLACEMENT_DEFAULT) {
			$placement = Request::ajax() ? self::PLACEMENT_REPLACE : self::PLACEMENT_POPUP;
		}
		$content = ['html' => $html, 'placement' => $placement];
		if (!empty($target)) {
			$content['target'] = $target;
		}
		if (!empty($options)) {
			$content['options'] = $options;
		}
		$this->data['html'][] = $content;
	}

	/**
	 * @param string $target
	 */
	public function removeElement(string $target = '') {
		$this->addHTML('', self::PLACEMENT_REMOVE, $target);
	}

	/**
	 * @param View $view
	 * @param string|null $placement
	 * @param string $target
	 * @param array $options
	 * @throws \Throwable
	 */
	public function addView(View $view, string $placement = null, string $target = '', array $options = []) {
		if (!$view->offsetExists('pageTitle')) {
			$view->with('pageTitle', $this->data['pageTitle']);
		}
		$this->addHTML($view->render(), $placement, $target, $options);
	}

	/**
	 * @param string $title
	 */
	public function setPageTitle(string $title) {
		$this->data['pageTitle'] = $title;
	}

	/**
	 * @param string $url
	 */
	public function setUrl(string $url) {
		$this->data['url'] = $url;
	}

	/**
	 * @return false|string
	 */
	public function json() {
		return json_encode($this->data, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * @param null $response
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|null
	 */
	public function generateResponse($response = null) {
		if (Request::expectsJson()) {
			foreach (SessionService::ALERTS as $msg) {
				if (\Session::has($msg)) {
					$this->addAlert(\Session::get($msg), '', $msg);
				}
			}
			return response()->json($this->data);
		}
		else {
			request()->session()->flash('response', $this);
			foreach ($this->data['alerts'] as $alert) {
				request()->session()->flash($alert['status'], $alert['message']);
			}
			if ($response !== null) {
				return $response;
			}
			else if (count($this->data['html'])) {
				return $this->data['html'][0]['html'];
			}
			else {
				return back();
			}
		}
	}
}
