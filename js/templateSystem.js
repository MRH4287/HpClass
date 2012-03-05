// Javascript based template-System by MRH
function templateSystem(__config, __l10n)
{
    // Sets $ to jQuery
    var $ = jQuery;
    // ........................

    var _config = {
        // The path to the AJAX-Backend
        'ajax-path': 'ajax/template.php',
        // Should the script use the sessionStorage Object
        'use_storage': true,
        // The key of the Cache Array in sessionStorage
        'template_cache_key': 'sessionStorage',
        // The key of the Node Cache Array in sessionStorage
        'template_node_cache_key': 'js_template_node_cache'

    };


    // .........................
    // private variables:

    var _ = function(a) { return a; };
    
    // Caches all template files
    var _templateCache = {};
    var _nodeCache = {};

    // The loaded file amd node
    var _file = undefined;
    var _node = undefined;

    // The Data needed for replacement
    var _data = {};
    var _vars = {};
    var _blocks = {};

    // ..........................

    // Constructor
    var _init = function(config)
    {
        _config = $.extend(_config, config);

        if (_config['use_storage'])
        {
            // try to load the template cache data from sessionStorage:
            try
            {
                // Checks if sessionStorage entry is valid:
                // If you know a better solution, please tell me :)

                if ((typeof sessionStorage[_config['template_cache_key']] != "undefined") &&
                    (typeof sessionStorage[_config['template_cache_key']] != "null") &&
                    sessionStorage[_config['template_cache_key']] != "undefined" &&
                    sessionStorage[_config['template_cache_key']] !== null &&
                    sessionStorage[_config['template_cache_key']] != "")
                {
                    _templateCache = JSON.parse(sessionStorage[_config['template_cache_key']]);
                } else
                {
                    sessionStorage[_config['template_cache_key']] = '{}';
                    _templateCache = {};
                }

                if ((typeof sessionStorage[_config['template_node_cache_key']] != "undefined") &&
                    (typeof sessionStorage[_config['template_node_cache_key']] != "null") &&
                    sessionStorage[_config['template_node_cache_key']] != "undefined" &&
                    sessionStorage[_config['template_node_cache_key']] !== null &&
                    sessionStorage[_config['template_node_cache_key']] != "")
                {
                    _nodeCache = JSON.parse(sessionStorage[_config['template_node_cache_key']]);
                } else
                {
                    sessionStorage[_config['template_node_cache_key']] = '{}';
                    _nodeCache = {};
                }

            } catch (ex)
            {
                console.warn(ex);
            }
        } else
        {
            _templateCache = {};
            _nodeCache = {};
        }

        if (typeof __l10n != "undefined")
        {
            _ = __l10n._;
        }
        
    }


    // -------------------------
    // public functions:

    // Requests and saves a template into the cache
    this.load = function(file, node)
    {
        var result = _load(file, node, false);

        _file = file;
        _node = node;

        _data = result.data;
        _vars = result.vars;

        if (_data.length == 0)
        {
            _data = {};
        }

        if (_vars.length == 0)
        {
            _vars = {};
        }

        _blocks = result.blocks;

    }

    // Sets a Key-Value Pair or a set of Key-Value Pairs
    this.set = function(data, value)
    {
        if (typeof value == "undefined")
        {
            $.each(data, function(key, value)
            {
                _set(key, value);
            });

        } else
        {
            _set(data, value);
        }

    }

    // Get the content of a specific Node with the data of the second argument
    this.getNode = function(node, content)
    {
        tempData = _data;
        _data = content;

        var result = _replace(_load(_file, node).content);

        _data = tempData;

        return result;
    }

    // Get the Data of this template
    this.get = function(node)
    {
        return _replace(_load(_file, node).content);
    }

    // Clears the Cache
    this.clearCache = function()
    {
        _templateCache = {};
        _nodeCache = {};

        if (_config['use_storage'])
        {
            // try to load the template cache data from sessionStorage:
            try
            {
                sessionStorage[_config['template_cache_key']] = '';
                sessionStorage[_config['template_node_cache_key']] = '';
            } catch (ex)
            {
                console.warn(ex);
            }
        }

    }

    // -------------------------

    // Requests a Template from the Backend
    var _request = function(file, node, all)
    {
        all = (typeof all == "undefined") ? true : all;
        var res = null;
        $.ajax({
            url: _config['ajax-path'],
            dataType: 'json',
            data: {
                'file': file,
                'node': node,
                'all': all
            },
            success: function(result)
            {
               res = result;
            },
            async: false
        });

        return res;
    }


    // Loads and caches a template object
    var _load = function(file, node, force)
    {
        if (typeof file == "undefined")
        {
            if (typeof _file != 'undefinded')
            {
                file = _file;
            } else
            {
                throw 'Template-System: No file given!';
            }
        }

        force = (typeof force == "undefined") ? false : force;
        node = (typeof node == "undefined") ? 'Main' : node;

        var result = null;

        if (force || (typeof _templateCache[file] == "undefined"))
        {
            var data = _request(file, node, true);
            if (data == null)
            {
                throw 'Template-System: No Template Data!';
            }
            _templateCache[file] = data;
            if (typeof _nodeCache[file] == "undefined")
            {
                _nodeCache[file] = {};
            }

            _nodeCache[file][node] = data['content'];

            result = data;

            if (_config['use_storage'])
            {
                try
                {
                    sessionStorage[_config['template_cache_key']] = JSON.stringify(_templateCache);
                    sessionStorage[_config['template_node_cache_key']] = JSON.stringify(_nodeCache);

                } catch (ex)
                {
                    console.warn(ex);
                }
            }

        } else
        {
            var data = _templateCache[file];
            if ((typeof _nodeCache[file] == "undefined") || (typeof _nodeCache[file][node] == "undefined"))
            {
                var req = _request(file, node, false);
                data['content'] = req['content'];

                if (typeof _nodeCache[file] == "undefined")
                {
                    _nodeCache[file] = {};
                }
                _nodeCache[file][node] = data['content'];

                if (_config['use_storage'])
                {
                    try
                    {
                        sessionStorage[_config['template_node_cache_key']] = JSON.stringify(_nodeCache);

                    } catch (ex)
                    {
                        console.warn(ex);
                    }
                }

            } else
            {
                data['content'] =   _nodeCache[file][node];

            }

            result = data;
        }

        return result;

    }


    // Sets a Key-Value Pair
    var _set = function(key, value)
    {
        try
        {
            _data[key] = value;

        } catch (ex)
        {
            console.warn(ex);
        }
    }


    // ---------------------------------------------------------------------------
    // Template replace functions:

    // Triggers all reqplacement functions
    var _replace = function(input)
    {
        if (typeof input == "undefined")
        {
            input = _load().content;
        }

        input = _replaceDefault(input);
        input = _replaceCondit(input);

        return input;
    }


    // Replaces all default variables
    var _replaceDefault = function(input)
    {
        try
        {
            var replace = $.extend(true, {}, _data, _vars);

            $.each(replace, function(key, value)
            {
                input = input.replace('#J!' + key + '#', value);
            });

        } catch (ex)
        {
            console.warn(ex);
        }

        return input;
    }

    // Replaces all conditional Elements
    var _replaceCondit = function(input)
    {
        var reg = /#J\?:([^#]+)#/g;
        var res = null;
        var output = input;

        while ((res = reg.exec(input)) != null)
        {
            var options = res[1].split(' : ');
            var result = '';

            var iftrue = '';
            var iffalse = '';
            // Not enough parameter given:
            if (options.length <= 1)
            {
                output = output.replace(res[0], '[Arguments?]');
                console.warn('templateSystem: '+res[0]+' Not enough Arguments!');
                continue;

            } else if (options.length == 2)
            {
                // #?:condit : ifTrue#
                iftrue = options[1];

            } else if (options.length == 3)
            {
                // #?:condit : ifTrue : ifFalse#
                iftrue = options[1];
                iffalse = options[2];
            }

            var condit = options[0];

            // Check if the Condition is true:
            if (((typeof _data[condit] != "undefined") && ((_data[condit] == true) || (_data[condit] == "true"))) ||
                ((typeof _vars[condit] != "undefined") && ((_vars[condit] == true) || (_vars[condit] == "true"))))
            {
                result = _replaceExtendedInput(iftrue);
            } else
            {
                result = _replaceExtendedInput(iffalse);
            }

            output = output.replace(res[0], result);
        }

        return output;
    }

    // Replaces the extendedInput
    var _replaceExtendedInput = function(input)
    {
        var reg_function = /@([^\"]*)\((.*)\)/;
        var reg_string = /\"(.*)\"/;
        var reg_lang = /%(.*)/;
        var reg_imediateNode = /!!(.*)/;
        var reg_node = /!(.*)/;
        var reg_loop = /l\:(.*)/;

        var regResult = null;
        var result = "";

        if ((regResult = reg_function.exec(input)) != null)
        {
            result = "[Not Implemented yet!]";

        } else if ((regResult = reg_string.exec(input)) != null)
        {
            result = regResult[1];

        } else if ((regResult = reg_lang.exec(input)) != null)
        {
            result = _(regResult[1]);

        } else if ((regResult = reg_imediateNode.exec(input)) != null)
        {
            if (typeof _blocks[regResult[1]] != "undefined")
            {
                result = _blocks[regResult[1]];
            } else
            {
                result = "[Node?]";
            }
        } else if ((regResult = reg_node.exec(input)) != null)
        {
            if (typeof _blocks[regResult[1]] != "undefined")
            {
                result = _replace(_blocks[regResult[1]]);
            } else
            {
                result = "[Node?]";
            }

        } else if ((regResult = reg_loop.exec(input)) != null)
        {
            result = "[Not Implemented yet!]";

        } else if (typeof _data[input] != "undefined")
        {
            result = _data[input];

        } else if (typeof _vars[input] != "undefined")
        {
            result = _vars[input];

        } else
        {
            result = "[Var?]";
        }

        return result;
    }


    // Loads the Constructor
    _init(__config);
}