*This repository is no longer maintained. It has been incorporated
into [idp-pw-api](https://github.com/silinternational/idp-pw-api).*

# idp-pw-api-passwordstore-multiple
Password store component for IdP PW API for using multiple backends.

## Example Usage

    use Sil\IdpPw\PasswordStore\Google\Google;
    use Sil\IdpPw\PasswordStore\IdBroker\IdBroker;
    use Sil\IdpPw\PasswordStore\Ldap\Ldap;
    use Sil\IdpPw\PasswordStore\Multiple\Multiple;
    
    // ...
    
    $multiple = new Multiple([
        'passwordStoresConfig' => [
            [
                'class' => IdBroker::class,
                // ... properties needed by this password store...
            ],
            [
                'class' => Google::class,
                // ... properties needed by this password store...
            ],
            [
                'class' => Ldap::class,
                // ... properties needed by this password store...
            ],
        ],
    ]);

## It Matters Which Password Store Comes First

Whichever password store you place first in the `passwordStoresConfig` list 
will be considered the primary password store.

When you call `set(...)` on this `Multiple` class, it will in turn call 
`set(...)` on each of the password stores you have defined, but only the return 
value of the first password stores' call will be returned to you.

When you call `getMeta(...)` on this `Multiple` class, it will only call 
`getMeta(...)` on the first password store in the list, returning it's resposne 
to you.

## Features

For more information about this password store's features, see the 
`features/multiple.feature` file.
