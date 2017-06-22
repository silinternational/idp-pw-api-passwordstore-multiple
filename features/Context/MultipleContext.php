<?php
namespace Sil\IdpPw\PasswordStore\Google\Behat\Context;

use Behat\Behat\Context\Context;
use Exception;
use PHPUnit\Framework\Assert;
use Sil\IdpPw\Common\PasswordStore\PasswordStoreInterface;
use Sil\IdpPw\Common\PasswordStore\UserPasswordMeta;
use Sil\PhpEnv\Env;

class MultipleContext implements Context
{
    public function __construct()
    {
        require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
    }
}
