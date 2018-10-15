<?php

namespace SvenNoorlander\HeaderQuery\traits;

use Illuminate\Database\Eloquent\Builder;
use Exception;

/**
 * Trait HeaderQueryFilter
 *
 * @author Sven Noorlander <s.noorlander@sqits.nl>
 * @since 1.0.0
 * @package App\Traits
 *
 */
trait HeaderQueryFilter
{
    /**
     * Build the query based on the give input 
     *
     * @author Sven Noorlander <s.noorlander@sqits.nl>
     * @since 1.0.0
     *
     * @param Illuminate/Database/Eloquent/Builder $query
     * @return Illuminate/Database/Eloquent/Builder $query
     * @throws Exception $e
     */
    public function scopeHeaderQueryFilter(Builder $query):Builder
    {
        try {
            $queryParts = $this->convertFilterHeader();
            
            foreach($queryParts as $queryPart) {
                $query = $this->addFilterQuery($query, $queryPart);
            }
            
            return $query;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Build the query based on the give input
     *
     * @author Sven Noorlander <s.noorlander@sqits.nl>
     * @since 1.0.0
     *
     * @param Illuminate/Database/Eloquent/Builder $query
     * @param array $queryPart
     * @return Illuminate/Database/Eloquent/Builder $query
     */
    private function addFilterQuery(Builder $query, array $queryPart):Builder
    {     
        // Check if the filter needs to be executed in a relation. If so, add a whereHas to the query for the relation
        if ($queryPart['relation'] == null) {
            switch ($queryPart['type']) {
                case 'where':           
                    return $query->where($queryPart['name'], $queryPart['operator'], $queryPart['value']);
            }
        } else {
            return $query->whereHas($queryPart['relation'][0], function($query) use ($queryPart) {
                unset($queryPart['relation'][0]);
                $queryPart['relation'] = array_values($queryPart['relation']);

                $this->addFilterQuery($query, $queryPart);
            });
        }
    }
    
    /**
     * Convert the header to a processable array.
     *
     * @author Sven Noorlander <s.noorlander@sqits.nl>
     * @since 1.0.0
     *
     * @return array $query
     * @throws Exception $e
     */
    private function convertFilterHeader():array
    {
        try {
        	$headerParts = request()->header(config('header-query.header_name_filter')) ?? null;
            
            if ($headerParts) {
                $headerParts = explode('|', $headerParts);
                
                $headerParts = array_unique($headerParts);
        
                $headerParts = array_filter($headerParts);
                
                $queryParts = [];
                
                foreach ($headerParts as $headerPart) {
                    $headerPart = explode(',', $headerPart);
                    
                    switch ($headerPart[0]) {
                        case 'where':      
                            if (array_key_exists(4, $headerPart)) {
                                $relation = explode('.', $headerPart[4]);
                            } else {
                                $relation = null;
                            }
                            
                            array_push($queryParts, [
                                'type' => $headerPart[0],
                                'name' => $headerPart[1],
                                'operator' => $headerPart[2],
                                'value' => $headerPart[3],
                                'relation' => $relation,
                            ]);
                            break;
                        default:
                        	abort(400, 'Invalid "' . config('header-query.header_name_filter') . '" header');
                    }
                }
                
                return $queryParts;
            } else {
                return [];
            }
        } catch (Exception $e) {
        	abort(400, 'Invalid "' . config('header-query.header_name_filter') . '" header');
        }
    }
    
}