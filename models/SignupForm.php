<?php
namespace micro\models;


// use micro\models\SignupForm;
use yii\base\Model;
use Yii;
use yii\db\ActiveRecord;
/**
 * Signup form
 */
class SignupForm extends ActiveRecord
{
    public $username;
    public $email;
    public $password;
    public $mobile;
    public $gender;
    public $nationality;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'micro\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'micro\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['mobile', 'required'],
            ['gender', 'required'],
            ['nationality', 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->phone = $this->mobile;
            $user->gender = $this->gender;
            $user->nationality = $this->nationality;
            // var_dump($this->nationality);
            // die();
            $user->generateAuthKey();

            
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}