<?php
class MyCurl
{
    public function __construct($debug = false)
    {
        $this->options = array(
            CURLOPT_URL            => null,
//            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_POSTFIELDS     => '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_VERBOSE        => $debug,
            CURLOPT_HTTPHEADER     => array("Expect:"),
            CURLOPT_HEADERFUNCTION => array($this, 'readHeader'),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        );
    }

    private function readHeader($ch, $header) {
        $match = 0;

        if(preg_match('/^([^:]+):\s*(.*?)$/', $header, $match))
        {
            $this->headers[$match[1]] = trim($match[2]);
        }

        return strlen($header);
    }

    public function get($url)
    {
        $options = $this->options;
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_HTTPGET] = true;

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return $response;
    }

    public function post($url, $input)
    {
        $options = $this->options;
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $input;

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return $response;
    }
}

class CurlRequest
{
    public static $debug = false;
    public static $user_agent = 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; pl; rv:1.9.2.2) Gecko/20100316 Firefox/3.6.2';

    public static function GET($url, $username = null, $password = null)
    {
        $opts = array(
            CURLOPT_URL            => $url,

            CURLOPT_HTTPHEADER     => array(
                'User-Agent: ' . self::$user_agent,
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: pl,en-us;q=0.7,en;q=0.3',
                'Accept-Charset: ISO-8859-2,utf-8;q=0.7,*;q=0.7',
                'Keep-Alive: 115',
                'Connection: keep-alive',
            ),

            CURLOPT_HTTPGET        => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_HEADER         => self::$debug,
            CURLOPT_VERBOSE        => self::$debug,
        );

        if ($username != null and $password != null) {
            $opts[CURLOPT_HTTPAUTH] = CURLAUTH_ANY;
            $opts[CURLOPT_USERPWD]  = sprintf('%s:%s', $username, $password);
        }

        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public static function POST($url, $data, $username = null, $password = null)
    {
        $opts = array(
            CURLOPT_URL            => $url,

            CURLOPT_HTTPHEADER     => array(
                'User-Agent: ' . self::$user_agent,
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: pl,en-us;q=0.7,en;q=0.3',
                'Accept-Charset: ISO-8859-2,utf-8;q=0.7,*;q=0.7',
                'Keep-Alive: 115',
                'Connection: keep-alive',
                'Referer: http://impuls.portaldokumentow.pl/default_IL.aspx',
                'Cookie: ASP.NET_SessionId=l5m5r1zfs3yxlr4lxfauoior',
                'Content-Type: application/x-www-form-urlencoded',
                'Expect:',
                'Content-Length: 1778',

            ),

            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_HEADER         => self::$debug,
            CURLOPT_VERBOSE        => self::$debug,
        );

        if ($username != null and $password != null) {
            $opts[CURLOPT_HTTPAUTH] = CURLAUTH_ANY;
            $opts[CURLOPT_USERPWD]  = sprintf('%s:%s', $username, $password);
        }

        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);
        curl_close($ch);

        print_r($result);

        return $result;
    }
}
