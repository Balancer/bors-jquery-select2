<?php

// Для проверок: https://id.aviaport.wrk.ru/cabinets/1/managers/

namespace B2\jQuery;

class Select2 extends \B2\jQuery\Plugin
{
	static function load($attrs = NULL)
	{
		\B2\jQuery::load();

		// If not installed bower-asset/select2 then using CDN
		if(empty(\bors::$bower_asset_packages['bower-asset/select2']))
		{
			\bors_use('https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.min.css');
			\bors_use('https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.min.js');
			\bors_use('https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2-bootstrap.min.css');
			\bors_use('https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2_locale_ru.min.js');
			return;
		}

		// Package bower-asset/jquery installed, use them
		$bower_asset_path = \B2\Cfg::get('bower-asset.path', '/bower-asset') . '/select2';
		\bors_use($bower_asset_path.'/select2.css');
		\bors_use($bower_asset_path.'/select2.min.js');
		\bors_use($bower_asset_path.'/select2-bootstrap.css');
		\bors_use($bower_asset_path.'/select2_locale_ru.js');
	}

	static function appear($el, $attrs)
	{
		self::load();

		$attrs = \blib_json::encode_jsfunc($attrs);
		\B2\jQuery::on_ready("$({$el}).select2($attrs)\n");
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

		if(preg_match('/^[\w\\\\]+$/', $class_name))
			$class_name = "'".addslashes($class_name)."'";

		if(preg_match('/^[\w\-,`]+$/', $order))
			$order = "'$order'";

		if(preg_match('/^[\w\-,`]+$/', $search))
			$search = "'$search'";

		if($w = popval($params, 'where'))
			$where = ",\n\t\twhere: ".json_encode($w);
		else
			$where = "";

//	https://admin.aviaport.ru/newses/257920/form2/
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
