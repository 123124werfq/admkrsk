<?php

namespace common\base;

class Request extends \yii\web\Request
{
    protected function getSecureForwardedHeaderTrustedParts()
    {
        $validator = $this->getIpValidator();

        $ranges = [];
        foreach ($this->trustedHosts as $key => $value) {
            $ranges[] = is_string($value) ? $value : $key;
        }

        $validator->setRanges($ranges);
        return array_filter($this->getSecureForwardedHeaderParts(), function ($headerPart) use ($validator) {
            return isset($headerPart['for']) ? !$validator->validate($headerPart['for']) : true;
        });
    }

    protected function getUserIpFromIpHeaders()
    {
        $ip = $this->getUserIpFromIpHeader($this->getSecureForwardedHeaderTrustedPart('for'));
        if ($ip !== null) {
            return $ip;
        }

        foreach ($this->ipHeaders as $ipHeader) {
            if ($this->headers->has($ipHeader)) {
                $ip = $this->getUserIpFromIpHeader($this->headers->get($ipHeader));
                if ($ip !== null) {
                    return $ip;
                }
            }
        }
        return null;
    }

    protected function getUserIpFromIpHeader($ips)
    {
        $ips = trim($ips);
        if ($ips === '') {
            return null;
        }
        $ips = preg_split('/\s*,\s*/', $ips, -1, PREG_SPLIT_NO_EMPTY);
        krsort($ips);
        $validator = $this->getIpValidator();
        $resultIp = null;
        foreach ($ips as $ip) {
            if ($ip !== null && preg_match(
                '/^\[?(?P<ip>(?:(?:(?:[0-9a-f]{1,4}:){1,6}(?:[0-9a-f]{1,4})?(?:(?::[0-9a-f]{1,4}){1,6}))|(?:[\d]{1,3}\.){3}[\d]{1,3}))\]?(?::(?P<port>[\d]+))?$/',
                $ip,
                $matches
            )) {
                $ip = $matches['ip'];
                if ($ip === null) {
                    break;
                }
            }
            $validator->setRanges('any');
            if (!$validator->validate($ip) /* checking IP format */) {
                break;
            }
            $resultIp = $ip;
            $isTrusted = false;
            foreach ($this->trustedHosts as $trustedCidr => $trustedCidrOrHeaders) {
                if (!is_array($trustedCidrOrHeaders)) {
                    $trustedCidr = $trustedCidrOrHeaders;
                }
                $validator->setRanges($trustedCidr);
                if ($validator->validate($ip) /* checking trusted range */) {
                    $isTrusted = true;
                    break;
                }
            }
            if (!$isTrusted) {
                break;
            }
        }
        return $resultIp;
    }
}
