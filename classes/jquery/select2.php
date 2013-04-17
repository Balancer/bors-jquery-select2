<?php

// Для проверок: http://id.aviaport.wrk.ru/cabinets/1/managers/

class jquery_select2
{
	function appear($el, $attrs)
	{
		$base = config('jquery.select2.base');
		bors_use("$base/select2.css");
		jquery::plugin("$base/select2.min.js");
		jquery::plugin("$base/select2_locale_ru.js");

		$attrs = blib_json::encode_jsfunc($attrs);
//		var_dump($attrs);
		jquery::on_ready("$({$el}).select2($attrs)\n");
	}
}
