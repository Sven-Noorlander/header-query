<?php

namespace SvenNoorlander\HeaderQuery\traits;

trait HeaderQuery 
{
	use HeaderQueryEagerLoad;
	use HeaderQueryFilter;
	
	public function scopeHeaderQuery($query) {
		return $query->HeaderQueryEagerLoad()->HeaderQueryFilter();
	}
}