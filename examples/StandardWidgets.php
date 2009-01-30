<?php

class StandardWidgets {

	private static function getAdditionalAttributes($args = array(), $reservedAttributes = array())
	{
		$attributes = '';
		foreach ($args as $name => $value) {
			if (!is_array($reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			} else if (!in_array($name, $reservedAttributes)) {
				$attributes .= ' '.$name.'="'.htmlentities($value).'"';
			}
		}

		return $attributes;
	}

	private static function clenseID($id) {
		return str_replace('-', '_', $id);
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
			$args['id'] = 'tabs-'.uniqid();
		}
		if (!$args['width'] || !is_numeric($args['width'])) {
			$args['width'] = '300';
		}
		if (isset($args['current'])) {
			$args['selectedTab'] = $args['current'];
		}
		$reservedAttributes = array('class', 'width', 'height', 'autoheight', 'current', 'frame', 'hideborders');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval = '
			<script type="text/javascript">
				dojo.require("dojo.parser");
				dojo.require("dijit.layout.ContentPane");
				dojo.require("dijit.layout.TabContainer");
			</script>
		';
		$retval .= '<div dojoType="dijit.layout.TabContainer"  class="tabs'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</div>';
		return $retval;
	}

	public static function tabContainer($args = array(), $innerContent = '') {
		// set some defaults.
		if (!$args['id']) {
			$args['id'] = 'tab-'.uniqid();
		}
		if (!$args['label']) {
			$args['title'] = 'My Tab';
		} else {
			$args['title'] = $args['label'];
		}
		$reservedAttributes = array('label');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval = '<div dojoType="dijit.layout.ContentPane"'.$attributes.'>'.$innerContent.'</div>';
		return $retval;
	}

	public static function panelContainer($args = array(), $innerContent = '') {
		// set some defaults.
		if (!$args['id']) {
			$args['id'] = 'panel-'.uniqid();
		}
		if (!$args['label']) {
			$args['title'] = 'Untitled Panel';
		} else {
			$args['title'] = $args['label'];
		}
		$reservedAttributes = array('label', 'class');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval = '
			<script type="text/javascript">
				dojo.require("dojo.parser");
				dojo.require("dijit.TitlePane");
			</script>
		';
		$retval .= '
			<div dojoType="dijit.TitlePane" class="panel'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</div>';
		return $retval;
	}

	public static function dashboardContainer($args = array(), $innerContent = '') {
		if (!$args['id']) {
			$args['id'] = 'dashboard-'.uniqid();
		}
		if (!$args['handles']) {
			$args['handles'] = 'true';
		}
		if (!$args['resizable']) {
			$args['resizable'] = 'false';
		}
		if (!$args['autoscroll']) {
			$args['autoscroll'] = 'true';
		}
		if (!$args['opacity']) {
			$args['opacity'] = '0.7';
		}
		if (!$args['zones']) {
			$args['zones'] = '3';
		}
		$reservedAttributes = array('class', 'handles', 'resizable', 'autoscroll', 'opacity', 'zones');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval = '
			<script type="text/javascript">
				dojo.require("dojo.parser");
				dojo.require("dojox.layout.GridContainer");
			</script>
		';

		$retval .= '<div dojoType="dojox.layout.GridContainer" nbZones="'.$args['zones'].'" opacity="0.7" allowAutoScroll="'.$args['autoscroll'].'" hasResizableColumns="'.$args['resizable'].'" withHandles="'.$args['handles'].'" acceptTypes="dijit.layout.ContentPane, dijit.TitlePane" class="dashboard'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</div>';

		return $retval;
	}

	public static function treeObject($args = array(), $innerXML = '') {
		if (!$args['id']) {
			$args['id'] = 'tree-'.uniqid();
		}
		$reservedAttributes = array('id','class');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);

		$retval = '
			<script  type="text/javascript">
				dojo.require("dojo.data.ItemFileReadStore");
				dojo.require("dijit.Tree");
				dojo.require("dojo.parser");
			</script>
		';

		$xml = new SimpleXMLElement($innerXML);
		$itemsString = '<div class="dojo-Tree">';
		foreach ($xml->children() as $child) {
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

		$retval .= '<div id="'.$args['id'].'"'.$attributes.'></div>'."\n".'<script>'."\n";
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
			$args['id'] = 'grid-'.uniqid();
		}
		// don't ask
		if ($args['height']) {
			if (isset($args['style'])) {
				$args['style'] .= '; height: '.$args['height'].'px;';
			} else {
				$args['style'] = 'height: '.$args['height'].'px;';
			}
		}
		if ($args['width']) {
			if (isset($args['style'])) {
				$args['style'] .= '; width: '.$args['width'].'px;';
			} else {
				$args['style'] = 'width: '.$args['width'].'px;';
			}
		}
		$reservedAttributes = array('id','class', 'label','height','width','striperows');
		$attributes = self::getAdditionalAttributes($args, $reservedAttributes);
		$retval = '<div id="'.$args['id'].'"'.$attributes.'></div>'."\n".'<script>'."\n";

		// now figure out what the grid looks like
		$xml = new SimpleXMLElement($innerXML);

		$columnTypes = array();
		$columnTypeDateFormatters = array();
		$autoExpandIndex = 0;
		$columnCount = 0;
		$headJSON = '';
		if (isset($xml->ghead)) {
			$headJSON .= '[';
			foreach ($xml->ghead->children() as $child) {
				if ($child->getName() == 'gr') {
					$currentColumn = 0;
					foreach ($child->children() as $gh) {
						if ($gh->getName() == 'gh') {
							if ($currentColumn != 0) {
								$headJSON .= ',';
							}
							$headJSON .= "{header:'".$gh."',dataIndex:'col".$currentColumn."'";
							foreach($gh->attributes() as $key => $value) {
								switch($key) {
									case 'width':
										$headJSON .= ",width:".$value;
										break;
									case 'id':
										$headJSON .= ",id:".$value;
										break;
									case 'sortable':
										$headJSON .= ",sortable:".$value;
										break;
									case 'renderer':
										$headJSON .= ",renderer:'".$value."'";
										break;
									case 'type':
										$columnTypes[$currentColumn] = $value;
										break;
									case 'dateformat':
										$columnTypeDateFormatters[$currentColumn] = $value;
										break;
									case 'autoexpand':
										$autoExpandIndex = $currentColumn;
										break;
								}
							}

							$headJSON .= '}';
							$currentColumn++;
						}
					}
				}
			}
			$headJSON .= ']';
		}
		$columnCount = $currentColumn;

		// format the body data
		$bodyJSON = '';
		if (isset($xml->gbody)) {
			$bodyJSON .= '[';
			foreach ($xml->gbody->children() as $child) {
				if ($bodyJSON != '[') {
					$bodyJSON .= ',';
				}
				if ($child->getName() == 'gr') {
					$currentColumn = 0;
					$bodyJSON .= '[';
					foreach($child->children() as $gd) {
						if ($gd->getName() == 'gd') {
							if ($currentColumn > 0) {
								$bodyJSON .= ',';
							}
							$bodyJSON .= "'".str_replace("'", "\\'", strval($gd))."'";
							$currentColumn++;
						}
					}
					$bodyJSON .= ']';
				}
			}
			$bodyJSON .= ']';
		}
		
		// start the output
		$retval .= "
			Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

			var grid".self::clenseID($args['id'])."Data = ".$bodyJSON.";
			
			var grid".self::clenseID($args['id'])."Store = new Ext.data.SimpleStore({
				fields: [";
		for($i = 0; $i < $columnCount; $i++) {
			if ($i > 0) {
				$retval .= ',';
			}
			$retval .= "{name: 'col".$i."'".(isset($columnTypes[$i]) ? ",type:'".str_replace("'", "\\'", $columnTypes[$i])."'" : '')."".(isset($columnTypeDateFormats[$i]) ? ",dateFormat:'".str_replace("'", "\\'", $columnTypeDateFormats[$i])."'" : '')."}";
		}
		$retval .= "]
			});
			grid".self::clenseID($args['id'])."Store.loadData(grid".self::clenseID($args['id'])."Data);

			var grid".self::clenseID($args['id'])." = new Ext.grid.GridPanel({
				store: grid".self::clenseID($args['id'])."Store,
				".($headJSON != '' ? "columns: ".$headJSON."," : '')."
				stripeRows: ".($args['striperows'] ? 'true' : 'false').",
				autoExpandColumn: 'col".$autoExpandIndex."'
				".($args['height'] ? ",height:".$args['height'] : '')."
				".($args['width'] ? ",width:".$args['width'] : '')."
				".($args['label'] ? ",title:'".str_replace("'", "\\'", $args['label'])."'" : '')."
			});

		grid".self::clenseID($args['id']).".render('".$args['id']."');

		grid".self::clenseID($args['id']).".getSelectionModel().selectFirstRow();

		";

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
		if (!isset($args['id'])) $args['id'] = 'text-'.uniqid();
		if (!isset($args['name'])) $args['name'] = $args['id'];
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
		if (!isset($args['id'])) $args['id'] = 'password-'.uniqid();
		if (!isset($args['name'])) $args['name'] = $args['id'];
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
		if (!isset($args['id'])) $args['id'] = 'select-'.uniqid();
		if (!isset($args['name'])) $args['name'] = $args['id'];
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
		if (!isset($args['id'])) $args['id'] = 'option-'.uniqid();
		$attributes = self::getAdditionalAttributes($args, array('class'));
		return '<option class="form-option'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.'>'.$innerContent.'</option>';
	}

	public static function fileTag ($args = array()) {
		if (!isset($args['id'])) $args['id'] = 'file-'.uniqid();
		if (!isset($args['name'])) $args['name'] = $args['id'];
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
		if (!isset($args['id'])) $args['id'] = 'checkbox-'.uniqid();
		if (!isset($args['name'])) $args['name'] = $args['id'];
		$attributes = self::getAdditionalAttributes($args, array('type', 'label', 'class'));
		return '<input type="checkbox" class="form-checkbox'.(isset($args['class']) ? ' '.$args['class'] : '').'"'.$attributes.' /><label class="form-checkbox-label" for="'.$args['id'].'">'.$args['label'].'</label>';

	}

	// this needs to be an object because the inner checkbox elements depend on the outer checkboxes element for their name/id
	public static function checkboxesObject($args = array(), $innerXML = '') {

	}

	public static function radiosObject($args = array(), $innerXML = '') {

	}

	public static function textareaContainer($args = array(), $innerContent = '') {
		if (!isset($args['id'])) $args['id'] = 'textarea-'.uniqid();
		if (!isset($args['name'])) $args['name'] = $args['id'];
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
		if (!isset($args['id'])) $args['id'] = 'submit-'.uniqid();
		if (!isset($args['name'])) $args['name'] = $args['id'];
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
		if (!isset($args['id'])) $args['id'] = 'button-'.uniqid();
		if (!isset($args['name'])) $args['name'] = $args['id'];
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
