<?php
/**
 * The Toasty Template Engine
 * 
 * Copyright 2008, 18th Street Software, LLC
 * 
 * This class defines a templating engine that can be used in PHP.  For a full listing of features, please visit 
 * http://toasty.codaserver.com.
 * 
 * @package toasty
 * @author Mike Arace <mike@18thstreetsoftware.com>
 * @copyright Copyright (c) 2008, 18th Street Software, LLC
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU Public License, Version 2
 */

class Toasty {
	
	// various configuration parameters.
	private $widgetClass = '';
	private $rootDirectory = '';
	private $extension = 'phtml';
	private $cacheDirectory = null;
	
	// the rewrite properties
	private $workingDirectory = '';
	private $aggregateBlocks = false;
	private $createFiles = false;
	private $scriptTag = 'scriptgroup';
	private $styleTag = 'stylegroup';
	
	// the blocks likely to break the XML structure.
	private $scriptBlocks = array();
	private $styleBlocks = array();
	
	// the widgets available 
	private $tags = array();
	private $objects = array();
	private $containers = array();
	
	// the template variables
	private $variables = array();
	
	/**
	 * Construct a new Toasty object.
	 *
	 * @param string $widgetClass
	 * @param string $extension
	 * @param string $cacheDirectory
	 */
	public function __construct($config = array()) {
		foreach ($config as $key => $value) {
			switch($key) {
				case 'widget_class':
					$this->setWidgetClass($value);
					break;
				case 'extension':
					$this->setExtension($value);
					break;
				case 'cache_directory':
					$this->setCacheDirectory($value);
					break;
				case 'root_directory':
					$this->setRootDirectory($value);
					break;
				case 'working_directory':
					$this->setWorkingDirectory($value);
					break;
				case 'aggregate_blocks':
					$this->setAggregateBlocks($value);
					break;
				case 'create_files':
					$this->setCreateFiles($value);
					break;
				case 'script_tag':
					$this->setScriptTag($value);
					break;
				case 'style_tag':
					$this->setStyleTag($value);
					break;
			}
		}
	}
	
	/**
	 * Sets the class name string for the widgetset to use.
	 *
	 * @param string $widgetClass
	 */
	public function setWidgetClass($widgetClass) {
		$this->widgetClass = $widgetClass;
		
		// get a list of all of the functions of the widget class
		$classMethods = get_class_methods($this->widgetClass);

		// iterate over the widget's functions to see what is provided
		foreach ($classMethods as $methodName) {
			if (substr($methodName, -3) == 'Tag') {
				$this->tags[] = strtolower(substr($methodName, 0, -3));
			} else if (substr($methodName, -6) == 'Object') {
				$this->objects[] = strtolower(substr($methodName, 0, -6));
			} else if (substr($methodName, -9) == 'Container') {
				$this->containers[] = strtolower(substr($methodName, 0, -9));
			}
		}
	}
	
	/**
	 * Gets the class name string for the current widgetset.
	 *
	 * @return string
	 */
	public function getWidgetClass() {
		return $this->widgetClass;
	}
	
	/**
	 * Sets the extension for template files.  The default is "phtml".
	 *
	 * @param string $extension
	 */
	public function setExtension($extension) {
		$this->extension = $extension;
	}
	
	/**
	 * Gets the template file extension.
	 *
	 * @return string
	 */
	public function getExtension() {
		return $this->extension;
	}
	
	/**
	 * Sets the cache directory.  A null value disables caching.
	 *
	 * @param string $cacheDirectory
	 */
	public function setCacheDirectory($cacheDirectory) {
		$this->cacheDirectory = $cacheDirectory;
	}
	
	/**
	 * Gets the cache directory
	 *
	 * @return string
	 */
	public function getCacheDirectory() {
		return $this->cacheDirectory;
	}
	
	/**
	 * Sets the root directory for templates.
	 *
	 * @param string $rootDirectory
	 */
	public function setRootDirectory($rootDirectory) {
		$this->rootDirectory = $rootDirectory;
	}
	
	/**
	 * Gets the root directory.
	 *
	 * @return string
	 */
	public function getRootDirectory() {
		return $this->rootDirectory;
	}
	
	/**
	 * Sets the directory Toasty uses to publish .js and .css files when createFile is set to true. Relative to the web root. 
	 *
	 * @param string $workingDirectory
	 */
 	public function setWorkingDirectory($workingDirectory) {
		$this->workingDirectory = $workingDirectory;
	}
	
	/**
	 * Gets the working directory
	 *
	 * @return string
	 */
	public function getWorkingDirectory() {
		return $this->workingDirectory;
	}
	
	/**
	 * Toggles the grouping of styles and scripts.
	 *
	 * @param boolean $aggregateBlocks
	 */
	public function setAggregateBlocks($aggregateBlocks) {
		$this->aggregateBlocks = ($aggregateBlocks == true);
	}
	
	/**
	 * Gets indicator of aggregate status
	 *
	 * @return boolean
	 */
	public function getAggregateBlocks() {
		return $this->aggregateBlocks;
	}
	
	/**
	 * Sets whether Toasty should create files for scripts and styles.
	 *
	 * @param boolean $createFiles
	 */
	public function setCreateFiles($createFiles) {
		$this->createFiles = ($createFiles == true);
	}
	
	/**
	 * Indicates whether Toasty should create files for scripts and styles.
	 *
	 * @return boolean
	 */
	public function getCreateFiles() {
		return $this->createFiles;
	}
	
	/**
	 * Sets the tag that indicates where aggregated scripts should be placed.
	 *
	 * @param string $scriptTag
	 */
	public function setScriptTag($scriptTag) {
		$this->scriptTag = $scriptTag;
	}
	
	/**
	 * Get the tag that indicates where aggregated scripts should be placed.
	 *
	 * @return string
	 */
	public function getScriptTag() {
		return $this->scriptTag;
	}
	
	/**
	 * Sets the tag that indicates where aggregated styles should be placed.
	 *
	 * @param string $styleTag
	 */
	public function setStyleTag($styleTag) {
		$this->styleTag = $styleTag;
	}
	
	/**
	 * Gets the tag that indicates where aggregated styles should be placed.
	 *
	 * @return string
	 */
	public function getStyleTag() {
		return $this->styleTag;
	}
	
	public function getScriptBlocks() {
		return $this->scriptBlocks;
	}
	
	public function getStyleBlocks() {
		return $this->styleBlocks;
	}
	
	/**
	 * Used to set template variables.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$this->variables[$name] = $value;
	}
	
	/**
	 * Used to get template variables.
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		return $this->variables[$name];
	}
	
	/**
	 * This function does the work of the template engine.
	 * 
	 * It takes the specified template file and attempts to render it.  If caching is enabled, it attempts to find
	 * the requested file (with an optional cache key) and returns that.  Otherwise, it executes the template, iterates over the
	 * resulting DOM to find custom tags, renders them according to the provided widgetset, optionally prettifies the resulting 
	 * document, and outputs them or returns them to the caller.  
	 *
	 * @param string $templateName
	 * @param string $cacheKey
	 * @param boolean $echoDirectly
	 * @param boolean $prettifyHtml
	 * @return string or null
	 */
	public function render($templateName = null, $cacheKey = null, $convertToHtml = true, $echoDirectly = false, $runCleanup = true, $prettifyHtml = false) {
		if (is_null($templateName)) {
			throw new Exception('No template provided.');
		} else {
			
			$file = file_get_contents($this->rootDirectory.$templateName.'.'.$this->extension);
			if (!$file) {
				throw new Exception('Template not found.');
			} else if(!is_null($this->cacheDirectory) && file_exists($this->cacheDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.php')) {
				if ($echoDirectly) {
						$this->cleanup();
						echo file_get_contents($this->cacheDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.html');
						return;
					} else {
						$this->cleanup();
						return file_get_contents($this->cacheDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.html');
					}
			} else {
				// eval the statement, grab the output buffer.
				ob_start();
			    eval('?>'.$file);
			    $file = ob_get_contents();
			    ob_end_clean(); 
			        
				//  strip out all of the script tags, store the content elsewhere for now.
				$file = preg_replace_callback(
					'/(<script[ \t>].*?\/script>)/sm',
					array(&$this, storeScriptBlock),
					$file
				);
				
				//  strip out all of the style tags, store the content elsewhere for now.
				$file = preg_replace_callback(
					'/(<style[ \t>].*?\/style>)/sm',
					array(&$this, storeStyleBlock),
					$file
				);

				if ($convertToHtml) {
				
					// build the XML document with simple XML
					/* $object = simplexml_load_string("<?xml version='1.0'?><document>".$file."</document>");*/
					$object = simplexml_load_string($file);
	
					// check to see if the resulting file is actually XML
					if (!$object) {
						throw new Exception('File not valid XML.');
					} else {
						$output = $this->processNode($object);
					}
				} else {
					$output = $file;
				}

				if (!$this->aggregateBlocks) {
					// replace the script blocks
					foreach ($this->scriptBlocks as $key => $value) {
						$output = str_replace('<script block="'.$this->hashNumber($key).'"/>', $value, $output);
						$output = str_replace('<script block="'.$this->hashNumber($key).'"></script>', $value, $output);	
					}
	
					// replace the style blocks
					foreach ($this->styleBlocks as $key => $value) {
						$output = str_replace('<style block="'.$this->hashNumber($key).'"/>', $value, $output);
						$output = str_replace('<style block="'.$this->hashNumber($key).'"></style>', $value, $output);	
					}
				} else {
					// iterate over each of the scripts and styles, building strings
					$styleString = '';
					$scriptString = '';
					
					foreach($this->scriptBlocks as $key => $code) {
						// determine if the script block is a src=" block.  do the regular thing if it is. Let's regex this soonish.
						if (strstr($code, 'src="') == false) {
							$scriptString .= "// start block ".$key."\n\n". strip_tags($code) . "\n\n// end block ".$key." \n\n";
							$output = str_replace('<script block="'.$this->hashNumber($key).'"/>', '', $output);
							$output = str_replace('<script block="'.$this->hashNumber($key).'"></script>', '', $output);
						} else {
							$output = str_replace('<script block="'.$this->hashNumber($key).'"/>', $code, $output);
							$output = str_replace('<script block="'.$this->hashNumber($key).'"></script>', $code, $output);
						}
					}
					
					foreach($this->styleBlocks as $key => $style) {
						$styleString .= "/* start block ".$key." */\n\n". strip_tags($style) . "\n\n/* end block ".$key." */\n\n";
						$output = str_replace('<style block="'.$this->hashNumber($key).'"/>', '', $output);
						$output = str_replace('<style block="'.$this->hashNumber($key).'"></style>', '', $output);
					}
					
					if ($this->createFiles) {
						// save the scripts and styles to external files, write these to the script and style tag locations, if specified
						if (!empty($this->scriptTag)) {
							if (!file_exists($_SERVER['DOCUMENT_ROOT'].$this->workingDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.js')) {
								file_put_contents($_SERVER['DOCUMENT_ROOT'].$this->workingDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.js', $scriptString);
							}
							$output = str_replace('<'.$this->scriptTag.' />', '<script language="javascript" type="text/javascript" src="'.$this->workingDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.js'.'"></script>', $output);
							$output = str_replace('<'.$this->scriptTag.'></'.$this->scriptTag.'>', '<script language="javascript" type="text/javascript" src="'.$this->workingDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.js'.'"></script>', $output);
						}
						if (!empty($this->styleTag)) {
							if (!file_exists($_SERVER['DOCUMENT_ROOT'].$this->workingDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.css')) {
								file_put_contents($_SERVER['DOCUMENT_ROOT'].$this->workingDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.css', $styleString);
							}
							$output = str_replace('<'.$this->styleTag.' />', '<link href="'.$this->workingDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.css'.'" rel="stylesheet" type="text/css" media="screen"/>', $output);
							$output = str_replace('<'.$this->styleTag.'></'.$this->styleTag.'>', '<link href="'.$this->workingDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.css'.'" rel="stylesheet" type="text/css" media="screen"/>', $output);
						}
					} else {
						// create script and style tags wrapping the new aggregated tags
						if (!empty($this->scriptTag)) {
							$output = str_replace('<'.$this->scriptTag.'/>', '<script language="javascript">'.$scriptString.'</script>', $output);
							$output = str_replace('<'.$this->scriptTag.'></'.$this->scriptTag.'>', '<script language="javascript">'.$scriptString.'</script>', $output);
						}
						if (!empty($this->styleTag)) {
							$output = str_replace('<'.$this->styleTag.'/>', '<style>'.$styleString.'</style>', $output);
							$output = str_replace('<'.$this->styleTag.'></'.$this->styleTag.'>', '<style>'.$styleString.'</style>', $output);
						}
					}
				}
				
				// save to cache if needed
				if (!is_null($this->cacheDirectory)) {
					file_put_contents($this->cacheDirectory.$templateName.(is_null($cacheKey) ? '' : '_'.$cacheKey).'.html', $output);
				}
				
				if ($echoDirectly) {
					if ($runCleanup) {
						$this->cleanup();
					}
					echo ($prettifyHtml ? $this->cleanHtmlCode($output) : $output);
					return;
				} else {
					if ($runCleanup) {
						$this->cleanup();
					}
					return ($prettifyHtml ? $this->cleanHtmlCode($output) : $output);
				}
			}
		}
		
	}
	
	/**
	 * A callback function used to store a <script> tag into this objects style block array, and return a placeholder so 
	 * the XML parser can work around it.
	 *
	 * @param array $matches
	 * @return string
	 */
	private function storeScriptBlock($matches) {
		$this->scriptBlocks[] = $matches[0];
		return '<script block="'.$this->hashNumber(count($this->scriptBlocks) - 1).'"/>';
	}
	
	/**
	 * A callback function used to store a <style> tag into this objects style block array, and return a placeholder so 
	 * the XML parser can work around it. 
	 *
	 * @param array $matches
	 * @return string
	 */
	private function storeStyleBlock($matches) {
		$this->styleBlocks[] = $matches[0];
		return '<style block="'.$this->hashNumber(count($this->styleBlocks) - 1).'"/>';
	}
	
	/**
	 * Recursively works down the page's DOM tree looking for custom Toasty tags.
	 *
	 * @param SimpleXMLElement $object
	 * @return string
	 */
	private function processNode($object) {
		if (in_array($object->getName(), $this->tags)) {
			// check to see if tag
			$className = $this->getWidgetClass();
			$attributes = array();
			foreach ($object->attributes() as $key => $attribute) {
				$attributes[$key] = strval($attribute);
			}
			return call_user_func(array($this->getWidgetClass(), $object->getName().'Tag'), $attributes);
		} else if (in_array($object->getName(), $this->objects)) {
			// check to see if object
			$attributes = array();
                        foreach ($object->attributes() as $key => $attribute) {
                                $attributes[$key] = strval($attribute);
                        }
			return call_user_func(array($this->getWidgetClass(), $object->getName().'Object'), $attributes, $object->asXML()); 
		} else if (in_array($object->getName(), $this->containers)) {
			$innerHtml = '';
			$children = $object->xpath('child::*');
			if (count($children) == 0) {
				$innerHtml = strval($object);
			} else {
				$domDoc = dom_import_simplexml($object);
				$domChildren = $domDoc->childNodes;
				for ($i = 0; $i < $domChildren->length; $i++) {
					$node = $domChildren->item($i);
					if ($node->nodeType == XML_TEXT_NODE) {
						$innerHtml .= htmlentities(' '.$node->textContent.' '); 
					} else {
						$child = simplexml_import_dom($node);
						$innerHtml .= $this->processNode($child);
					}
				}
				/*
				foreach ($children as $child) {
					$innerHtml .= $this->processNode($child);
				}
				*/
			}
			$attributes = array();
                        foreach ($object->attributes() as $key => $attribute) {
                                $attributes[$key] = strval($attribute);
                        }
			return call_user_func(array($this->getWidgetClass(), $object->getName().'Container'), $attributes, $innerHtml);
		} else {
			
			$children = $object->xpath('child::*');
			if (count($children) == 0) {
				$output = $object->asXML();
				return ($output == '<attributes/>' || $output == '<attributes></attributes>' ? '' : $output); 
			} else {
				$domElement = dom_import_simplexml($object);
				
				$attributes = '';
				foreach ($domElement->attributes as $key => $value) {
					$attributes .= ' '.$value->name.'="'.$value->value.'"'; 
				}
				
				$html = '<'.$object->getName().$attributes.'>';
				$domDoc = dom_import_simplexml($object);
				$domChildren = $domDoc->childNodes;
				for ($i = 0; $i < $domChildren->length; $i++) {
					$node = $domChildren->item($i);
					if ($node->nodeType == XML_TEXT_NODE) {
						$html .= htmlentities(' '.$node->textContent.' '); 
					} else if ($node->nodeType != XML_ATTRIBUTE_NODE) {
						$child = simplexml_import_dom($node);
						$html .= $this->processNode($child);
					}
				}
				$html .= '</'.$object->getName().'>';
				return $html;
			}
		}
	}
	
	/**
	 * Used to generate repeatable unique keys for the various placeholders.
	 */
	private function hashNumber ($num) {
		return md5($num.'cavantina');
	}
	
	/**
	 * Returns all instance variables to empty states.
	 *
	 */
	public function cleanup() {
		$this->scriptBlocks = array();
		$this->styleBlocks = array();
		
		$this->variables = array();
	}
	
	/**
	 * Clears all of the template's variables.
	 *
	 */
	public function clearVariables() {
		$this->variables = array();
	}
	
	/**
	 * Prepares uncleaned HTML for cleaning.
	 * 
	 * This function was found on http://snippets.dzone.com/posts/show/1964 and is assumed to be public domain
	 *
	 * @param string $fixthistext
	 * @return string
	 */
	private function fixNewlinesForCleanHtml($fixthistext) {
	  	$fixthistext_array = explode("\n", $fixthistext);
	  	foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue) {
	  		//Makes sure empty lines are ignores
	  		if (!preg_match("/^(\s)*$/", $unfixedtextvalue)) {
	  			$fixedtextvalue = preg_replace("/>(\s|\t)*</U", ">\n<", $unfixedtextvalue);
	  			$fixedtext_array[$unfixedtextkey] = $fixedtextvalue;
	  		}
	  	}
	  	return implode("\n", $fixedtext_array);
	}

	/**
	 * Cleans unclean HTML.
	 * 
	 * This function was found on http://snippets.dzone.com/posts/show/1964 and is assumed to be public domain
	 *
	 * @param string $uncleanhtml
	 * @return string
	 */
	private function cleanHtmlCode($uncleanhtml) {
		//Set wanted indentation
		$indent = "    ";  
	   	//Uses previous function to seperate tags
	  	$fixed_uncleanhtml = $this->fixNewlinesForCleanHtml($uncleanhtml);
	  	$uncleanhtml_array = explode("\n", $fixed_uncleanhtml);
	  	//Sets no indentation
	  	$indentlevel = 0;
	  	foreach ($uncleanhtml_array as $uncleanhtml_key => $currentuncleanhtml)
	  	{
	  		//Removes all indentation
	  		$currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
	  		$currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);
	  		
	  		$replaceindent = "";
	  		
	  		//Sets the indentation from current indentlevel
	  		for ($o = 0; $o < $indentlevel; $o++)
	  		{
	  			$replaceindent .= $indent;
	  		}
	  		
	  		//If self-closing tag, simply apply indent
	  		if (preg_match("/<(.+)\/>/", $currentuncleanhtml))
	  		{ 
	  			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
	  		}
	  		//If doctype declaration, simply apply indent
	  		else if (preg_match("/<!(.*)>/", $currentuncleanhtml))
	  		{ 
	  			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
	  		}
	  		//If opening AND closing tag on same line, simply apply indent
	  		else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml))
	  		{ 
	  			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
	  		}
	  		//If closing HTML tag or closing JavaScript clams, decrease indentation and then apply the new level
	  		else if (preg_match("/<\/(.*)>/", $currentuncleanhtml) || preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $currentuncleanhtml))
	  		{
	  			$indentlevel--;
	  			$replaceindent = "";
	  			for ($o = 0; $o < $indentlevel; $o++)
	  			{
	  				$replaceindent .= $indent;
	  			}
	  			
	  			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
	  		}
	  		//If opening HTML tag AND not a stand-alone tag, or opening JavaScript clams, increase indentation and then apply new level
	  		else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml)) || preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $currentuncleanhtml))
	  		{
	  			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
	  			
	  			$indentlevel++;
	  			$replaceindent = "";
	  			for ($o = 0; $o < $indentlevel; $o++)
	  			{
	  				$replaceindent .= $indent;
	  			}
	  		}
	  		else
	  		//Else, only apply indentation
	  		{$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;}
	  	}
	  	//Return single string seperated by newline
	  	return implode("\n", $cleanhtml_array);	
	}
}
