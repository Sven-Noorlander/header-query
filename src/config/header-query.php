<?php

return [
	/*
	 |--------------------------------------------------------------------------
	 | Header names
	 |--------------------------------------------------------------------------
	 |
     | Here you may specify what the names are for the headers used in the
	 | header-query package. Make sure to use unique names to prevent unexpected
	 | behavior in the application.
	 |
	 */
	
	 // The header name that is used in the HeaderQueryEagerLoad trait
	 'header_name_eager_load' => 'EagerLoad',
		
	 // The header name that is used in the HeaderQueryFilter trait
	 'header_name_filter' => 'Filter',
		
	/*
	 |--------------------------------------------------------------------------
	 | Header separators
	 |--------------------------------------------------------------------------
	 |
     | Here you may specify what the separators are that will be used in the
     | headers. Make sure to use unique separators to prevent unexpected
	 | behavior in the application.
	 |
	 */
		
 	 // The header separator that is used to separate different queries that need to be added
	 'header_separator_queries' => '|',
		
	 // The header separator that is used to separate different input variables in a query that need to be added
	 'header_separator_variables' => ',',
];