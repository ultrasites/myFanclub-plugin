<?php
namespace myfanclub\helper;

/**
 * Secure
 *
 * Encrypt and decrypt Class
 *
 *
 * @package  helper
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
class MyfcSecure
{
    private $salt= "myfc110120181916#f6(s1wQQb/=§ös";
    private $encrypt_method = "AES-256-CBC";
    private $iv;

    public function __construct()
    {
        $this->iv = substr(hash('sha256', 'gn/&%SS9!s'), 0, 16);
    }

    /**
     *
     * decrypt secure input
     *
     * @param $input
     * @return string
     */
    public function myfcDecrypt($input)
    {
        return openssl_decrypt(base64_decode($input), $this->encrypt_method, hash('sha256', $this->salt), 0, $this->iv);
    }

    /**
     *
     * encrypt value
     *
     * @param $input
     * @return string
     */
    public function myfcEncrypt($input)
    {
        return base64_encode(
            openssl_encrypt(
                $input,
                $this->encrypt_method,
                hash('sha256', $this->salt),
                0,
                $this->iv
            )
        );
    }
}
