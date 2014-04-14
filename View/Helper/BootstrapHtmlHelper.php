<?php

class BootstrapHtmlHelper extends TwitterBootstrapHelper {

    public $helpers = array("Html", "TwitterBootstrap.Bootstrap", "TwitterBootstrap.BootstrapForm", "Form");

    /**
     * Arma un botón a aprtir sólo del array
     * de opciones
     * @param mixed $button Array "URL_LINK" o HTML armado
     * @param string $type Puede ser 'post' o 'link' (default)
     */
    public function _buttonFromArray($button, $type = 'link') {
        list($title, $url, $opts, $confirm, $type) = $this->_parseLinkArray($button, $type);
        $button = ($type == 'link') ? $this->buttonLink($title, $url, $opts, $confirm) : $this->BootstrapForm->buttonForm($title, $url, $opts, $confirm);
        return $button;
    }
    
    
    protected function _linkFromArray($button, $type = 'link') {
        list($title, $url, $opts, $confirm, $type) = $this->_parseLinkArray($button, $type);
        $button = ($type == 'link') ? $this->Html->link($title, $url, $opts, $confirm) : $this->Form->postLink($title, $url, $opts, $confirm);
        return $button;
    }
    
    public function _parseLinkArray($button, $type = 'link') {
        if(isset($button['link']) || isset($button['post'])) {
            $type = array_keys($button)[0];
            $button = $button[$type];
        }
        
        if(is_array($button[0])) {
            $title = '';
            foreach($button[0] as $element => $config) {
                switch($element) {
                    case 'icon':
                        $name  = is_array($config) ? $config[0] : $config;
                        $style = isset($config[1]) ? $config[1] : array();
                        $title .= $this->Bootstrap->icon($name, $style);
                        break;
                    case 'text':
                        $title .= ' '.$config;
                        break;
                    case 'label':
                        $style = isset($config[1]) ? $config[1] : null;
                        $title .= $this->Bootstrap->label($config[0], $style);
                        break;
                }
            }
            /**
             * If there's no text inside the <a></a>, buttons
             * are smaller. So if it's just an iconed or labeled button
             * a non-blank space is added before and after (so that the icon or label
             * stays centered)
             */
            if(!isset($button[0]['text'])) $title = "&nbsp;$title&nbsp;";
        } else {
            $title = $button[0];
        }
        
        $url = $button[1];
        $opts = isset($button[2]) ? $button[2] : array();
        $confirm = isset($button[3]) ? $button[3] : array();
        $opts['escape'] = false;
        return array($title, $url, $opts, $confirm, $type);
    }
    
    /**
     * Bootstrap 3.0 panels
     * @param array $options
     */
    public function start_panel($options = array()){
        $html = ' ';
        $panel_class = array();
        $panel_body_class = array();
        $panel_body_class[] = (isset($options['padding']) && $options['padding']) ? '' : 'nopadding';
        
        $valid_styles = array(
            'primary', 'success', 'warning', 'info', 'danger', 'default'
        );
        
        
        if(isset($options['class']) && !empty($options['class'])) {
            $panel_class[] = $options['class'];
        } else {
            $panel_class[] = "panel-default";
        }
        
        if(isset($options['style']) && in_array($options['style'], $valid_styles)) {
            $panel_class[] = 'panel-' . $options['style'];
        }
        
        if(!isset($options['id'])) {
            $html .= '<div class="panel '. implode(' ', $panel_class) .'"><div class="panel-heading">';
        } else {
            $html .= '<div id="'.$options['id'].'" class="panel '. implode(' ', $panel_class) .'"><div class="panel-heading">';
        }
    
    
        // PANEL HEADING
        $title = '';
        
        /**
         * Panel Icon (pull-left)
         */
        if(isset($options['icon'])) {
            if(is_array($options['icon'])) {
                $icon_options = isset($options['icon'][1]) ? $options['icon'][1] : array();
                $title .= '<span class="icon">' . $this->Bootstrap->icon($options['icon'][0], $icon_options) . '</span>';
            } else {
                $title .= '<span class="icon">' . $options['icon'] . '</span>';
            }
        }
        
        /**
         * Panel Title
         */
        if(isset($options['title'])) $title .= $this->Html->tag('strong', $options['title']);
        
        
        /**
         * Panel Label (pull right)
         */
        if(isset($options['label'])) {
            if(is_array($options['label'])) {
                foreach($options['label'] as $label) {
                    if(is_array($label)) {
                        $style = (isset($label[1])) ? $label[1] : '';
                        $opts = (isset($label[2])) ? $label[2] : array();
                        if(isset($opts['class'])) $opts['class'] .= " pull-right"; else $opts['class'] = "pull-right";
                        $title .= $this->Bootstrap->label($label[0], $style, $opts);
                    } else {
                        $title .= $label;
                    }
                }
            } else {
                $title .= $options['label'];
            }
        }
        
        /**
         * Panel buttons (pull-right)
         */
        if(isset($options['buttons'])) {
            $title .= '<div class="btn-group pull-right">';
            foreach($options['buttons'] as $button) {
                
                if(empty($button)) {
                    $button = '</div><div class="buttons btn-group pull-right">';
                } else {
                    if(isset($button['dropdown'])) {
                        if(!isset($button['dropdown']['size'])) $button['dropdown']['size'] = $this->defaultPanelButtonSize;
                        $button = $this->_fillDropdown($button['dropdown']);
                    } elseif(isset($button['html'])) {
                        $button = $button['html'];
                    } else {
                        $buttonType = array_keys($button)[0];
                        if($buttonType !== 0) {
                            if(!isset($button[$buttonType][2]['size'])) $button[$buttonType][2]['size'] = $this->defaultPanelButtonSize;
                        } else {
                            if(!isset($button[2]['size'])) $button[2]['size'] = $this->defaultPanelButtonSize;
                        }
                        $button = $this->_buttonFromArray($button);
                    }
                }
                
                $title .= $button;
            }
            $title .= '</div>';
        }
        
        $html .= $title;
        $html .= '</div>';
        
        // PANEL BODY:
        
        $html .= '<div class="panel-body '.implode(' ', $panel_body_class).'">';
        
        return $html;
    }
    
    public function start_panel_footer() {
        return '</div><div class="panel-footer">';
    }
    
    public function end_panel($options = array()){
        return '</div></div>';
    }

    /**
     * Builds a dropdown button with it's links.
     * @param   array   $options Posible options:
     *  split => boolean Defines if the dropdown button should have it's own href and make another button for caret (default is false)
     *  style => string Twitter Bootstrap styles (valid are: 'default', 'primary', 'warning', 'danger', 'info') (default is 'default')
     *  size => string Twitter Bootstrap sizes (valid are: 'xs', 'sm', 'lg', null) (default is null)
     *  navbar => boolean If it's a navbar dropdown dropdown toggler button should be a link
     *  links => array Array containing link arrays (see $this->_linkFromArray)
     */
    public function _fillDropdown($options = array()) {
        /**
         * Makes button or buttons (if split) for calling dropdown:
         */
        $split  = (isset($options['split']) && $options['split'] === true) ? true : false;
        $style  = (isset($options['style'])) ? $options['style'] : null; //actual default is given in $this->_buttonFromArray
        $size   = (isset($options['size'])) ? $options['size'] : null; //ditto
        $navbar = (isset($options['navbar']) && $options['navbar'] === true) ? true : false;
        
        
        $button = '';
        if(isset($options['first']['link'])) {
            $options['first']['link'][2]['style'] = $style;
            $options['first']['link'][2]['size'] = $size;
            $buttonArray = $options['first']['link'];
            $type = 'link';
        } elseif(isset($options['first']['post'])) {
            $options['first']['post'][2]['style'] = $style;
            $options['first']['post'][2]['size'] = $size;
            $buttonArray = $options['first']['post'];
            $type = 'post';
        } elseif(isset($options['first']['title'])) {
            $buttonArray = array(
                $options['first']['title'],
                '#',
                array(
                    'style' => $style,
                    'size' => $size,
                ),
            );
            $type = 'link';
        } else {
            $buttonArray = array(array('text' => ''), '#', array('style' => $style, 'size' => $size));
            $type = 'link';
        }
        
        if(!isset($buttonArray[0]['text'])) $buttonArray[0]['text'] = '';
        if(!$split){
            $buttonArray[0]['text'] .= '&nbsp;' . $this->Html->tag('span', '', array('class' => 'caret'));
            $buttonArray[2]['class'] = (isset($buttonArray[2]['class'])) ? $buttonArray[2]['class'] . ' dropdown-toggle' : 'dropdown-toggle';
            $buttonArray[2]['data-toggle'] = 'dropdown'; 
        }
        
        $button = ($navbar === false) ? $this->_buttonFromArray($buttonArray, $type) : $this->_linkFromArray($buttonArray, $type);
        
        if($split) {
            $caretClasses = array(
                'btn',
                'dropdown-toggle',
            );
            if($style) array_push($caretClasses, 'btn-'.$style); else array_push($caretClasses, 'btn-default');
            if($size) array_push($caretClasses, 'btn-'.$size);
            $button .= '<button class="'.implode(' ', $caretClasses).'" data-toggle="dropdown"><span class="caret"></span></button>';
        }
        
        //Dropdown links:
        $link_li = "";
        
        foreach($options['links'] as $link) {
            if(is_array($link)){
                $link_li .= (!empty($link)) ? $this->Html->tag('li', $this->_linkFromArray($link)) : $this->Html->tag('li', '', array('class' => 'divider'));
            } else {
                $link_li .= $this->Html->tag('li', $link);
            }
        }
        return $button . $this->Html->tag('ul', $link_li, array('class' => 'dropdown-menu'));
    }
    
    
    
    /**
     * Builds a button dropdown menu with the $value as the button text and the
     * "links" option as the dropdown items
     * @param  string $value
     * @param  array  $options
     * @return string
     */
    public function buttonDropdown($options = array()) {
        $class= array('btn-group');
        if(isset($options['dropup']) && $options['dropup']) array_push($class, 'dropup');
       
        return $this->Html->tag('div', $this->_fillDropdown($options), array('class' => implode(' ', $class)));
    }

    

    /**
     * Wraps the html link method and applies the Bootstrap classes to the
     * options array before passing it on to the html link method.
     *
     * @param mixed $title
     * @param mixed $url
     * @param array $options
     * @param mixed $confirm
     * @access public
     * @return string
     */
    public function buttonLink($title, $url, $opt = array(), $confirm = false) {
        $opt = $this->buttonOptions($opt);
        return $this->Html->link($title, $url, $opt, $confirm);
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
            "danger", "info", "primary",
            "warning", "success", "link",
        );
        $valid_sizes = array("xs", "sm", "lg");
        $style = isset($options["style"]) ? $options["style"] : "";
        $size = isset($options["size"]) ? $options["size"] : "";
        $disabled = false;
        if (isset($options["disabled"])) {
            $disabled = (bool)$options["disabled"];
        }
        $class = "btn";
        if (!empty($style) && in_array($style, $valid_styles)) {
            $class .= " btn-{$style}";
        } else {
            $class .= " btn-default";
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

    public function breadcrumbs($options = array()) {
        $crumbs = $this->Html->getCrumbs("%%", null);
        $crumbs = explode("%%", $crumbs);
        $out = "";
        
        $lastCrumb = array_pop($crumbs);
        
        foreach($crumbs as $crumb) {
            $out .= $this->Html->tag('li', $crumb);
        }
        $out .= $this->Html->tag('li', $lastCrumb, array('class' => 'active'));
        
        return $this->Html->tag('ol', $out, array('class' => 'breadcrumb'));
    }
    
    /**
     * Builds Navbar based on $options = array():
     * Options include:
     * collapsable  boolean default true.
     * inverse      boolean default true
     * fixed        mixed   default false. Can be string "bottom", "top" or "static"
     * id           string  default (string)time(). Required for collapsable.
     * brand        array   default false. Can be a string for the brand part of the Navbar
     * container    boolean default true. Defines if navbar content should be inside a <div class="container">
     * buttons      array   Same array as buttons for Panel. Except you can add a "right" key containing buttons that sould be pulled right
     * class        string  Aditional classes
     */
    public function navbar($options = array()) {
        $defaults = array(
            'collapsable' => true,
            'inverse' => false,
            'fixed' => false, //top, bottom, static-top
            'id' => (string)time(),
            'brand' => array(
                'title' => '',
                'href' => '#',
            ),
            'container' => true,
            'buttons' => array(
                'right' => array(),
            ),
            'class' => '',
        );
        
        if(isset($options['brand']) && is_array($options['brand'])) $options['brand'] = array_merge($defaults['brand'], $options['brand']);
        if(isset($options['buttons']) && is_array($options['buttons'])) $options['buttons'] = array_merge($defaults['buttons'], $options['buttons']);
        
        $options = array_merge($defaults, $options);
        
        $class = array('navbar');
        $class[] = ($options['inverse'] === true) ? 'navbar-inverse' : 'navbar-default';
        if($options['fixed'] !== false) {
            $valid = array('top', 'bottom', 'static-top');
            if(in_array($options['fixed'], $valid)) {
                switch($options['fixed']) {
                    case 'top':
                    case 'bottom':
                        $class[] = 'navbar-fixed-' . $options['fixed'];
                        break;
                    case 'static-top':
                        $class[] = $options['fixed'];
                        break;
                }
            }
        }
        
        /**
         * HEADER
         */
        $brand = (is_array($options['brand'])) ? $this->Html->link($options['brand']['title'], $options['brand']['href'], array('class' => 'navbar-brand')) : '';
        if($options['collapsable'] === false) {
            $header = '';
        } else {
            $header = <<<EOT
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-{$options['id']}">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
EOT;
        }
        $header .= $brand;
        $header = $this->Html->tag('div', $header, array('class' => 'navbar-header'));
        
        /**
         * BUTTON
         * (and other things inside navbar)
         */
        $buttons_right  = '';
        $buttons_left   = '';
        if(is_array($options['buttons'])) {
            $right = $options['buttons']['right'];
            unset($options['buttons']['right']);
            
            $buttons_left = $this->Html->tag('ul', $this->_nav_buttons_html($options['buttons']), array('class' => 'nav navbar-nav'));
            
            if(is_array($right)) {
                $buttons_right = $this->Html->tag('ul', $this->_nav_buttons_html($right), array('class' => 'nav navbar-nav navbar-right'));
            }
        }
        $buttons_class = ($options['collapsable'] === false) ? '' : 'collapse navbar-collapse';
        $buttons = $this->Html->tag('div', $buttons_left . $buttons_right, array('class' => $buttons_class, 'id' => 'navbar-'.$options['id']));
        
        /**
         * Contents of the navbar
         */
        $container = ($options['container'] === true) ? $this->Html->tag('div', $header.$buttons, array('class' => 'container')) : $header.$buttons;
        
        /**
         * Return Navbar
         */
        return $this->Html->tag('nav', $container, array('class' => implode(' ', $class) , 'role' => 'navigation'));
    }
    
    /**
     * Makes <li><a>navbar link</a></li>
     * Params
     * @param array $buttons ButtonsArray
     * @return <li><a>navbar link</a></li><li><a>navbar link</a></li><li><a>navbar link</a></li><li><a>navbar link</a></li>
     */
    protected function _nav_buttons_html($buttons = array()) {
        $buttons_html = '';
        foreach($buttons as $button) {
            
            if(isset($button['dropdown'])) {
                $button = $button['dropdown'];
                $id = '';
                if(isset($button['li_id'])) {
                    $id = $button['li_id'];
                    unset($button['li_id']);
                }
                $button['navbar'] = true;
                $buttons_html .= $this->Html->tag('li', $this->_fillDropdown($button), array('class' => 'dropdown', 'id' => $id));
            } elseif(isset($button['html'])) {
                $buttons_html .= $button['html'];
            } else {
                $id = $this->get_link_property($button, 'li_id', true);
                $buttons_html .= $this->Html->tag('li', $this->_linkFromArray($button), array('id' => $id));
            }
            
        }
        return $buttons_html;
    }
    
    /** Returnts the value of $property key from the $options array form a LINK_ARRAY.
     * Optionally unsets the key
     * Arguments:
     * @param LINK_ARRAY    &$button    Reference to the LINK_ARRAY
     * @param string        $property   Property to look for
     * @param boolean       $extract    If the key should be unset
     * @return mixed        Returns the value for the property or '' if not found
     */
    protected function get_link_property(&$button, $property, $extract = false) {
        $value = '';
        $type = array_keys($button)[0];
        
        if($type === 0) {
            if(isset($button[2][$property])) {
                $value = $button[2][$property];
                if($extract === true) unset($button[2][$property]);
            }
        } else {
            if(isset($button[$type][2][$property])) {
                $value = $button[$type][2][$property];
                if($extract === true) unset($button[$type][2][$property]);
            }
        }
        return $value;
    }
    
    /**
     * Creates a nav based on $options
     * Options include:
     * style        string  Style of the NAV. Can be 'pills' (default) or 'tabs'
     * justified    boolean Justified class for non-stacked navs. Default is false
     * stacked      boolean Stacked class for non-justified navs. Default is false
     * buttons      array   Array filled with LINK_ARRAY (with the addition of 'li_id' key)
     * class        string  Additional classes
     */
    public function nav($options = array()) {
        $defaults = array(
            'style' => 'pills', //'pills', 'tabs'
            'justified' => false,
            'stacked' => false,
            'buttons' => array(), //Array with LINK_ARRAY
            'class' => '', //Aditional classes
        );
        $options = array_merge($defaults, $options);
        if(!is_array($options['buttons']) || empty($options['buttons'])) return false;
        
        $class = "nav nav-" . $options['style'];
        if($options['justified'] === true) $class .= ' nav-justified';
        if($options['stacked'] === true) $class .= ' nav-stacked';
        if(!empty($options['class'])) $class .= ' '.$options['class'];
        return $this->Html->tag('ul', $this->_nav_buttons_html($options['buttons']), $class);
    }
}

