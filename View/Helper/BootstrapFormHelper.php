<?php

App::uses("FormHelper", "View/Helper");

class BootstrapFormHelper extends FormHelper {

    /**
     * Options used internally. Don't send any of these options along to FormHelper
     *
     * @var array
     * @access private
     */
    private $__dontSendToFormHelper = array(
        'help_inline',
        'help_block',
        'label',
        'div',
        'error',
        'checkbox_label',
        'append',
        'prepend',
        'field'
    );
	
	private $_formHorizontal = false;
	private $_horizontalSeparation = 2;

	/**
	 * Starts a form to be ended with CakePHP's FormHelper::end(). This methods adds new option for
	 * new "form-horizontal" CSS class.
	 * Now you can add $options['horizontal'] which can be:
	 * boolean Creates a form-horizontal. Label will be inside a col-md-2 div. Input will be inside a col-md-10 div
	 * integer The size of the label col-md- div. 
	 *
	 * @param mixed $model The model name for which the form is being defined. Should
	 *   include the plugin name for plugin models. e.g. `ContactManager.Contact`.
	 *   If an array is passed and $options argument is empty, the array will be used as options.
	 *   If `false` no model is used.
	 * @param array $options An array of html attributes and options.
	 * @return string An formatted opening FORM tag.
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-create
	 */
	public function create($model = null, $options = array()) {
		if(isset($options['horizontal'])) {
			switch(gettype($options['horizontal'])) {
				case 'boolean':
					$this->_formHorizontal = $options['horizontal'];
					break;
				case 'integer':
					$this->_formHorizontal = true;
					if(0 < $options['horizontal'] && $options['horizontal'] < 12 ) {
						$this->_horizontalSeparation = $options['horizontal'];
					}
					break;
			}
			
			if($this->_formHorizontal === true) {
				$options['class'] = (isset($options['class'])) ? $options['class'] . ' form-horizontal' : 'form-horizontal';
			}
			unset($options['horizontal']);
		}
		return parent::create($model, $options);
	}
    /**
     * basic_input
     *
     * @param mixed $field
     * @param array $options
     * @access public
     * @return void
     */
    public function basicInput($field, $options = array()) {
        $options = $this->_parseInputOptions($field, $options);
        if (!isset($options["field"])) { return ""; }
        $options["label"] = $this->_constructLabel($options);
        $options["input"] = $this->_constructInput($options);
        return $options["label"] . $options["input"];
    }

    /**
     * _parse_input_options
     *
     * @param mixed $field
     * @param array $options
     * @access public
     * @return void
     */
    public function _parseInputOptions($field, $options = array()) {
        if (is_array($field)) {
            $options = $field;
        } else {
            $options["field"] = $field;
        }
        $defaults = array(
            "type" => "",
            "help_inline" => "",
            "help_block" => "",
            "label" => "",
            "append" => false,
            "prepend" => false,
            "state" => false,
            "class" => "",
        );
        $out = array_merge($defaults, $options);
        $out['class'] = $out['class'] . " form-control";
        return $out;
    }

    /**
     * _construct_label
     *
     * @param mixed $options
     * @access public
     * @return void
     */
    public function _constructLabel($options, $basic = true) {
        if ($options["label"] === false) { return ""; }
        if (in_array($options["type"], array("checkbox"))) {
            $opt = $options;
            $opt["type"] = "";
            $input = $this->_constructInput($opt);
            $options["label"] = parent::label(
                $options["field"],
                $input . $options["label"],
                "checkbox"
            );
        } else {
            $class = (!$basic) ? "control-label" : null;
            if (!empty($options["label"])) {
                $options["label"] = parent::label(
                    $options["field"],
                    $options["label"],
                    array("class" => $class)
                );
            } else {
                $options["label"] = parent::label(
                    $options["field"],
                    null,
                    array("class" => $class)
                );
            }
        }
        return $options["label"];
    }

    /**
     * _construct_input
     *
     * @param mixed $options
     * @access public
     * @return void
     */
    public function _constructInput($options) {
        if (in_array($options["type"], array("checkbox"))) {
            $options["input"] = "";
        }
        if (isset($options["input"])) { return $options["input"]; }
        $options["input"] = parent::input($options["field"], array(
            "div" => false,
            "class" => $options["class"],
            "label" => false
        ));
        return $options["input"];
    }


    /**
     * search
     *
     * @param mixed $name
     * @param array $options
     * @access public
     * @return void
     */
    public function search($name = null, $options = array()) {
        $class = "search-query";
        if (!$name) {
            $name = "search";
        }
        if (isset($options["class"])) {
            $options["class"] .= " {$class}";
        } else {
            $options["class"] = $class;
        }
        return $this->text($name, $options);
    }

    /**
     * Takes an array of options to output markup that works with
     * twitter bootstrap forms.
     *
     * @param array $options
     * @access public
     * @return string
     */
    public function input($field, $options = array()) {
        $options = $this->_parseInputOptions($field, $options);
        if (!isset($options['field'])) { return ''; }
        $out = $help_inline = $help_block = '';
		
        if ($options['label'] === false) {
            $options['label'] = '';
        } else if (!empty($options['label'])) {
            $options['label'] = $this->label(
                $options['field'],
                $options['label'],
                "control-label"
            );
        } else {
            $options['label'] = $this->label(
                $options['field'],
                null,
                "control-label"
            );
        }
		if($this->_formHorizontal === true) {
			$options['label'] = $this->Html->tag('div', $options['label'], array('class' => "col-md-{$this->_horizontalSeparation}"));
		}
		
        list($help_inline, $help_block) = $this->_helpMarkup($options);
        if ($this->error($options['field'])) {
            $options['state'] = 'error';
            $help_block = $this->Html->tag(
                "span",
                $this->error($options['field']),
                array("class" => "help-block")
            );
        }
		
		$options['input'] = $this->_combineInput($options);
        
		
		$input = $options['input'].$help_inline.$help_block;
		if($this->_formHorizontal === true) {
			$input = $this->Html->tag('div', $input, array('class' => "col-md-" . (12 - $this->_horizontalSeparation)));
		}
		
        $wrap_class = "form-group";
        if ($options["state"] !== false) {
            $wrap_class = "{$wrap_class} has-{$options["state"]}";
        }
        return $this->Html->tag(
            "div",
            $options['label'].$input,
            array("class" => $wrap_class)
        );
    }

    /**
     * Takes the array of options and will apply the append or prepend bits
     * from the options and returns the input string.
     *
     * @param mixed $input
     * @param string $type
     * @access public
     * @return string
     */
    public function _combineInput($options) {
        $combine_markup = array("append" => "", "prepend" => "");
        $input = "";
        if (isset($options["input"])) {
            $input = $options["input"];
        } else {
            $opt = array("div" => false, "label" => false, "error" => false);
            foreach ($options as $key => $value) {
                if (!in_array($key, $this->__dontSendToFormHelper)) {
                    if ($key !== 'type' || !empty($value)) $opt[$key] = $value;
                }
            }
			/**
			 * This allows Bootstrap 3.0 new datepicker
			 */
			$validDateInput = array('date', 'month', 'day', 'time');
			$input = (isset($options['type']) && in_array($options['type'], $validDateInput)) ? $this->_dateInput($options['field'], $opt) : $input = parent::input($options["field"], $opt);
            
            if (isset($options["checkbox_label"])) {
                $input = $this->label($options["field"], $input.' '.$options["checkbox_label"], array('class' => 'checkbox'));
            }
        }
		
        foreach (array_keys($combine_markup) as $combine) {
            if (isset($options[$combine]) && !empty($options[$combine])) {
				$class = 'input-group-addon';
                if (strpos($options[$combine], "btn") !== false) {
                    $class = 'input-group-btn';
                }
				
				$combine_markup[$combine] = $this->Html->tag(
					'span',
					$options[$combine],
					array("class" => $class)
				);
            }
        }
		
        if (!empty($combine_markup["append"])) {
            $input = $input . $combine_markup["append"];
        }
        if (!empty($combine_markup["prepend"])) {
            $input = $combine_markup["prepend"] . $input;
        }
        if (!empty($combine_markup["append"]) || !empty($combine_markup["prepend"])) {
            $input = $this->Html->tag(
                "div",
                $input,
                array("class" => trim("input-group"))
            );
        }
        return $input;
    }
    
    /**
     * Bootstrap 3.0 date input
     */
    public function _dateInput($field, $opt) {
        $type = $opt['type'];
        $opt['type'] = 'text';
        $input = parent::input($field, $opt);
        return str_replace('type="text"', 'type="'.$type.'"', $input);
    }

    /**
     * Takes the options from the input method and returns an array of the
     * inline help and inline block content wrapped in the appropriate markup.
     *
     * @param mixed $options
     * @access public
     * @return string
     */
    public function _helpMarkup($options) {
        $help_markup = array("help_inline" => "", "help_block" => "");
        foreach (array_keys($help_markup) as $help) {
            if (isset($options[$help]) && !empty($options[$help])) {
                $help_class = str_replace("_", "-", $help);
                $help_markup[$help] = $this->Html->tag(
                    "span",
                    $options[$help],
                    array("class" => $help_class)
                );
            }
        }
        return array_values($help_markup);
    }

    /**
     * Outputs a list of radio form elements with the proper
     * markup for twitter bootstrap styles
     *
     * @param array $options
     * @access public
     * @return string
     */
    public function radio($field, $options = array(), $attributes = array()) {
        if (is_array($field)) {
            $options = $field;
        } else {
            $options["field"] = $field;
        }
        if (!isset($options["options"]) || !isset($options["field"])) {
            return "";
        }
        $opt = $options["options"];
        unset($options["options"]);
        $inputs = "";
        $hiddenField = (isset($options['hiddenField']) && $options['hiddenField']);
        foreach ($opt as $key => $val) {
            $input = parent::radio(
                $options["field"],
                array($key => $val),
                array("label" => false, 'hiddenField' => $hiddenField)
            );
            $id = array();
            preg_match_all("/id=\"[a-zA-Z0-9_-]*\"/", $input, $id);
            if (!empty($id[0])) {
                $id = end($id[0]);
                $id = substr($id, 4);
                $id = substr($id, 0, -1);
                $input = $this->Html->tag(
                    "label",
                    $input,
                    array("class" => "radio", "for" => $id)
                );
            }
            $inputs .= $input;
        }
        $options["input"] = $inputs;
        return $this->input($options);
    }

    /**
     * Wraps the form button method and just applies the Bootstrap classes to
     * the button before passing the options on to the FormHelper button method.
     *
     * @param string $value
     * @param array $options
     * @access public
     * @return string
     */
    public function button($value = "Submit", $options = array()) {
        $options = $this->buttonOptions($options);
        return parent::button($value, $options);
    }

    /**
     * Wraps the postLink method to create post links that use the bootstrap
     * button styles.
     *
     * @param mixed $title
     * @param mixed $url
     * @param array $options
     * @param mixed $confirm
     * @access public
     * @return string
     */
    public function buttonForm($title, $url, $opt = array(), $confirm = false) {
        $opt = $this->buttonOptions($opt);
        return $this->postLink($title, $url, $opt, $confirm);
    }

    /**
     * Takes the array of options from $this->button or $this->button_link
     * and returns the modified array with the bootstrap classes
     *
     * @param mixed $options
     * @access public
     * @return string
     */
    public function buttonOptions($options) {
        $valid_styles = array(
            "danger", "info", "primary", "warning", "success", "default",
        );
        $valid_sizes = array("xs", "sm", "lg");
        $style = isset($options["style"]) ? $options["style"] : "default";
        $size = isset($options["size"]) ? $options["size"] : "";
        $disabled = (isset($options["disabled"]) && $options["disabled"] === true) ? true : false;
		
        $class = "btn";
        if (!empty($style) && in_array($style, $valid_styles)) {
            $class .= " btn-{$style}";
        }
        if (!empty($size) && in_array($size, $valid_sizes)) {
            $class .= " btn-{$size}";
        }
        if ($disabled) { $class .= " disabled"; }
        unset($options["style"]);
        unset($options["size"]);
        unset($options["disabled"]);
        if (isset($options["class"])) {
            $options["class"] = $options["class"] . " " . $class;
        } else {
            $options["class"] = $class;
        }
        return $options;
    }

}
