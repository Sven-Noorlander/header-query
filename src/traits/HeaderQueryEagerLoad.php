<?php

namespace SvenNoorlander\HeaderQuery\traits;

use Illuminate\Database\Eloquent\Builder;
use Exception;
use Illuminate\Support\Facades\Config;

/**
 * Trait HeaderQueryEagerLoad
 *
 * @author Sven Noorlander <s.noorlander@sqits.nl>
 * @since 1.0.0
 * @package App\Traits
 *
 */
trait HeaderQueryEagerLoad
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
    public function scopeHeaderQueryEagerLoad(Builder $query):Builder
    {
        try {
            $queryParts = $this->convertEagerLoadHeader();
            
            foreach($queryParts as $queryPart) {
            	$query = $this->addEagerLoadQuery($query, $queryPart);
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
     * @param string $relation
     * @return Illuminate/Database/Eloquent/Builder $query
     */
    private function addEagerLoadQuery(Builder $query, array $relation):Builder
    {
        return $query->with($relation['relation']);
    }
    
    /**
     * Build the query based on the give input
     *
     * @author Sven Noorlander <s.noorlander@sqits.nl>
     * @since 1.0.0
     *
     * @return array $headerParts
     * @throws Exception $e
     */
    private function convertEagerLoadHeader():array
    {
        try {
        	$headerParts = request()->header(config('header-query.header_name_eager_load')) ?? null;

            if ($headerParts) {
            	$queryParts = [];
            	
                $headerParts = explode('|', $headerParts);

                $headerParts = array_unique($headerParts);

                $headerParts = array_filter($headerParts);
                
                foreach ($headerParts as $headerPart) { 
                    array_push($queryParts, [
                        'relation' => $headerPart,
                    ]);
                }
                
                return $queryParts;
            } else {
                return [];
            }
        } catch (Exception $e) {
        	abort(400, 'Invalid "' . config('header-query.header_name_eager_load') . '" header');
        }
    }
    
}