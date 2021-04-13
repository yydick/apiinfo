<style>
    /* [jquery.json-viewer.css] START */
    /* Root element */
    .json-document {
        padding: 1em 2em;
    }

    /* Syntax highlighting for JSON objects */
    ul.json-dict, ol.json-array {
        list-style-type: none;
        margin: 0 0 0 1px;
        border-left: 1px dotted #ccc;
        padding-left: 2em;
    }
    .json-string {
        color: #0B7500;
    }
    .json-literal {
        color: #1A01CC;
        font-weight: bold;
    }

    /* Toggle button */
    a.json-toggle {
        position: relative;
        color: inherit;
        text-decoration: none;
    }
    a.json-toggle:focus {
        outline: none;
    }
    a.json-toggle:before {
        font-size: 1.1em;
        color: #c0c0c0;
        content: "\25BC"; /* down arrow */
        position: absolute;
        display: inline-block;
        width: 1em;
        text-align: center;
        line-height: 1em;
        left: -1.2em;
    }
    a.json-toggle:hover:before {
        color: #aaa;
    }
    a.json-toggle.collapsed:before {
        /* Use rotated down arrow, prevents right arrow appearing smaller than down arrow in some browsers */
        transform: rotate(-90deg);
    }

    /* Collapsable placeholder links */
    a.json-placeholder {
        color: #aaa;
        padding: 0 1em;
        text-decoration: none;
    }
    a.json-placeholder:hover {
        text-decoration: underline;
    }
    /* [jquery.json-viewer.css] END */

    body {
        margin: 0 100px;
        font-family: sans-serif;
    }
    p.options label {
        margin-right: 10px;
    }
    p.options input[type=checkbox] {
        vertical-align: middle;
    }
    textarea#json-input {
        width: 100%;
        height: 200px;
    }
    pre#json-renderer {
        border: 1px solid #aaa;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
<div>
    Welcome to use ApiInfo!
    <textarea id="json-input" autocomplete="off" style="display:none;">
    {{$docExample[0]['location']}}
    </textarea>
    <p class="options">
        Options:
        <label title="Generate node as collapsed">
            <input type="checkbox" id="collapsed">Collapse nodes
        </label>
        <label title="Allow root element to be collasped">
            <input type="checkbox" id="root-collapsable" checked>Root collapsable
        </label>
        <label title="Surround keys with quotes">
            <input type="checkbox" id="with-quotes" checked>Keys with quotes
        </label>
        <label title="Generate anchor tags for URL values">
            <input type="checkbox" id="with-links" checked>
            With Links
        </label>
    </p>
    <button id="btn-json-viewer" title="run jsonViewer()">Transform to HTML</button>
    <pre id="json-renderer"></pre>

</div>

<script type="text/javascript">

     $(function() {
        function renderJson() {
            try {
                var input = eval('(' + $('#json-input').val() + ')');
            }
            catch (error) {
                return alert("Cannot eval JSON: " + error);
            }
            var options = {
                collapsed: $('#collapsed').is(':checked'),
                rootCollapsable: $('#root-collapsable').is(':checked'),
                withQuotes: $('#with-quotes').is(':checked'),
                withLinks: $('#with-links').is(':checked')
            };
            $('#json-renderer').jsonViewer(input, options);
        }
        // Generate on click
        $('#btn-json-viewer').click(renderJson);
        // Generate on option change
        $('p.options input[type=checkbox]').click(renderJson);
        // Display JSON sample on page load
        renderJson();
    });
 
    //[jquery.json-viewer.js] START
    /**
     * jQuery json-viewer
     * @author: Alexandre Bodelot <alexandre.bodelot@gmail.com>
     * @link: https://github.com/abodelot/jquery.json-viewer
     */
    (function($) {
 
        /**
         * Check if arg is either an array with at least 1 element, or a dict with at least 1 key
         * @return boolean
         */
        function isCollapsable(arg) {
            return arg instanceof Object && Object.keys(arg).length > 0;
        }
 
        /**
         * Check if a string represents a valid url
         * @return boolean
         */
        function isUrl(string) {
            var urlRegexp = /^(https?:\/\/|ftps?:\/\/)?([a-z0-9%-]+\.){1,}([a-z0-9-]+)?(:(\d{1,5}))?(\/([a-z0-9\-._~:/?#[\]@!$&'()*+,;=%]+)?)?$/i;
            return urlRegexp.test(string);
        }
 
        /**
         * Transform a json object into html representation
         * @return string
         */
        function json2html(json, options) {
            var html = '';
            if (typeof json === 'string') {
                // Escape tags and quotes
                json = json
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/'/g, '&apos;')
                    .replace(/"/g, '&quot;');
 
                if (options.withLinks && isUrl(json)) {
                    html += '<a href="' + json + '" class="json-string" target="_blank">' + json + '</a>';
                } else {
                    // Escape double quotes in the rendered non-URL string.
                    json = json.replace(/&quot;/g, '\\&quot;');
                    html += '<span class="json-string">"' + json + '"</span>';
                }
            } else if (typeof json === 'number') {
                html += '<span class="json-literal">' + json + '</span>';
            } else if (typeof json === 'boolean') {
                html += '<span class="json-literal">' + json + '</span>';
            } else if (json === null) {
                html += '<span class="json-literal">null</span>';
            } else if (json instanceof Array) {
                if (json.length > 0) {
                    html += '[<ol class="json-array">';
                    for (var i = 0; i < json.length; ++i) {
                        html += '<li>';
                        // Add toggle button if item is collapsable
                        if (isCollapsable(json[i])) {
                            html += '<a href class="json-toggle"></a>';
                        }
                        html += json2html(json[i], options);
                        // Add comma if item is not last
                        if (i < json.length - 1) {
                            html += ',';
                        }
                        html += '</li>';
                    }
                    html += '</ol>]';
                } else {
                    html += '[]';
                }
            } else if (typeof json === 'object') {
                var keyCount = Object.keys(json).length;
                if (keyCount > 0) {
                    html += '{<ul class="json-dict">';
                    for (var key in json) {
                        if (Object.prototype.hasOwnProperty.call(json, key)) {
                            html += '<li>';
                            var keyRepr = options.withQuotes ?
                                '<span class="json-string">"' + key + '"</span>' : key;
                            // Add toggle button if item is collapsable
                            if (isCollapsable(json[key])) {
                                html += '<a href class="json-toggle">' + keyRepr + '</a>';
                            } else {
                                html += keyRepr;
                            }
                            html += ': ' + json2html(json[key], options);
                            // Add comma if item is not last
                            if (--keyCount > 0) {
                                html += ',';
                            }
                            html += '</li>';
                        }
                    }
                    html += '</ul>}';
                } else {
                    html += '{}';
                }
            }
            return html;
        }
 
        /**
         * jQuery plugin method
         * @param json: a javascript object
         * @param options: an optional options hash
         */
        $.fn.jsonViewer = function(json, options) {
            // Merge user options with default options
            options = Object.assign({}, {
                collapsed: false,
                rootCollapsable: true,
                withQuotes: false,
                withLinks: true
            }, options);
 
            // jQuery chaining
            return this.each(function() {
 
                // Transform to HTML
                var html = json2html(json, options);
                if (options.rootCollapsable && isCollapsable(json)) {
                    html = '<a href class="json-toggle"></a>' + html;
                }
 
                // Insert HTML in target DOM element
                $(this).html(html);
                $(this).addClass('json-document');
 
                // Bind click on toggle buttons
                $(this).off('click');
                $(this).on('click', 'a.json-toggle', function() {
                    var target = $(this).toggleClass('collapsed').siblings('ul.json-dict, ol.json-array');
                    target.toggle();
                    if (target.is(':visible')) {
                        target.siblings('.json-placeholder').remove();
                    } else {
                        var count = target.children('li').length;
                        var placeholder = count + (count > 1 ? ' items' : ' item');
                        target.after('<a href class="json-placeholder">' + placeholder + '</a>');
                    }
                    return false;
                });
 
                // Simulate click on toggle button when placeholder is clicked
                $(this).on('click', 'a.json-placeholder', function() {
                    $(this).siblings('a.json-toggle').click();
                    return false;
                });
 
                if (options.collapsed == true) {
                    // Trigger click to collapse all nodes
                    $(this).find('a.json-toggle').click();
                }
            });
        };
    })(jQuery);
    //[jquery.json-viewer.js] END
</script>