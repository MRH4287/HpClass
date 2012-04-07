<?php

abstract class AjaxFunctions
{
	protected static $functions = array();
	
	public static function extend($ext)
	{
		if (is_object($ext))
		{
			$funktions = get_class_methods($ext);

			foreach ($funktions as $key=>$value)
			{
				$split = explode("_", $value, 2);
				if ((count($split) > 1) && ($split[0] == "ajax") && ($split[1] != "") && !in_array($split[1], self::$functions))
				{
					$name = $split[1];

					$array = array(
						"name" => $name,
						"func" => $value,
						"obj" => $ext
						);

					self::$functions[$name] = $array;

				}
			}
		}
	}
	
	public static function call($name, $args)
	{
		if (isset(self::$functions[$name]))
		{
			$data = self::$functions[$name];
			
			
			return $data['obj']->$data['func']($args);
		} else
		{
			return null;
		}
	}
}

?>
