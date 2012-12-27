function WidgetController()
{
	var $ = jQuery;
	
	// GUI:
	var _placedWidgetClass = "widgets_movable_element";
	var _dropContainerClass = "widgets_drop";
	var _notPlacedClass = "widgets_not_placed";
	
	//Backend:
	var _action = "widgetAction";
	
	// Container for not placed Elements:
	var _notPlacedContainer = undefined;
	
	
	var _enablePlacing = function()
	{
		$("."+_placedWidgetClass).each(function(k, el)
		{
			$(el).draggable({
				revert: true
			});
			
		});
		
		$("."+_notPlacedClass).each(function(k, el)
		{
			$(el).draggable({
				revert: true
			});
			
		});
		
		$("."+_dropContainerClass).each(function(k, el)
		{
			$(el).droppable(
			{
				hoverClass: "hclass",
				drop: function( event, ui ) 
				{
					_widgetDrop($(this), ui);
				}
			});
			
		});
		
	}
	
	this.enablePlacing = _enablePlacing;
	
	
	this.setNotPlacedContainer = function(container)
	{
		_notPlacedContainer = container;
	}
	
	this.widgetDeleteDropEvent = function(element, ui)
	{
		var moved = ui.draggable;
		
		var parentName = element.attr('id');
		var movedName = moved.attr('name');
		
		var com = new Communicator();
		
		com.request("widgetsAction", { event:"delete", moved:movedName, parent:parentName},
		function(result)
		{
			_reload(result);
		});
		
	}
	
	var _widgetDrop = function(element, ui)
	{		
		var moved = ui.draggable;
		
		var parentName = element.attr('name');
		var movedName = moved.attr('name');
		
		var com = new Communicator();

		var event = moved.is("." + _notPlacedClass) ? "create" : "move";
		
		com.request("widgetsAction", { event:event, moved:movedName, parent:parentName},
		function(result)
		{
			_reload(result);
			
		});
		
	}
		
	var _reload = function(result)
	{
		var template = result.template;
		var notPlaced = result.notPlaced;

		$(".widget").each(function(k, el)
		{
			el = $(el);
			//console.log("Check Widget ", el); 
			
			var name = el.attr("placeholder");
			//console.log("Placeholder: ", name);
			
			if (typeof(template[name]) != "undefined")
			{
				//console.log("Element is set... ");
				
				// Element is set:
				if (el.is("." + _placedWidgetClass))
				{
					//console.log("There is already a Widget on this Element");
					
					// Widget is already set
					if (el.attr("name") == template[name]["source"])
					{
						// The Element is correct
						//console.log("Element is the same, ignore");
					}
					else
					{
						// Another Element is set for this element
						el.html(template[name]["content"]);
						el.attr("name", template[name]["source"]);
					}
				}
				else
				{
					//console.log("This Element is not set => Create ");
					// The Element is a Placeholder			
					el.droppable( "destroy" );
					el.removeClass(_dropContainerClass);
					el.addClass(_placedWidgetClass);
					el.draggable({
						revert: true
					});
					
					el.html(template[name]["content"]);
					el.attr("name", template[name]["source"]);
				}
			
			}
			else
			{
				//console.log("No Element on this placeholder");
				// Element is not set:
				if (el.is("." + _placedWidgetClass))
				{
					//console.log("Destroy existing Widget");
					
					el.draggable( "destroy" );
					el.removeClass(_placedWidgetClass);
					el.addClass(_dropContainerClass);
				
					el.html("");
					el.droppable(
					{
						hoverClass: "hclass",
						drop: function( event, ui ) 
						{
							_widgetDrop($(this), ui);
						}
					});
					
					el.attr("name", name);
				}
			}
		});
		
		var placed = [];
		
		// Reload Not set Elements:
		$("." + _notPlacedClass).each(function(k, el)
		{
			el = $(el);
			
			var name = el.attr("name");
			
			if (typeof(notPlaced[name]) == "undefined")
			{
				// The Element is no longer "notPlaced"
				// Remove Element
				
				el.remove();
			}
			else
			{
				// The Element is still not Placed
				placed.push(name);
			}
			
		});
		
		if (typeof(_notPlacedContainer) != "undefined")
		{
			$.each(notPlaced, function(name, content)
			{
				if (placed.indexOf(name) == -1)
				{
					// Element is not set, create
					
					var element = $("<div></div>")
					.addClass(_notPlacedClass)
					.addClass("widget")
					.attr("placeholder", "-")
					.attr("name", name)
					.html(content).draggable({
						revert: true
					}).appendTo(_notPlacedContainer);
					
				}
			});
		}
	}
		
}
