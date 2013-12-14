<?php
/**
 * A random string used in security hashing methods.
 */	Configure::write('Security.salt', 'a668f877ee39dec0ac3c59a91970011538c20c30');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */	Configure::write('Security.cipherSeed', '353137383463373366616438373331');

/**
* Want to reset your Key/Secret?
* Replace value of 'api_key' with { api_key } (no spaces)
* Replace value of 'api_secret' with { api_secret } (no spaces)
*/
Configure::write('api_key', '{api_key}');
Configure::write('api_secret', '{api_secret}');