<?php
namespace micro\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class StarRatingForm extends Model
{
    public $rating;
   /**
     * Define validation rules.
     */
    public function rules()
    {
        return [
            ['rating', 'required'],
            ['rating', 'integer', 'min' => 1, 'max' => 5],
        ];
    }
    /**
     * Optionally add attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'rating' => 'Your Rating',
        ];
    }
}
