<?php

// Для проверок: http://id.aviaport.wrk.ru/cabinets/1/managers/

class jquery_select2
{
	static function appear($el, $attrs)
	{
		bors_use("/bower-asset/select2/select2.css");
		jquery::plugin("/bower-asset/select2/select2.min.js");
		jquery::plugin("/bower-asset/select2/select2_locale_ru.js");

		$attrs = blib_json::encode_jsfunc($attrs);
		jquery::on_ready("$({$el}).select2($attrs)\n");
	}

	static function appear_ajax($el, $class_name, $params = array())
	{
		$title_field = popval($params, 'title_field', 'title');
		$order = popval($params, 'order', 'title');
		$search = popval($params, 'search_fields', 'title');
		$width = popval($params, 'width', '');
		popval($params, 'name');
		popval($params, 'value');
		popval($params, 'json');

		if(popval($params, 'dropdownAutoWidth'))
			$autowidth = ",\n\t\tdropdownAutoWidth: true";
		else
			$autowidth = "";

		if($width)
			$width = ",\n\t\twidth:\"".htmlspecialchars($width)."\"";
		else
			$width = "";

		if(preg_match('/^\w+$/', $class_name))
			$class_name = "'$class_name'";

		if(preg_match('/^[\w\-,`]+$/', $order))
			$order = "'$order'";

		if(preg_match('/^[\w\-,`]+$/', $search))
			$search = "'$search'";

		if($w = popval($params, 'where'))
			$where = ",\n\t\twhere: ".json_encode($w);
		else
			$where = "";

//	http://admin2.aviaport.wrk.ru/newses/257920/form2/
		$js_params = array_merge($params, [
			'ajax' => [
				'minimumInputLength' => 2,
//				'placeholder' => "Search for a movie",
				'url' => '/_bors/data/lists/',
				'data' => "function (text, page) { return {
		class: $class_name,
		q: text,
		s: 50,
		tpl: '{\"id\":\"id\",\"text\":\"".$title_field."\"}',
		order: ".$order.",
		search: ".$search.",
		results: \"results\"{$autowidth}{$width}{$where}
	}
}",
				'results' => 'function (data, page) { return data }',
			],
		]);

		if(!empty($params['create_new']))
		{
			$js_params['createSearchChoice'] = 'function (term, data) {
				if($(data).filter(function () {
					return this.text.localeCompare(term) === 0;
				}).length === 0) {
					return {
						id: term,
						text: term
					};
				}
			}';

			$js_params['selectOnBlur'] = true;
		}

		self::appear($el, $js_params);
	}
}
