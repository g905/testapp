<?
class TestApp_Eurofxref {
    function getRatesRemote() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $url = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200) {
            throw new Exception($res . PHP_EOL . $url . " " . curl_error($ch), 450);
        }
        curl_close($ch);
    }
    function getRates($doc) {
        return $doc;
    }
    function transformFo($xml) {
        $xmldom = new DOMDocument();
        $xmldom->loadXML($xml);
        $xsldom = new DomDocument();
        $xsldom->load("stylesheets/TestApp/eurofxref-daily.xsl"); // need to rename
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsldom);
        $res = $proc->transformToXML($xmldom);
        return $res;
    }
    function transformPdf($xml) {
        $url = "https://demo01.ilb.ru/fopservlet/fopservlet";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200) {
            throw new Exception($res . PHP_EOL . $url . " " . curl_error($ch), 450);
        }
        curl_close($ch);
        return $res;
    }
}
?>
