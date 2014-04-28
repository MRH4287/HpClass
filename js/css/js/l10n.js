/************************************************************************************
 * Author    : MRH
 ************************************************************************************/

 // This Class manages the localization
 function Localization()
 {
    var $ = jQuery;

    var _config = {
        backend: './ajax/action.php',
        storage_key: 'Localization-Cache',
        use_storage: true
    }

    var _locale_cache = {};

    // ------- Init ---------
    var _init = function()
    {
        _loadConfig();
    }

    // ------ Config: --------

    // Loads the config values out of the local Storage
    var _loadConfig = function()
    {
        if (_config.use_storage)
        {
            try
            {
                if ((typeof sessionStorage[_config.storage_key] != "undefined") &&
                    (typeof sessionStorage[_config.storage_key] != "null") &&
                    sessionStorage[_config.storage_key] != "undefined" &&
                    sessionStorage[_config.storage_key] != "null" &&
                    sessionStorage[_config.storage_key] != "" &&
                    sessionStorage[_config.storage_key] != null)
                {
                    _locale_cache = JSON.parse(sessionStorage[_config.storage_key]);
                }

            } catch (ex)
            {
            }
        }

    }

    // Saves the Config into sessionStorage
	var _save_config = function()
	{
        if (_config.use_storage)
        {
            try
            {
                sessionStorage[_config.storage_key] = JSON.stringify(_locale_cache);

            } catch (e)
            {
            }
        }
	}

    // Requests Data from the Server
    var _request = function(data, callback, async)
    {
        async = (typeof async != "undefined") ? async : false;

        $.ajax({
            type: 'POST',
            url: _config.backend,
            data: data,
            async: async,
            success: function(response)
            {
                callback(jQuery.parseJSON(response));
            },
            statusCode:
            {
                400: function()
                {
                    alert('Bad Request');
                }
            }
        });
    }

    this._ = function(name, async)
    {
        async = (typeof async != "undefined") ? async : false;

        if (name in _locale_cache)
        {
            return _locale_cache[name];
        }

        var erg = null;

        _request({
			action: 'l10n',
            request: name

        }, function(result) {

            _locale_cache = $.extend(_locale_cache, result);
            _save_config();

            erg = result[name];
        },async);

        return erg;

    }

    // ------
    _init();
 }