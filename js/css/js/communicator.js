/*
	Generic Ajax-Requester for the Action-System
*/
function Communicator()
{
	var $ = jQuery;
	var _backendAdress = "ajax/action.php";
	
	this.request = function(command, params, callback)
	{
		if (typeof(callback) == "undefined")
		{
			callback = function(res) 
			{
				console.log("Communicator: Callback for "+command);
			}
		}
		
		var data = $.extend(
		{
			action: command
		}, params);
		
		$.post(_backendAdress, data, callback, 'json');

	}
	
}