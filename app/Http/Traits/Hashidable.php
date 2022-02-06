<?php
namespace App\Http\Traits;

trait Hashidable
{
    /**
     * Get route key
     *
     * @return string
     */
    public function getRouteKey()
    {
        return \Hashids::connection(get_called_class())->encode($this->getKey());
    }

    /**
     * Append hashid
     *
     * @return void
     */
    public function initializeHashidableTrait()
    {
        $this->append('hashid');
    }

    /**
     * Get hashid
     *
     * @return string
     */
    public function getHashidAttribute()
    {
        return \Hashids::encode($this->attributes['id']);
    }
}
