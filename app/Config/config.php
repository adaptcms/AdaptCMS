<?php
/**
 * A random string used in security hashing methods.
 */	Configure::write('Security.salt', 'gbFmS141sZOQBzTqTuEv1qKYjtpx4dI55gwE6wQ');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */	Configure::write('Security.cipherSeed', '343945644476450161825016663774');

/**
* Want to reset your Key/Secret?
* Replace value of 'api_key' with { api_key } (no spaces)
* Replace value of 'api_secret' with { api_secret } (no spaces)
*/
Configure::write('api_key', '{api_key}');
Configure::write('api_secret', '{api_secret}');