<?php

namespace LaravelCommode\Utils\Meta\Localization;

class MetaManager
{
    protected $metas = [];

    public function registerMetaAttributes($shortCut, $classname)
    {
        if (last(array_values(class_parents($classname))) !== MetaAttributes::class) {
            throw new \InvalidArgumentException(
                'Meta attribute but be extended from '.MetaAttributes::class.'. '.$classname.' was given.'
            );
        }

        $this->metas[$shortCut] = $classname;
    }

    /**
     * @param string $shortCut
     * @return MetaAttributes
     */
    public function getMetaAttributes($shortCut)
    {
        $meta = $this->metas[$shortCut];
        return new $meta(app()->getLocale());
    }
}
