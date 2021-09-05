namespace AlgolTeam;

class Utility {

    private function getClientSerial() {
        $arr = ['HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR'];
        foreach($arr as $item) {
            if (!empty($_SERVER[$item]) && filter_var($_SERVER[$item], FILTER_VALIDATE_IP)) return $_SERVER[$item];
        }
        $arr = ['DISKDRIVE', 'bios'];
        foreach($arr as $item) {
            $result = trim(str_replace('SerialNumber', '', shell_exec('wmic ' . $item . ' GET SerialNumber 2>&1')));
            if (isset($result)) return $result;
        }
        return "UNKNOWN";
    }

    public static function getSerial() {
        return self::getClientSerial();
    }

}