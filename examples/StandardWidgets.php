<?php

class StandardWidgets {

	private static function getAdditionalAttributes($args = array(), $reservedAttributes = array())
	{
		$attributes = '';
		foreach ($args as $name => $value) {
			if (!in_array($name, $reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}

		return $attributes;
	}

	public static function navContainer($args = array(), $innerContent = '') {
		$attributes = self::getAdditionalAttributes($args, array('class'));
		return '<ul class="nav'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</ul>';
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

		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		return '<li'.$attributes.'>'.(isset($args['url']) ? '<a href="'.htmlentities($args['url']).'">' : '').$innerContent.(isset($args['url']) ? '</a>' : '').'</li>';
	}

	public static function subnavContainer($args = array(), $innerContent = '') {
		$attributes = self::getAdditionalAttributes($args, array('class'));
		return '<ul class="subnav'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</ul>';
	}

	public static function bannerTag ($args = array()) {
		$reservedAttributes = array('class', 'src', 'title', 'subtitle');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		if (isset($args['src']) && file_exists($_SERVER['DOCUMENT_ROOT'].$args['src'])) {
			$showImage = true;
		} else {
			$showImage = false;
		}

		return '<div class="banner'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.($showImage ? '<img src="'.htmlentities($args['src']).'" alt="'.(isset($args['title']) ? htmlentities($args['title']) : 'Logo').'" />' : '').(isset($args['title']) ? '<h1>'.htmlentities($args['title']).'</h1>' : '').(isset($args['subtitle']) ? '<h2>'.htmlentities($args['subtitle']).'</h2>' : '').'</div>';
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
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval = '
			<script>
				stdWidgetTabs = new Array();
				stdWidgetTabContent = new Array();
			</script>
		';
		$retval .= '<div class="tabs'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</div>';
		$retval .= "
			<script>
				stdWidgetActiveTab = ".($args['current'] ? "stdWidgetTabs.indexOf('".$args['current']."')" : '0').";

				new Ext.TabPanel({
					renderTo: '".$args['id']."',
					width: ".$args['width'].",
					".($args['plain'] && $args['plain'] == 'false' ? "plain:false," : "plain:true,")."
					".($args['height'] && is_numeric($args['height']) ? "height: ".$args['height']."," : '')."
					activeTab: stdWidgetActiveTab,
					".($args['frame'] && $args['frame'] == 'true' ? "frame:true," : "frame:false,")."
					".($args['hideborders'] && $args['hideborders'] == 'true' ? "hideBorders:true," : "hideBorders:false,")."
					".($args['autoheight'] && $args['autoheight'] == 'true' ? 'defaults:{autoHeight: true},' : '')."
					items: stdWidgetTabContent
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
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval = "
			<script>
				stdWidgetTabs[stdWidgetTabs.length] = '".$args['id']."';
				stdWidgetTabContent[stdWidgetTabContent.length] = {
					title:'".str_replace("'", "\\'",$args['label'])."',
					html: html_entity_decode('".htmlentities(str_replace("\n", '', str_replace('\'', "\\'", $innerContent)))."'),
					cls: 'tab-heading'
				};
			</script>
		";
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
		if (!$args['embed'] || !$args['embed'] == 'true') {
			$args['embed'] = false;
		} else {
			$args['embed'] = true;
		}
		$reservedAttributes = array('id','label', 'width', 'collapsible', 'embed', 'class');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		if (!$args['embed']) {
			$retval = '<div id="'.$args['id'].'"'.$attributes.'></div><script>';
			$retval .= "    new Ext.Panel({
							title: '".htmlentities($args['label'])."',
							".($args['collapsible'] && $args['collapsible'] == 'true' ? 'collapsible:true,' : 'collapsible: false,')."
							".($args['class'] ? "cls: '".str_replace("'", "\\'", $args['class'])."'," : '')."
												renderTo: '".$args['id']."',
							width:".$args['width'].",
							html: html_entity_decode('".htmlentities(str_replace("\n", '', str_replace('\'', "\\'", $innerContent)))."')
						})
					";
			$retval .= ';</script>';
		} else {
			$retval .= "  ,{
							title: '".htmlentities($args['label'])."',
							".($args['collapsible'] && $args['collapsible'] == 'true' ? 'collapsible:true,' : 'collapsible: false,')."
												".($args['class'] ? "cls: '".str_replace("'", "\\'", $args['class'])."'," : '')."
												width:".$args['width'].",
							html: html_entity_decode('".htmlentities(str_replace("\n", '', str_replace('\'', "\\'", $innerContent)))."')
						}
					";
		}

		return $retval;
	}

	public static function dashboardContainer($args = array(), $innerContent = '') {
		if (!$args['id']) {
			$args['id'] = 'dashboard-'.time();
		}
		$reservedAttributes = array('id','margins');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval .= '<div id="'.$args['id'].'"'.$attributes.'></div>';
		$retval .= '<script>'."\n";
		$retval .= 'var stdWidgetColumns = new Array();'."\n";
		$retval .= '</script>'."\n";
		$retval .= $innerContent;
		$retval .= "<script>\n";
		$retval .= "
			new Ext.Panel({
				cls: 'dashboard',
				renderTo: '".$args['id']."',
				autoHeight: true,
				border: false,
				items: [{
					xtype:'portal',
					".($args['margins'] ? "margins:'".str_replace("'", "\\'", $args['margins'])."'," : '')."
					items: stdWidgetColumns
				}]
			});
			stdWidgetColumns = new Array();
		";
		$retval .= '</script>';
		return $retval;
	}

	public static function columnContainer($args = array(), $innerContent = '') {
		if (!$args['width'] || !is_numeric($args['width'])) {
			$args['width'] = '1';
		}
		$retval = "
			<script>
				var stdWidgetColumnElements = new Array();
				stdWidgetColumnElements = [ false
					".$innerContent."
				];
				stdWidgetColumnElements.shift();
				stdWidgetColumns[stdWidgetColumns.length] = {
										".($args['id'] ? "id: '".str_replace("'", "\\'", $args['id'])."'," : '')."
					".($args['class'] ? "cls: '".str_replace("'", "\\'", $args['class'])."'," : '')."
										border: false,
					columnWidth: ".$args['width'].",
					".($args['style'] ? "style: '".str_replace("'", "\\'", $args['style'])."'," : '')."
					items: stdWidgetColumnElements
				};
				var stdWidgetColumnElements = new Array();
			</script>
		";
		return $retval;
	}

	public static function treeObject($args = array(), $innerXML = '') {
		if (!$args['id']) {
			$args['id'] = 'tree-'.time();
		}
		$reservedAttributes = array('id','class');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval = '<div id="'.$args['id'].'"'.$attributes.'></div>'."\n".'<script>'."\n";
		$xml = new SimpleXMLElement($innerXML);
		$itemsString = '[';
		$firstFlag = true;
		foreach ($xml->children() as $child) {
			if ($firstFlag) {
				$firstFlag = false;
			} else {
				$itemsString .= ',';
			}

			// initialize based on node type
			if ($child->getName() == 'leaf') {
				$itemsString .= '{leaf:true';
				$className = 'tree-leaf';
			} else {
				$itemsString .= '{leaf:false';
				$className = 'tree-branch';
			}

			// do all of the parts that aren't node specific
			foreach ($child->attributes() as $name => $value) {
				switch ($name) {
					case 'class':
						$className = $value;
						break;
					case 'id':
						$itemsString .= ",id:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'href':
						$itemsString .= ",href:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'target':
						$itemsString .= ",hrefTarget:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'icon':
						$itemsString .= ",icon:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'label':
						$itemsString .= ",text:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'title':
						$itemsString .= ",qtip:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'disabled':
						$itemsString .= ",disabled:".($value == "true" ? 'true' : 'false');
						break;
					case 'onclick':
						$itemsString .= ",listeners:{'click':function(){".$value."},scope:this}";
						break;
				}
			}
			$itemsString .= ",cls:'".$className."'";

			// finalize based on node type
			if ($child->getName() == 'leaf') {
				$itemsString .= '}';
			} else {
				$itemsString .= ',children:['.self::treeRecursionHelper($child).']}';
			}
		}
		$itemsString .= ']';

		$retval .= "
			var Tree = Ext.tree;

			var tree = new Tree.TreePanel({
				el:'".$args['id']."',
				useArrows:true,
				".($args['class'] ? 'cls: '.$args['class'].',' : '')."
				autoScroll:true,
				animate:true,
				enableDD:true,
				containerScroll: true,
				rootVisible:false,
				root: {
					text: 'Root',
					draggable:false,
					id:'source',
					children:".$itemsString."
				}
			});

			// render the tree
			tree.render();
			</script>
		";
		return $retval;
	}

	public static function treeRecursionHelper($node) {
		$itemsString = '';
		$firstFlag = true;
		foreach ($node->children() as $child) {
			if ($firstFlag) {
				$firstFlag = false;
			} else {
				$itemsString .= ',';
			}

			// initialize based on node type
			if ($child->getName() == 'leaf') {
				$itemsString .= '{leaf:true';
				$className = 'tree-leaf';
			} else {
				$itemsString .= '{leaf:false';
				$className = 'tree-branch';
			}

			// do all of the parts that aren't node specific
			foreach ($child->attributes() as $name => $value) {
				switch ($name) {
					case 'class':
						$className = $value;
						break;
					case 'id':
						$itemsString .= ",id:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'href':
						$itemsString .= ",href:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'target':
						$itemsString .= ",hrefTarget:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'icon':
						$itemsString .= ",icon:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'label':
						$itemsString .= ",text:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'title':
						$itemsString .= ",qtip:'".str_replace("'", "\\'", $value)."'";
						break;
					case 'disabled':
						$itemsString .= ",disabled:".($value == "true" ? 'true' : 'false');
						break;
					case 'onclick':
						$itemsString .= ",listeners:{'click':function(){".$value."},scope:this}";
						break;
				}
			}
			$itemsString .= ",cls:'".$className."'";

			// finalize based on node type
			if ($child->getName() == 'leaf') {
				$itemsString .= '}';
			} else {
				$itemsString .= ',children:['.self::treeRecursionHelper($child).']}';
			}
		}
		return $itemsString;
	}

	public static function gridObject($args = array(), $innerXML = '') {
		if (!$args['id']) {
			$args['id'] = 'grid-'.time();
		}
		$reservedAttributes = array('id','class');
		$attributes = '';
		foreach ($args as $name => $value) {
			if (!in_array($name, $reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}
		$retval = '<div id="'.$args['id'].'"'.$attributes.'></div>'."\n".'<script>'."\n";
		$xml = new SimpleXMLElement($innerXML);

		$retval .= '</script>';
		return $retval;
	}

	public static function filterContainer($args = array(), $innerContent = '') {
		$attributes = self::getAdditionalAttributes($args, array('class'));

		return '<div class="filter'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'<br class="clearer" /></div>';
	}

	public static function headerContainer($args = array(), $innerContent = '') {
		$attributes = self::getAdditionalAttributes($args, array('class'));

		return '<div class="header'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'<br class="clearer" /></div>';
	}

	public static function footerContainer($args = array(), $innerContent = '') {
		$attributes = self::getAdditionalAttributes($args, array('class'));

		return '<div class="footer'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'<br class="clearer" /></div>';
	}

	public static function contentContainer($args = array(), $innerContent = '') {
		$attributes = self::getAdditionalAttributes($args, array('class'));

		return '<div class="content'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'<br class="clearer" /></div>';
	}

	public static function layoutContainer($args = array(), $innerContent = '') {

	}

	public static function toolbarObject($args = array(), $innerXML = '') {

	}

	public static function menuObject($args = array(), $innerXML = '') {

	}

	public static function textTag ($args = array()) {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('type', 'label', 'class'));
		$retval = '';
		if (isset($args['label'])) {
			$retval .= '<div class="form-element-container"><label for="'.$args['id'].'">'.$args['label'].'</label>';
		}
		$retval .= '<input type="text" class="form-text'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.' />';
		if (isset($args['label'])) {
			$retval .= '</div>';
		}
		return $retval;
	}

	public static function passwordTag ($args = array()) {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('type', 'label', 'class'));
		$retval = '';
		if (isset($args['label'])) {
			$retval .= '<div class="form-element-container"><label for="'.$args['id'].'">'.$args['label'].'</label>';
		}
		$retval .= '<input type="password" class="form-password'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.' />';
		if (isset($args['label'])) {
			$retval .= '</div>';
		}
		return $retval;
	}

	public static function dateTag ($args = array()) {

	}

	public static function selectContainer($args = array(), $innerContent = '') {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('label', 'class'));
		$retval = '';
		if (isset($args['label'])) {
			$retval .= '<div class="form-element-container"><label for="'.$args['id'].'">'.$args['label'].'</label>';
		}
		$retval .= '<select class="form-select'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</select>';
		if (isset($args['label'])) {
			$retval .= '</div>';
		}
		return $retval;
	}

	public static function optionContainer($args = array(), $innerContent = '') {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('class'));
		return '<option class="form-option'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</option>';
	}

	public static function fileTag ($args = array()) {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('type', 'label', 'class'));
		$retval = '';
		if (isset($args['label'])) {
			$retval .= '<div class="form-element-container"><label for="'.$args['id'].'">'.$args['label'].'</label>';
		}
		$retval .= '<input type="file" class="form-file'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.' />';
		if (isset($args['label'])) {
			$retval .= '</div>';
		}
		return $retval;
	}

	public static function checkboxTag ($args = array()) {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('type', 'label', 'class'));
		return '<input type="checkbox" class="form-checkbox'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.' /><label class="form-checkbox-label" for="'.$args['id'].'">'.$args['label'].'</label>';

	}

	// this needs to be an object because the inner checkbox elements depend on the outer checkboxes element for their name/id
	public static function checkboxesObject($args = array(), $innerXML = '') {

	}

	public static function radiosObject($args = array(), $innerXML = '') {

	}

	public static function textareaContainer($args = array(), $innerContent = '') {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('class', 'label'));
		$retval = '';
		if (isset($args['label'])) {
			$retval .= '<div class="form-element-container"><label for="'.$args['id'].'">'.$args['label'].'</label>';
		}
		$retval .= '<textarea class="form-textarea'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</textarea>';
		if (isset($args['label'])) {
			$retval .= '</div>';
		}
		return $retval;
	}

	public static function submitTag ($args = array()) {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('type', 'class', 'label'));
		$retval = '';
		if (isset($args['label'])) {
			$retval .= '<div class="form-element-container"><label for="'.$args['id'].'">'.$args['label'].'</label>';
		}
		$retval .= '<input type="submit" class="form-submit'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.' />';
		if (isset($args['label'])) {
			$retval .= '</div>';
		}
		return $retval;
	}

	public static function buttonTag ($args = array()) {
		if (!isset($args['id'])) $args['id'] = uniqid();
		$attributes = self::getAdditionalAttributes($args, array('type', 'class', 'label'));
		$retval = '';
		if (isset($args['label'])) {
			$retval .= '<div class="form-element-container"><label for="'.$args['id'].'">'.$args['label'].'</label>';
		}
		$retval .= '<input type="button" class="form-submit'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.' />';
		if (isset($args['label'])) {
			$retval .= '</div>';
		}
		return $retval;
	}

	public static function setObject($args = array(), $innerXML = '') {

	}
}
