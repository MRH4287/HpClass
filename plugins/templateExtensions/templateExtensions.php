<?php
	// This File is included by the SiteTemplate-System
	class templateExtensions
	{	
		// Base:
		
		public function temp_not($args)
		{
			if (count($args) < 1)
			{
				return false;
			}
			
			return !($this->temp_bool($args));
		}
		
		// Typecasting:
		
		public function temp_bool($args)
		{
			if (count($args) < 1)
			{
				return false;
			}
		
			return ($args[0] === true) || ($args[0] === 1) || (strtolower($args[0]) === "true");
		}
		
		public function temp_int($args)
		{
			if (count($args) < 1)
			{
				return false;
			}
		
			return ((int)intval($args[0]));
		}
		
		public function temp_float($args)
		{
			if (count($args) < 1)
			{
				return false;
			}
		
			return ((float)floatval($args[0]));
		}
	
	
		// Math:
		
		public function temp_add($args)
		{
			$callback = function($a, $b) { return $a + $b; };
			return $this->math_op($args, $callback); 
		}
		
		public function temp_sub($args)
		{
			$callback = function($a, $b) { return $a - $b; };
			return $this->math_op($args, $callback); 
		}
		
		public function temp_div($args)
		{
			$callback = function($a, $b) { return $a / $b; };
			return $this->math_op($args, $callback); 
		}
		
		public function temp_mul($args)
		{
			$callback = function($a, $b) { return $a * $b; };
			return $this->math_op($args, $callback); 
		}
		
		public function temp_sqrt($args)
		{
			$callback = function($a, $b) { return sqrt($a); };
			return $this->math_op($args, $callback); 
		}
		
		public function temp_pow($args)
		{
			$callback = function($a, $b) { return pow($a, $b); };
			return $this->math_op($args, $callback); 
		}
		
		public function temp_floor($args)
		{
			$callback = function($a, $b) { return floor($a); };
			return $this->math_op($args, $callback); 
		}
		
		public function temp_ceil($args)
		{
			$callback = function($a, $b) { return ceil($a); };
			return $this->math_op($args, $callback); 
		}
		
		public function temp_round($args)
		{
			$callback = function($a, $b) { return round($a); };
			return $this->math_op($args, $callback); 
		}
		
		// ----------
		
		private function math_op($args, $callback)
		{
			if (count($args) < 1)
			{
				return 0;
			}
		
			$result = floatval($args[0]);
			
			for ($i = 1; $i < count($args); $i++)
			{
				$result = $callback($result, floatval($args[$i]));
			}
			
			return $result;
		}
		
		// Compare:
		
		public function temp_eq($args)
		{
			$callback = function($a, $b) { return $a == $b; };
			return $this->bool_op($args, $callback);
		}
		
		public function temp_gt($args)
		{
			$callback = function($a, $b) { return $a > $b; };
			return $this->bool_op($args, $callback);
		}
		
		public function temp_lt($args)
		{
			$callback = function($a, $b) { return $a < $b; };
			return $this->bool_op($args, $callback);
		}
		
		public function temp_gte($args)
		{
			$callback = function($a, $b) { return $a >= $b; };
			return $this->bool_op($args, $callback);
		}
		
		public function temp_lte($args)
		{
			$callback = function($a, $b) { return $a <= $b; };
			return $this->bool_op($args, $callback);
		}
		
		// ----------
		
		private function bool_op($args, $callback)
		{
			if (count($args) < 2)
			{
				return false;
			}
			
			$data = $args[0];
			$value = true;
			
			for ($i = 1; $i < count($args); $i++)
			{
				$value = $value && $callback($data, $args[$i]);
				$data = $args[$i];
			}
			
			return $value;
		}

	}	
?>