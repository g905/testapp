<?php

require_once("../conf/bootstrap.php");

//читаем данные и HTTP-запроса, строим из них XML по схеме
$hreq = new HTTP_Request2Xml("schemas/TestApp/DocumentListRequest.xsd");
$req=new TestApp_DocumentListRequest();
if (!$hreq->isEmpty()) {
	$hreq->validate();
	$req->fromXmlStr($hreq->getAsXML());
}

// формируем xml-ответ
$xw = new XMLWriter();
$xw->openMemory();
$xw->setIndent(TRUE);
$xw->startDocument("1.0", "UTF-8");
$xw->writePi("xml-stylesheet", "type=\"text/xsl\" href=\"stylesheets/TestApp/DocumentList.xsl\"");
$xw->startElementNS(NULL, "DocumentListResponse", "urn:ru:ilb:meta:TestApp:DocumentListResponse");
$req->toXmlWriter($xw);
// Если есть входные данные, проведем вычисления и выдадим ответ
if (!$hreq->isEmpty()) {
	$pdo=new PDO("mysql:host=localhost;dbname=testapp","testapp","1qazxsw2",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	//prior to PHP 5.3.6, the charset option was ignored. If you're running an older version of PHP, you must do it like this:
	$pdo->exec("set names utf8");
	$query = "SELECT * FROM document WHERE docDate BETWEEN :dateStart AND :dateEnd AND displayName LIKE :name";
	$sth=$pdo->prepare($query);
	$sth->execute(array(":dateStart"=>$req->dateStart,":dateEnd"=>$req->dateEnd, ":name"=>$req->nameContains));
	while($row=$sth->fetch(PDO::FETCH_ASSOC)) {
		$doc = new TestApp_Document();
		$doc->fromArray($row);
		$doc->toXmlWriter($xw);
	}
}
$xw->endElement();
$xw->endDocument();
//Вывод ответа клиенту
//header("Content-Type: text/xml");
//echo $xw->flush();
$document = $xw->flush();

$format = $req->outputFormat;

$headers = array();
$output = null;
$erofxref = new TestApp_Eurofxref();
$ratesxml = $erofxref->getRates($document);

switch ($format) {
    case "html":
        $headers = array("Content-type: text/xml");
        $output = $document;
        break;
    case "pdf":
        $attachmentName = "eurofxref-daily.pdf";
        $headers = array(
            "Content-Type: application/pdf",
            "Content-Disposition: inline; filename*=UTF-8''" . $attachmentName
        );
        $fo = $erofxref->transformFo($ratesxml, $format);
        $output = $erofxref->transformPdf($fo);
        break;
    default:
        $headers = array("Content-type: text/xml");
        $output = $document;
}
foreach ($headers as $h) {
    header($h);
}
echo $output;

