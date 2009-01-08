<?php

class StandardWidgets {
	public static function navContainer($args = array(), $innerContent = '') {
		$attributes = '';
		foreach ($args as $name => $value) {
			if ($name != 'class') {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		return '<ul class="nav'.(key_exists('class', $args) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</ul>';
	}
	
	public static function navitemContainer($args = array(), $innerContent = '') {
		$reservedAttributes = array('url', 'current');
		// add the current class if set
		if (isset($args['current']) && $args['current'] == 'true') {
			if (isset($args['class'])) {
				$args['class'] = 'current-nav '.$args['class']; 
			} else {
				$args['class'] = 'current-nav';
			}
		}
		$attributes = '';
		foreach ($args as $name => $value) {
			if (!in_array($name, $reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		return '<li'.$attributes.'>'.(isset($args['url']) ? '<a href="'.htmlentities($args['url']).'">' : '').$innerContent.(isset($args['url']) ? '</a>' : '').'</li>';
	}
	
	public static function subnavContainer($args = array(), $innerContent = '') {
		$attributes = '';
		foreach ($args as $name => $value) {
			if ($name != 'class') {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		return '<ul class="subnav'.(key_exists('class', $args) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</ul>';
	}
	
	public static function bannerTag ($args = array()) {
		$reservedAttributes = array('class', 'src', 'title', 'subtitle');
		$attributes = '';
		foreach ($args as $name => $value) {
			if (!in_array($name, $reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		if (isset($args['src']) && file_exists($_SERVER['DOCUMENT_ROOT'].$args['src'])) {
			$showImage = true;	
		} else {
			$showImage = false;
		}
		return '<div class="banner'.(key_exists('class', $args) ? ' '.$args['class'] : '').'"'.$attributes.'>'.($showImage ? '<img src="'.htmlentities($args['src']).'" alt="'.(isset($args['title']) ? htmlentities($args['title']) : 'Logo').'" />' : '').(isset($args['title']) ? '<h1>'.htmlentities($args['title']).'</h1>' : '').(isset($args['subtitle']) ? '<h2>'.htmlentities($args['subtitle']).'</h2>' : '').'</div>';
	}
	
	public static function tabsContainer($args = array(), $innerContent = '') {
		// set some defaults.
		if (!$args['id']) {
			$args['id'] = 'tabs-'.time();
		}
		if (!$args['width'] || !is_numeric($args['width'])) {
			$args['width'] = '300';
		}
		$reservedAttributes = array('class', 'width', 'height', 'autoheight', 'current', 'frame', 'hideborders');
		$attributes = '';
		foreach ($args as $name => $value) {
			if (!in_array($name, $reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		$retval = '
			<script>
				tabs = new Array();
				tabContent = new Array();
			</script>
		';
		$retval .= '<div class="tabs'.(key_exists('class', $args) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</div>';
		$retval .= "
			<script>
				activeTab = ".($args['current'] ? "tabs.indexOf('".$args['current']."')" : '0').";
				Ext.onReady(function(){
				    var tabpanel = new Ext.TabPanel({
				        renderTo: '".$args['id']."',
				        width: ".$args['width'].",
				        ".($args['height'] && is_numeric($args['height']) ? "height: ".$args['height']."," : '')."
				        activeTab: activeTab,
				        ".($args['frame'] && $args['frame'] == 'true' ? "frame:true," : "frame:false,")." ,
				        ".($args['hideborders'] && $args['hideborders'] == 'true' ? "hideBorders:true," : "hideBorders:false,")." ,
				        ".($args['autoheight'] && $args['autoheight'] == 'true' ? 'defaults:{autoHeight: true},' : '')."
				        items:tabContent
				    });
				});
			</script>
		";
		return $retval;
	}
	
	public static function tabContainer($args = array(), $innerContent = '') {
		// set some defaults.
		if (!$args['id']) {
			$args['id'] = 'tab-'.time();
		}
		if (!$args['label']) {
			$args['label'] = 'My Tab';
		}
		$reservedAttributes = array('id', 'label');
		$attributes = '';
		foreach ($args as $name => $value) {
			if (!in_array($name, $reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		$retval = '
			<script>
				htmlCode'.preg_replace('/[.:-]/', '_', $args['id']).' = html_entity_decode(\''.htmlentities(str_replace("\n", '', str_replace('\'', "\\'", $innerContent))).'\');
				tabs[] = "'.$args['id'].'";
				tabContent[] = {title:\''.str_replace("'", "\\'",$args['label']).'\',html: htmlCode'.preg_replace('/[.:-]/', '_', $args['id']).'};
			</script>
		';
		return $retval;
	}
	
	public static function panelContainer($args = array(), $innerContent = '') {
		// set some defaults.
		if (!$args['id']) {
			$args['id'] = 'panel-'.time();
		}
		if (!$args['label']) {
			$args['label'] = 'Untitled Panel';
		}
		if (!$args['width']) {
			$args['width'] = '300';
		}
		$reservedAttributes = array('id','label', 'width', 'collapsible');
		$attributes = '';
		foreach ($args as $name => $value) {
			if (!in_array($name, $reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		//echo str_replace("\t", '', str_replace("\n", '', $innerContent)); die;
		/*
		echo $fixedString.'<br />';
		for($i = 0; $i < strlen($fixedString); $i++) {
			echo ord(substr($fixedString, $i, 1)).' ';
		}
		die;*/
		$retval = '<div id="'.$args['id'].'"'.$attributes.'></div><script>htmlCode'.preg_replace('/[.:-]/', '_', $args['id']).' = html_entity_decode(\''.htmlentities(str_replace("\n", '', str_replace('\'', "\\'", $innerContent))).'\'); ';
		$retval .= "Ext.onReady(function(){
					    var p = new Ext.Panel({
					        title: '".htmlentities($args['label'])."',
					        ".($args['collapsible'] && $args['collapsible'] == 'true' ? 'collapsible:true,' : 'collapsible: false,')."
					        renderTo: '".$args['id']."',
					        width:".$args['width'].",
					        html: htmlCode".preg_replace('/[.:-]/', '_', $args['id'])." 
					    });
					});";
		$retval .= '</script>';
		return $retval;
	}
	
	public static function dashboardContainer($args = array(), $innerContent = '') {
		
	}
	
	public static function treeObject($args = array(), $innerXML = '') {
		
	}
	
	public static function gridObject($args = array(), $innerXML = '') {
		
	}
	
	public static function filterContainer($args = array(), $innerContent = '') {
		$attributes = '';
		foreach ($args as $name => $value) {
			if ($name != 'class') {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		return '<div class="filter'.(key_exists('class', $args) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'<br class="clearer" /></div>';
	}
	
	public static function headerContainer($args = array(), $innerContent = '') {
		$attributes = '';
		foreach ($args as $name => $value) {
			if ($name != 'class') {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		return '<div class="header'.(key_exists('class', $args) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'<br class="clearer" /></div>';
	}
	
	public static function footerContainer($args = array(), $innerContent = '') {
		$attributes = '';
		foreach ($args as $name => $value) {
			if ($name != 'class') {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		return '<div class="footer'.(key_exists('class', $args) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'<br class="clearer" /></div>';
	}
	
	public static function contentContainer($args = array(), $innerContent = '') {
		$attributes = '';
		foreach ($args as $name => $value) {
			if ($name != 'class') {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		return '<div class="content'.(key_exists('class', $args) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'<br class="clearer" /></div>';
	}
	
	public static function layoutContainer($args = array(), $innerContent = '') {
		
	}
	
	public static function toolbarObject($args = array(), $innerXML = '') {
		
	}
	
	public static function menuObject($args = array(), $innerXML = '') {
		
	}
	
	public static function textTag ($args = array()) {
		
	}
	
	public static function passwordTag ($args = array()) {
		
	}
	
	public static function dateTag ($args = array()) {
		
	}
	
	public static function selectContainer($args = array(), $innerContent = '') {
		
	}
	
	public static function optionContainer($args = array(), $innerContent = '') {
		
	}
	public static function fileTag ($args = array()) {
		
	}
	
	public static function checkboxTag ($args = array()) {
		
	}
	
	// this needs to be an object because the inner checkbox elements depend on the outer checkboxes element for their name/id
	public static function checkboxesObject($args = array(), $innerXML = '') {
		
	}
	
	public static function radiosObject($args = array(), $innerXML = '') {
		
	}
	
	public static function textareaContainer($args = array(), $innerContent = '') {
		
	}
	
	public static function submitTag ($args = array()) {
		
	}
	
	public static function buttonTag ($args = array()) {
		
	}
	
	public static function setObject($args = array(), $innerXML = '') {
		
	}
}