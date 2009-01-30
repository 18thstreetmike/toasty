/*
 * This function is used to load any other needed files.
 */
function include_dom(script_filename) {
    var html_doc = document.getElementsByTagName('head').item(0);
    var js = document.createElement('script');
    js.setAttribute('language', 'javascript');
    js.setAttribute('type', 'text/javascript');
    js.setAttribute('src', script_filename);
    html_doc.appendChild(js);
    return false;
}

/*
 * Include all needed JS files.
 */
include_dom('scripts/ext-2.2/adapter/ext/ext-base.js');
include_dom('scripts/ext-2.2/ext-all-debug.js');
include_dom('scripts/php/php.js');
include_dom('scripts/ext-2.2/Portal.js');
include_dom('scripts/ext-2.2/PortalColumn.js');
include_dom('scripts/ext-2.2/Portlet.js');
