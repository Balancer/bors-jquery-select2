<?php

class jquery_select2
{
	function appear($el, $attrs)
	{
		bors_use('/_bors-3rd/jquery/plugins/select2-release-3.2/select2.css');
		jquery::plugin('/_bors-3rd/jquery/plugins/select2-release-3.2/select2.js');

		$attrs = blib_json::encode_jsfunc($attrs);
//		var_dump($attrs);
		jquery::on_ready("$({$el}).select2($attrs)\n");
	}
}
