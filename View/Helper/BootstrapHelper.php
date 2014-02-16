<?php

class BootstrapHelper extends AppHelper {

	public $helpers = array("Html", "Session");

	/**
	 * Displays an h1 tag wrapped in a div with the page-header class
	 *
	 * @param string $title
	 * @param string $small
	 * @return string
	 */
	public function pageHeader($title, $small){
		if(!empty($small)) {
			return $this->Html->tag(
				"div",
				$this->Html->tag('h1', $title . $this->Html->tag('small', $small)),
				array("class" => "page-header")
			);
		} else {
			return $this->Html->tag(
				"div",
				$this->Html->tag('h1', $title),
				array("class" => "page-header")
			);
		}
	}

	/**
	 * Creates a Bootstrap label with $messsage and optionally the $type. Any
	 * options that could get passed to HtmlHelper::tag can be passed in the
	 * third param.
	 *
	 * @param string $message
	 * @param string $type
	 * @param array $options
	 * @access public
	 * @return string
	 */
	public function label($message = "", $style = "default", $options = array()) {
		$class = "label";
		$valid_style = array("default", "primary", "success", "info", "warning", "danger");
		
		$class .= (in_array($style, $valid_style)) ? " label-{$style}" : " label-default";
		
		if (isset($options["class"]) && !empty($options["class"])) {
			$options["class"] = $class . " " . $options["class"];
		} else {
			$options["class"] = $class;
		}
		return $this->Html->tag("span", $message, $options);
	}

	/**
	 * Creates a Bootstrap badge with $title  Any options
	 * that could get passed to the HtmlHelper::tag can be passed in the 2nd
	 * param. Note that style are deprecated since Bootstrap 3.0.0
	 *
	 * @param  string  $title
	 * @param  array   $options
	 * @return string
	 */
	public function badge($title, $options = array()) {
		$class = "badge";
		
		if (isset($options["class"]) && !empty($options["class"])) {
			$options["class"] = $class . " " . $options["class"];
		} else {
			$options["class"] = $class;
		}
		return $this->Html->tag("span", $title, $options);
	}

	/**
	 * progress
	 *
	 * @param  string $style
	 * @param  array  $options
	 * @return string
	 */
	public function progress($options = array()) {
		$class = "progress";
		$width = 0;
		$valid = array("info", "success", "warning", "danger");
		if (isset($options["style"]) && in_array($options["style"], $valid)) {
			$class .= " progress-{$options["style"]}";
		}
		if (isset($options["striped"]) && $options["striped"]) {
			$class .= " progress-striped";
		}
		if (isset($options["active"]) && $options["active"]) {
			$class .= " active";
		}
		if (
			isset($options["width"]) &&
			!empty($options["width"]) &&
			is_int($options["width"])
		) {
			$width = $options["width"];
		}
		$bar = $this->Html->tag(
			"div",
			"",
			array("class" => "bar", "style" => "width: {$width}%;")
		);
		return $this->Html->tag("div", $bar, array("class" => $class));
	}

	/**
	 * Takes the name of an icon and returns the i tag with the appropriately
	 * named class.
	 *
	 * @param string $name
	 * @param array $options Html options passed to HtmlHelper::tag()
	 * @access public
	 * @return void
	 */
	public function icon($name, $options = array()) {
		$class = " glyphicon icon-{$name} ";
		if(!is_array($options)) $options = array();
		
		if(isset($options['class']) && !empty($options['class'])) {
			$options['class'] .= $class;
		} else {
			$options['class'] = $class;
		}
		return $this->Html->tag("i", false, $options);
	}

	/**
	 * Renders alert markup and takes a style and closable option
	 *
	 * @param mixed $content
	 * @param array $options
	 * @access public
	 * @return void
	 */
	public function alert($content, $options = array()) {
		$close = "";
		if (isset($options['closable']) && $options['closable']) {
			$close = '<a class="close" data-dismiss="alert">&times;</a>';
		}
		$style = isset($options["style"]) ? $options["style"] : "warning";
		$types = array("info", "success", "danger", "warning");
		if ($style === "flash") {
			$style = "warning";
		}
		if (strtolower($style) === "auth") {
			$style = "error";
		}
		if (!in_array($style, array_merge($types, array("auth", "flash")))) {
			$class = "alert alert-warning {$style}";
		} else {
			$class = "alert alert-{$style}";
		}
		return $this->Html->tag(
			'div',
			"{$close}{$content}",
			array("class" => $class)
		);
	}

	/**
	 * Captures the Session flash if it is set and renders it in the proper
	 * markup for the twitter bootstrap styles.
	 * Options are the same options as SessionHelper::flash($key = 'flash', $options = array());
	 * Added Options inside 'params' key are:
	 * 	'closable' => Boolean //Default is false
	 *  'style' => String // Twitter Bootstrap supported Styles are: 'success', 'info', 'warning', 'danger'. Default is 'danger'
	 * 'class' key inside 'params' key has a changed behaviour; classes inside 'class' configuration now append to the "alert" and "alert-style" class
	 *
	 * @param string $key
	 * @param $options
	 * @access public
	 * @return string
	 */
	public function flash($key = 'flash', $attrs = array()) {
		$out = false;

		if (CakeSession::check('Message.' . $key)) {
			$flash = CakeSession::read('Message.' . $key);
			$message = $flash['message'];
			unset($flash['message']);
			
			if (!empty($attrs)) {
				if(isset($attrs['params']) && isset($flash['params'])) {
					$attrs['params'] = array_merge($flash['params'], $attrs['params']);
				}
				$flash = array_merge($flash, $attrs);
			}

			if ($flash['element'] === 'default') {
				$class = 'alert ';
				$class .= (isset($flash['params']['style']) && !empty($flash['params']['style'])) ? "alert-{$flash['params']['style']} " : 'alert-warning ';
				if (!empty($flash['params']['class'])) {
					$class .= $flash['params']['class'];
				}
				if(isset($flash['params']['closable']) && $flash['params']['closable'] === true) {
					$class .= 'fade in';
					$closeButton = '<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>';
					$out = $this->Html->tag('div', $closeButton . $message, array('id' => "{$key}Message", 'class' => $class));
				} else {
					$out = $this->Html->tag('div', $message, array('id' => "{$key}Message", 'class' => $class));
				}
			} elseif (!$flash['element']) {
				$out = $message;
			} else {
				$options = array();
				if (isset($flash['params']['plugin'])) {
					$options['plugin'] = $flash['params']['plugin'];
				}
				$tmpVars = $flash['params'];
				$tmpVars['message'] = $message;
				$out = $this->_View->element($flash['element'], $tmpVars, $options);
			}
			CakeSession::delete('Message.' . $key);
		}
		return $out;
	}

}
