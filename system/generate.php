<?php
// generate epub

require('connect.php');
require('helper.php');
require('settings.php');


generateFileStructure('test-'.date('H.i.s'));


function generateFileStructure($epubName = "test"){
	mkdir($epubName, 0755);
	mkdir($epubName.'/META-INF', 0755);
	mkdir($epubName.'/OEBPS', 0755);
	mkdir($epubName.'/OEBPS/css', 0755);
	mkdir($epubName.'/OEBPS/images', 0755);
	mkdir($epubName.'/OEBPS/type', 0755);

	writeMimetype($epubName);
	writeContainerXML($epubName);
	writeCSS($epubName);
	copyType($epubName);
	generatePages($epubName);
  generatePackage($epubName);
  generateTOC($epubName);

}

function copyType($epubName){
	$typeDir = 'type/';
	$typeFiles = scandir($typeDir);
	foreach ($typeFiles as $typeFile) {
		if ($typeFile !== '.' && $typeFile !== '..') {
			copy($typeDir.$typeFile, $epubName.'/OEBPS/type/'.$typeFile);
		}
	}
}

function writeMimetype($epubName) {
	$mimetype = $epubName."/mimeteype";
	$mimetypeHandle = fopen($mimetype, 'w');
	fwrite($mimetypeHandle, "application/epub+zip");
	fclose($mimetypeHandle);
}

function writeContainerXML($epubName) {
	$containerXML = $epubName."/META-INF/container.xml";
	$filecontent = <<<XMLFILE
<?xml version="1.0"?>
<container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container">
  <rootfiles>
    <rootfile full-path="OEBPS/package.opf" media-type="application/oebps-package+xml"/>
  </rootfiles>
</container>
XMLFILE;
	$containerXMLhandle = fopen($containerXML, 'w');
	fwrite($containerXMLhandle, $filecontent);
	fclose($containerXMLhandle);
}

function generatePackage($epubName) {
  $time = date('Y-m-d-H:i:s');
  $fileName = $epubName.'/OEBPS/package.opf';
  $filelist = '';
  $imageList = '';
  $spine = '';
  $i = 1;
  $spread_result = mysql_query("SELECT * FROM spread");
  if (mysql_num_rows($spread_result)) {
      while($spread = mysql_fetch_assoc($spread_result)){
          $piq = "SELECT * FROM spreadItem WHERE spreadId=".$spread['id'];
          $pageId_result = mysql_query($piq);
          if (mysql_num_rows($pageId_result)) {
              while ($pageId = mysql_fetch_assoc($pageId_result)) {
                $pq = "SELECT * FROM item WHERE id=".$pageId['itemId'];
                $page_result = mysql_query($pq);
                $page = mysql_fetch_assoc($page_result);
                $filelist .= '      <item id="'.sprintf('pg%03d', $i).'" href="'.sprintf('page%03d.xhtml', $i).'" media-type="application/xhtml+xml"/>'.PHP_EOL;
                $spine    .= '      <itemref idref="'.sprintf('pg%03d', $i).'"/>'.PHP_EOL;
                if ($page['backgroundImage']) {
                  $imageList .= '      <item id="page'.sprintf('%03d', $i).'-bg" href="images/page'.sprintf('%03d', $i).'.jpg" media-type="image/jpeg"/>'.PHP_EOL;
                }
                $i++;
              }
          }
      }
  }
  
  $filecontent = <<<FILE
<?xml version="1.0" encoding="utf-8"?>
  <package xmlns="http://www.idpf.org/2007/opf" unique-identifier="bookid" version="3.0" prefix="rendition: http://www.idpf.org/vocab/rendition/# ibooks: http://vocabulary.itunes.apple.com/rdf/ibooks/vocabulary-extensions-1.0/">
    <metadata xmlns="http://www.idpf.org/2007/opf" xmlns:dc="http://purl.org/dc/elements/1.1/" >
      <dc:title>sample title</dc:title>
      <dc:identifier id="bookid">123456</dc:identifier>
      <dc:language>en</dc:language>
      <meta property="dcterms:modified">{$time}</meta>
      <dc:rights>.</dc:rights>    
      <dc:creator>sample creator</dc:creator>
      <meta property="ibooks:version">3.0</meta>
      <meta property="rendition:layout">pre-paginated</meta>
      <meta property="rendition:spread">auto</meta>
      <meta property="rendition:orientation">auto</meta>
    </metadata>
    
    <manifest>
      <!--supplementary -->
      <item id="toc" href="toc.xhtml" media-type="application/xhtml+xml" properties="nav"/>
      <item id="css-overall" href="css/stylesheet.css" media-type="text/css"/>
      <!--content-->  
{$filelist}

      <!--images-->
{$imageList}

      <!-- typefaces -->
      <item id="typeface-universlight" href="type/OpenSans-Regular.ttf" media-type="font/truetype" />
     
    </manifest>
    
    <spine>
{$spine}
    </spine>

  </package>
FILE;
  $fileHandle = fopen($fileName, 'w');
  fwrite($fileHandle, $filecontent);
  fclose($fileHandle);
}

function writeCSS($epubName) {
	$containerCSS = $epubName."/OEBPS/css/stylesheet.css";
	$filecontent = <<<CSS
@font-face {
    font-family: 'Univers';
    src: url('../type/UniversLTStd-Light.otf') format('opentype');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: 'Univers';
    src: url('../type/UniversLTStd-Black.otf') format('opentype');
    font-weight: bold;
    font-style: normal;
}
@font-face {
    font-family: 'Univers';
    src: url('../type/UniversLTStd-LightObl.otf') format('opentype');
    font-weight: normal;
    font-style: italic;
}
@font-face {
    font-family: 'Univers';
    src: url('../type/UniversLTStd-XBlack.otf') format('opentype');
    font-weight: 900;
    font-style: normal;
}


html, body { height: 100%; }
body { width: 429px;  height: 532px;  margin: 0;  -webkit-user-select: text; font-family: 'Univers', sans-serif; font-size: 11px; line-height: 16px; }
img { position: absolute; margin: 0; }

img.background { width: 429px; height: 532px; margin: 0; top: 0; left: 0; z-index: -1; pointer-events: none; }
.page { width: 429px; height: 532px; overflow: hidden; }
header { position: absolute;  line-height: 38px; font-weight: bold; font-size: 18px; width: 310px; background-color: #000; color: #FFF; padding: 0 10px; left: 47px; top: 42px;}
.content { width:380px; position: absolute; color: #000; left: 57px; width: 330px; height: 520px; top:0; }
.inner-content {position: absolute; bottom:0; }
.main-content {text-align: justify; font-size: 12px; line-height: 13px;
}
.content h1 { font-size: 40px; font-weight: 900; line-height: 40px; text-transform: uppercase; font-family: 'Univers', sans-serif; margin: 0 0 10px; padding-left: 10px;}

.quote { font-style: italic;}
.quote span { display: block; font-style: normal;}

.page002 img { position: absolute; left:0; top:0; z-index: -1; }
.page003 img { position: absolute; left:0; top:0; z-index: -1; }
.page004 img { position: absolute; left:0; top:0; z-index: -1; }
.page005 header { color: #fff;  background-color: #ED1C24;  width: 325px;  height: 36px;  position: absolute; left: 40px; top: 36px; font-weight: 900; line-height: 36px; padding-left: 1em; }
.page038 { background-color: #ffea00;}
.page038 header { color: #ffea00;}
.page042 header { background-color: transparent; color: #000; }
.page044 { background-color: #cfedfc;}
.page044 header { color: #cfedfc;}
.page046 header { background-color: #f49ab9;}
.page050 header { background-color: #00a651;}
.page054 header { background-color: transparent; color: #000; position: absolute; top: 409px; left: 212px;}
.page056 header { background-color: #ed1c24; }
.page064  { background-color: #ed1c24; }
.page064 header { color: #ed1c24; }
.page064 .firstline { position: relative;  right: 14px;}
.page066 header { background-color:#00a651; color: #FFF; }
.page070 { background-color:#d0a26f; }
.page070 .firstline { position: relative;  right: 14px; }
.page070 header { color:#d0a26f; }
.page072 { background-color:#231f20; color: #fff; }
.page072 .content { color: #fff; }
.page072 header { background-color: #fff; color:#231f20; }
.page074 { background-color:#ed1c24; }
.page074 header { color:#ed1c24; }
.page076 header { background-color:#604da0; }
CSS;
	$containerCSShandle = fopen($containerCSS, 'w');
	fwrite($containerCSShandle, $filecontent);
	fclose($containerCSShandle);
}


function generatePage($pageId, $epubName, $pageNumber, $spread = '#1'){
  $pq = "SELECT * FROM item WHERE id=".$pageId;
  $page_result = mysql_query($pq);
  $page = mysql_fetch_assoc($page_result);
  $fileName = $epubName."/OEBPS/page".$pageNumber.".xhtml";
  $content = html_entity_decode ($page['content'], ENT_COMPAT, "UTF-8");
  $title = html_entity_decode ($page['title'], ENT_COMPAT, "UTF-8");
  $hasText = FALSE;
  if ( isset($content) && trim($content) != '') {
    $hasText = TRUE;
  }
  $pageBackground = '';
  $pageText = '';
  if ( $hasText ) {
    $pageText = <<<PAGETEXT
  <header>{$spread}</header>
  <div class="content">
    <div class="inner-content">
      <h1>{$title}</h1>
      <div class="main-content">       
       {$content}
       </div>
     </div>
   </div>
PAGETEXT;
  }
  if ( $page['backgroundImage'] ) {
    $pageBackground = '<img src="images/page'.$pageNumber.'.jpg" width="429" height="532" alt=""/>'.PHP_EOL;

  }
  $pageContent = <<<PAGE
<?xml version="1.0" encoding="utf-8"?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xmlns:ibooks="http://vocabulary.itunes.apple.com/rdf/ibooks/vocabulary-extensions-1.0" epub:prefix="ibooks: http://vocabulary.itunes.apple.com/rdf/ibooks/vocabulary-extensions-1.0">
  <head>
  <title></title>
    <meta name="viewport" content="width=429, height=532"/>
  <meta charset="UTF-8"/>   
    <link href="css/stylesheet.css" type="text/css" rel="stylesheet"/>
  </head>
  <body>
    <div class="page{$pageNumber} page">
    {$pageBackground}
    {$pageText}
    </div>
  </body>
</html>
PAGE;
  $fileHandle = fopen($fileName, 'w');
  fwrite($fileHandle, $pageContent);
  fclose($fileHandle);
}

function generateTOC($epubName){
	$fileName = $epubName."/OEBPS/toc.xhtml";
	$pageContent = <<<PAGE
<?xml version="1.0" encoding="utf-8"?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xmlns:ibooks="http://vocabulary.itunes.apple.com/rdf/ibooks/vocabulary-extensions-1.0" epub:prefix="ibooks: http://vocabulary.itunes.apple.com/rdf/ibooks/vocabulary-extensions-1.0">
  <head>
    <title>Think Like A Lawyer (Don't Act Like One)</title>
  </head>
  
  <body>
    <nav id="toc" epub:type="toc">
      <h1 class="chapter">Table of Contents</h1>
      <ol>
        <li><a href="page001.xhtml">Cover</a></li>
      </ol>
    </nav>

  <nav epub:type="landmarks">
      <h1>Guide</h1>
      <ol>
        <li><a epub:type="ibooks:reader-start-page" href="page001.xhtml">Start Reading</a></li>
          <li><a epub:type="cover" href="page001.xhtml">Cover</a></li>
      <li><a epub:type="bodymatter" href="page002.xhtml">Start of Content</a></li>
      </ol>
  </nav>
  
  <nav epub:type="page-list">
    <ol>
      <li><a href="page001.xhtml">Cover</a></li>
      <li><a href="page002.xhtml">Never Lose Sight</a></li>
      <li><a href="page004.xhtml">Avoid Bickering</a></li>
    </ol>
  </nav>
  </body>
</html>
PAGE;
	$fileHandle = fopen($fileName, 'w');
	fwrite($fileHandle, $pageContent);
	fclose($fileHandle);
}

function generatePages($epubName) {
  $pageNumber = 0;
  $i = 0;
	$spread_result = mysql_query("SELECT * FROM spread");
	if (mysql_num_rows($spread_result)) {
	    while($spread = mysql_fetch_assoc($spread_result)){
          $i++;
	        $piq = "SELECT * FROM spreadItem WHERE spreadId=".$spread['id'];
	        $pageId_result = mysql_query($piq);
	        if (mysql_num_rows($pageId_result)) {
	            while ($pageId = mysql_fetch_assoc($pageId_result)) {
                $pageNumber++;
	            	generatePage( $pageId['itemId'], $epubName, sprintf('%03d', $pageNumber), '#'.$i );
	            }
	        }
	    }
	}

}