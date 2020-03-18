<?php

class nsc_bar_cookie_saver
{
    public function nsc_bar_save_cookie($cookiename, $path, $cookieDomain, $expiryDays, $secure)
    {

        $expiryDate = $this->get_expiry_date($cookiename, $expiryDays);

        if (empty($cookieDomain)) {
            $cookieDomain = "";
        }

        if (isset($_COOKIE[$cookiename])) {
            setcookie($cookiename, $_COOKIE[$cookiename], $expiryDate, $path, $cookieDomain, $secure);
            setcookie(ITP_SAVER_COOKIE_NAME, $cookiename . "---_---" . $expiryDate, $expiryDate, $path, $cookieDomain, $secure, true);
        }
    }

    /*
    will return the expirydate.
    if the cookie was already handled by the saver it will return the original date stored in a cookie
    if the cookie was never handled by this script it will create a fresh one.
     */

    private function get_expiry_date($cookiename, $expiryDays)
    {
        $expiryDate = time() + 60 * 60 * 24 * $expiryDays;

        if (!isset($_COOKIE[ITP_SAVER_COOKIE_NAME])) {
            return $expiryDate;
        }

        $done_cookie_values = explode("---_---", $_COOKIE[ITP_SAVER_COOKIE_NAME]);

        if (count($done_cookie_values) != 2) {
            return $expiryDate;
        }

        if ($done_cookie_values[0] != $cookiename) {
            return $expiryDate;
        }

        return $done_cookie_values[1];
    }
}
