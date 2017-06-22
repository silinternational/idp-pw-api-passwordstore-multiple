<?php
namespace Sil\IdpPw\PasswordStore\Multiple;

use Exception;
use InvalidArgumentException;
use Sil\IdpPw\Common\PasswordStore\PasswordStoreInterface;
use Sil\IdpPw\Common\PasswordStore\UserPasswordMeta;
use Sil\IdpPw\PasswordStore\Multiple\Exception\NotAttemptedException;
use yii\base\Component;

class Multiple extends Component implements PasswordStoreInterface
{
    /** @var array */
    public $passwordStoresConfig;
    
    /** @var PasswordStoreInterface[] */
    protected $passwordStores = [];
    
    /**
     * See if all the password store backends are available.
     *
     * @param string $employeeId The Employee ID to use to see if each password
     *     store is available.
     * @param string $taskDescription A short description of what is about to be
     *     attempted (e.g. 'set the password') if all backends are available.
     * @throws NotAttemptedException
     */
    protected function assertAllBackendsAreAvailable(
        $employeeId,
        $taskDescription
    ) {
        foreach ($this->passwordStores as $passwordStore) {
            try {
                $passwordStore->getMeta($employeeId);
            } catch (Exception $e) {
                throw new NotAttemptedException(sprintf(
                    'Did not attempt to %s because not all of the backends are '
                    . 'available. The %s password store gave this error when '
                    . 'asked for the specified user (%s): %s',
                    $taskDescription,
                    \get_class($passwordStore),
                    var_export($employeeId, true),
                    $e->getMessage()
                ), 1498163919, $e);
            }
        }
    }
    
    public function init()
    {
        parent::init();
        
        if (empty($this->passwordStoresConfig)) {
            throw new InvalidArgumentException(
                'You must provide config for at least one password store.',
                1498162679
            );
        }
        
        foreach ($this->passwordStoresConfig as $passwordStoreConfig) {
            $className = $passwordStoreConfig['class'];
            
            $configForClass = $passwordStoreConfig;
            unset($configForClass['class']);
            
            $this->passwordStores[] = new $className($configForClass);
        }
    }
    
    public function getMeta($employeeId): UserPasswordMeta
    {
        return $this->passwordStores[0]->getMeta($employeeId);
    }

    public function set($employeeId, $password): UserPasswordMeta
    {
        $this->assertAllBackendsAreAvailable($employeeId, 'set the password');
        $numSuccessfullySet = 0;
        $responses = [];
        foreach ($this->passwordStores as $passwordStore) {
            try {
                $responses[] = $passwordStore->set($employeeId, $password);
                $numSuccessfullySet++;
            } catch (Exception $e) {
                throw new Exception(sprintf(
                    'Failed to set the password using %s after successfully '
                    . 'setting it in %s other password store(s). Error: %s',
                    \get_class($passwordStore),
                    $numSuccessfullySet,
                    $e->getMessage()
                ), 1498162884, $e);
            }
        }
        return $responses[0];
    }
}
