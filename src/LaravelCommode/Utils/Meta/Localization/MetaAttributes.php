<?php

namespace LaravelCommode\Utils\Meta\Localization;

abstract class MetaAttributes
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $elementTag = 'label';

    /**
     * @var string
     */
    private $lookUpLocation = 'validation.attributes';

    public function __construct($locale = null)
    {
        $this->setLocale($locale ?: app()->getLocale());
    }
    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
    public function __get($field)
    {
        $requiredField = $this->getLocale()."_".$field;

        if (property_exists($this, $requiredField)) {
            return $this->{$requiredField};
        } else {
            $fieldKey = $this->getLookUpLocation().'.'.$field;

            if (($res = trans($fieldKey)) !== $fieldKey) {
                return $res;
            }
        }
        return $field;
    }
    /**
     * @return string
     */
    public function getLookUpLocation()
    {
        return $this->lookUpLocation;
    }

    /**
     * @param string $lookUpLocation
     * @return $this
     */
    public function setLookUpLocation($lookUpLocation)
    {
        $this->lookUpLocation = $lookUpLocation;
        return $this;
    }

    /**
     * @return string
     */
    public function getElementTag()
    {
        return $this->elementTag;
    }

    /**
     * @param string $elementTag
     * @return $this
     */
    public function setElementTag($elementTag)
    {
        $this->elementTag = $elementTag;
        return $this;
    }

    public function element($key)
    {
        return "<{$this->elementTag}>{$this->{$key}}</{$this->elementTag}>";
    }
}
