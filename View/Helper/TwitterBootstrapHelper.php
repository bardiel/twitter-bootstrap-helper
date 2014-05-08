<?php

class TwitterBootstrapHelper extends AppHelper {

	public $helpers = array(
		"TwitterBootstrap.Bootstrap",
		"TwitterBootstrap.BootstrapHtml",
		"TwitterBootstrap.BootstrapForm"
	);
	
	public $defaultPanelButtonSize = 'sm';

	public function basic_input($field, $options = array()) {
		return $this->BootstrapForm->basicInput($field, $options);
	}

	public function _parse_input_options($field, $options = array()) {
		return $this->BootstrapForm->_parseInputOptions($field, $options);
	}

	public function _construct_label($options, $basic = true) {
		return $this->BootstrapForm->_constructLabel($options, $basic);
	}

	public function _construct_input($options) {
		return $this->BootstrapForm->_constructInput($options);
	}

	public function _constuct_input_and_addon($options) {
		return $this->BootstrapForm->_constuctInputAndAddon($options);
	}

	public function _handle_input_addon($options) {
		return $this->BootstrapForm->_handleInputAddon($options);
	}

	//public function input_addon($content, $input, $type = "append") {
	//	return $this->BootstrapForm->inputAddon($content, $input, $type);
	//}

	public function search($name = null, $options = array()) {
		return $this->BootstrapForm->search($name, $options);
	}

	public function input($field, $options = array()) {
		return $this->BootstrapForm->input($field, $options);
	}

	public function _combine_input($options) {
		return $this->BootstrapForm->_combineInput($options);
	}

	public function _help_markup($options) {
		return $this->BootstrapForm->_helpMarkup($options);
	}

	public function radio($field, $options = array()) {
		return $this->BootstrapForm->radio($field, $options);
	}

	public function button($value = "Submit", $options = array()) {
		return $this->BootstrapForm->button($value, $options);
	}

	public function button_dropdown($options = array()) {
		return $this->BootstrapHtml->buttonDropdown($options);
	}

	public function button_link($title, $url = null, $opt = array(), $confirm = false, $type = 'link') {
		if(is_array($title) && isset($title['post']) || isset($title['link'])) {
			return $this->BootstrapHtml->_buttonFromArray($title);
		} else {
			return $this->BootstrapHtml->_buttonFromArray(array($title, $url, $opt, $confirm, $type));
		}
	}

	public function breadcrumbs($breadcrumbs = array()) {
		if(empty($breadcrumbs)) return null;

		foreach ($breadcrumbs as $item) {
			$item = $this->BootstrapHtml->_parseLinkArray($item);
			$item[2]['escape'] = false;
		    $this->BootstrapHtml->Html->addCrumb($item[0], $item[1], $item[2]);
		}

		return $this->BootstrapHtml->breadcrumbs();
	}

	public function label($message = "", $style = "", $options = array()) {
		return $this->Bootstrap->label($message, $style, $options);
	}

	public function badge($title = '0', $options = array()) {
		return $this->Bootstrap->badge($title, $options);
	}

	public function icon($name, $options = array()) {
		return $this->Bootstrap->icon($name, $options);
	}

	public function progress($options = array()) {
		return $this->Bootstrap->progress($options);
	}

	public function alert($content, $options = array()) {
		return $this->Bootstrap->alert($content, $options);
	}

	public function flash($key = "flash", $options = array()) {
		return $this->Bootstrap->flash($key, $options);
	}

	public function _flash_content($key = "flash") {
		return $this->Bootstrap->_flash_content($key);
	}

	public function page_header($title, $small = ""){
		return $this->Bootstrap->pageHeader($title, $small);
	}
	
	public function start_panel($options = array()) {
		return $this->BootstrapHtml->start_panel($options);
	}
	
	public function start_panel_footer($options = array()) {
		return $this->BootstrapHtml->start_panel_footer($options);
	
	public function end_panel($options = array()) {
		return $this->BootstrapHtml->end_panel($options);
	}
	
	public function save_button() {
		return $this->button($this->icon('ok-sign', 'white') . __('&nbsp;Guardar'), array('style' => 'success', 'size' => $this->defaultPanelButtonSize));
	}
	
	public function navbar($options = array()) {
		return $this->BootstrapHtml->navbar($options);
	}
	
	public function formCreate($model = null, $options = array()) {
		return $this->BootstrapForm->create($model, $options);
	}
	
	public function nav($options = array()) {
		 return $this->BootstrapHtml->nav($options);
	}
}
