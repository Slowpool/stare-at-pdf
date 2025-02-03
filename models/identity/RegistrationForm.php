<?php

namespace app\models\identity;

use Yii;
use yii\base\Model;
use app\models\domain\UserRecord;

/**
 * RegistrationForm is the model behind the registration form.
 *
 * @property-read UserRecord|null $user
 *
 */
class RegistrationForm extends LoginForm
{
    public function cancelRegistration()
    {
        try {
            $this->_user->delete();
        } catch (\Exception $e) {
            // the record was not created. exception is ignored.
        }
    }

    /**
     * 
     * @return bool True - successfully registered. False - user with such a username already exists
     * @throws \Exception Failed to register
     */
    public function registerAndLogin(): bool
    {
        try {
            $this->_user = UserRecord::register($this->username, $this->password);
            return $this->_user
                ? $this->login()
                : false;
        }
        // failed to register
        catch (\Exception $e) {
            $this->cancelRegistration();
            throw $e;
        }
    }
}
