<?php

// Для проверок: http://id.aviaport.wrk.ru/cabinets/1/managers/

class jquery_select2
{
	static function appear($el, $attrs)
	{
		$base = config('jquery.select2.base');
		bors_use("$base/select2.css");
		jquery::plugin("$base/select2.min.js");
		jquery::plugin("$base/select2_locale_ru.js");

		$attrs = blib_json::encode_jsfunc($attrs);
//		var_dump($attrs);
		jquery::on_ready("$({$el}).select2($attrs)\n");
	}

	static function appear_ajax($el, $class_name, $params = array())
	{
		self::appear($el, array(
			'ajax' => array(
				'minimumInputLength' => 3,
//				'placeholder' => "Search for a movie",
				'url' => '/_bors/data/lists/',
				'data' => "function (text, page) { return {
						class: '$class_name',
						q: text,
						s: 10,
						tpl: '{\"id\":\"id\",\"text\":\"".defval($params, 'title_field', 'title')."\"}',
						order: \"".defval($params, 'order', 'title')."\",
						search: \"title\",
						results: \"results\"
					} }",
				'results' => 'function (data, page) { return data }',
			),
		));
	}
}
