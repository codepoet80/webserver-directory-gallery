<html>
<head>
<title>Directory Gallery</title>
<style>
img { padding: 8px; }
h1 { text-align: center; }

.modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding: 10px 62px 0px 62px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: black;
}

.modal-content {
  position: relative;
  display: none;
  flex-direction: column;
  justify-content: center;
  margin: auto;
  padding: 0 20 0 0;
  height: 100%;
  max-width: 1200px;
}

.spinner {
  display: none;
  position: fixed;
  z-index: 1031; /* High z-index so it is on top of the page */
  top: 50%;
  right: 50%; /* or: left: 50%; */
  margin-top: -71px; /* half of the elements height */
  margin-right: -71px; /* half of the elements width */
}

</style>
<script>
function openLightbox(imgPath) {
    document.getElementById("spinner").style.display = "block";
    document.getElementById("lightboxContainer").style.display = "block";
    document.getElementById("lightboxContainer").style.opacity = "50%";
    document.getElementById("lightboxImg").src = imgPath;
    lightboxImg.onload = function() {
        document.getElementById("spinner").style.display = "none";
        document.getElementById("lightboxContainer").style.opacity = "100%";
        document.getElementById("lightboxImg").style.display = "block";
        document.getElementById("lightboxImg").style.display = "flex";
    };
}

function closeLightbox() {
    document.getElementById("spinner").style.display = "none";
    lightboxContainer.style.display = "none";
    document.getElementById("lightboxImg").style.display = "none";
}
</script>
</head>
<body>
<?php
include('common.php');
$config = include('config.php');
$handlers = include('handlers.php');
$baseDir = $config['directoryBase'];

$query = $_SERVER['QUERY_STRING'];
$query = base64url_decode($query);
$baseDir = $baseDir . str_replace(" ", "%20", $query . "/");

//echo "new base dir: " . $baseDir . "<br>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseDir);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

//echo "response: " . $response;

$data = json_decode($response, true);
if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != "") {
    echo "<h1>" . $query . "</h1>";
	echo "<a href='javascript:history.back()'><img src='folder-back.png' height='32' align='absmiddle'>../</a><br>\n";
} else {
    echo "<h1>" . $config['title'] . "</h1>";
}
$count = 0;
foreach ($data as $item) {
	if ($item['type'] == "directory") {
		echo "<a href='?" . base64url_encode($query . "/" . $item['name']) . "'>";
        echo "<img src='folder.png' height='32' align='absmiddle'>";
		echo $item['name'];
		echo "</a><br>\r\n";
	}
	if ($item['type'] == "file") {
        $filenameParts = explode('.', $item['name']);
        $extension = end($filenameParts);
        $handler = find_file_handler($extension, $handlers);
        $file_path = $baseDir . $item['name'];
        switch ($handler) {
            case "image.php":
                echo "<img src='image.php?" . base64url_encode($file_path) . "' height='200' onclick='openLightbox(\"image.php?" . base64url_encode($file_path) . "\");'>\r\n";  
                break;
            default:
        }
	}
    $count++;
}

function find_file_handler($extension, $handlers) {
    $extension = "." . strtolower($extension);
    $key = 0;
    foreach ($handlers as $handler) {
        //print_r($handler);
        if (in_array($extension, $handler)) {
            return (array_keys($handlers)[$key]);
        }
        $key++;
    }
}
echo "<!--";
echo json_encode($data, JSON_PRETTY_PRINT);
echo "-->";
?>
<div id="lightboxContainer" class="modal">
    <img id="lightboxImg" class="modal-content" onclick="closeLightbox()">
</div>
<img id="spinner" src="spinner.gif" class="spinner">
</body>
</html>
