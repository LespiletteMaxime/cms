<?php

use Devise\Pages\Fields\FieldValue;
use Illuminate\Database\Eloquent\SoftDeletes;

class DvsModelField extends Eloquent
{
    use SoftDeletes;

    public $dvs_type = 'model_field';

    protected $softDelete = true;

	protected $table = 'dvs_model_fields';

    protected $guarded = array();

    protected $_value;

    protected $dates = ['deleted_at'];

    /**
     * Accessor on this model to get value
     * for the latestVersion of this field
     *
     * @return  FieldValue
     */
    public function getValueAttribute()
    {
        $json = $this->json_value ?: '{}';
        $this->_value = $this->_value ?: new FieldValue($json);
        return $this->_value;
    }

    /**
     * Sometimes we've been using values instead of value
     * so this is here for backwards compatability support
     *
     * @return FieldValue
     */
    public function getValuesAttribute()
    {
        return $this->getValueAttribute();
    }

    /**
     * Let's us know if the scope of this field is global or page
     *
     * @return string
     */
    public function getScopeAttribute()
    {
        return 'page';
    }
}