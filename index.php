<?php
$mysqli = new mysqli("localhost", "root", "", "coba_gis");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$locations = "SELECT cities.id, provinces.name, cities.name  FROM cities INNER JOIN provinces ON cities.province_id=provinces.id";

if (isset($_REQUEST['city_id']) && !empty($_REQUEST['city_id'])) {
    $city = $_REQUEST['city_id'];
    $polygons = "SELECT id, city_id, name, t_2014, t_2015, AsText(shape), color FROM polygons WHERE city_id = $city";
} else {
    $city = null;
    $polygons = "SELECT id, city_id, name, t_2014, t_2015, AsText(shape), color FROM polygons";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SIG Pendidikan</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/animate.css" rel="stylesheet"/>
    <!-- Squad theme CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="color/default.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet">

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">
<!-- Preloader -->
<div id="preloader">
    <div id="load"></div>
</div>

<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="index.php">
                <h1>SISTEM INFORMASI GEOGRAFIS</h1>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>
                <li><a href="#peta">Peta</a></li>
                <li><a href="#about">Data</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Section: intro -->
<section id="intro" class="intro">

    <div class="slogan">
        <h2>PEMETAAN WILAYAH BERDASARKAN TARAF PENDIDIKAN MASYARAKAT JAWA TIMUR</h2>
    </div>
    <div class="page-scroll">
        <a href="#peta" class="btn btn-circle">
            <i class="fa fa-angle-double-down animated"></i>
        </a>
    </div>
</section>
<!-- /Section: intro -->


<!-- Section: peta -->
<section id="peta" class="home-section">
    <div class="peta">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-lg-offset-0">
                    <div class="wow bounceInDown" data-wow-delay="0.4s">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <div class="row form-group wow bounceInDown" data-wow-delay="0.4s">
                <div class="col-lg-12">
                    <form id="form-filter">
                        <label class="control-label" for="city_id">Filter Kabupaten/Kota :</label>
                        <select id="city_id" class="form-control" name="city_id">
                            <option></option>
                            <?php
                            if ($result = $mysqli->query($locations)) {
                                $data = $result->fetch_all();
                                $province = "";
                                foreach ($data as $row) {
                                    if ($province != $row[1]) {
                                        $province = $row[1];
                                        echo "<optgroup label='" . $province . "'>";
                                    }
                                    echo "<option value='" . $row[0] . "' " . ($city != null && $city == $row[0] ? 'selected' : '') . ">" . $row[2] . "</option></optgroup>";
                                }
                                $result->free_result();
                            }
                            ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Section: peta -->


<!-- Section: about -->
<section id="about" class="home-section text-center">
    <div class="container"></div>
</section>
<!-- /Section: about -->

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="wow shake" data-wow-delay="0.4s">
                    <div class="page-scroll marginbot-30">
                        <a href="#peta" class="btn btn-circle">
                            <i class="fa fa-angle-double-up animated"></i>
                        </a>
                    </div>
                </div>
                <p>M. Aguk Nur Anggraini (18051204081), Restian Hanifia (18051204080), Probo Novian C (18051204082),
                    Yudhistira Satrio Y (16051204045)
                <div class="credits">
                    <!--
                      All the links in the footer should remain intact.
                      You can delete the links only if you purchased the pro version.
                      Licensing information: https://bootstrapmade.com/license/
                      Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Squadfree
                    -->
                    S1 Teknik Informatika - Universitas Neger Surabaya @2019
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Core JavaScript Files -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.easing.min.js"></script>
<script src="js/jquery.scrollTo.js"></script>
<script src="js/wow.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="js/custom.js"></script>
<script src="contactform/contactform.js"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd3dSy2ivrW8j-Pmz12_bs2rwSaCapCx8&callback=initialize"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script type="text/javascript">
    var geocoder, map, $city_id = $("#city_id"), google;

    $(function () {
        $city_id.select2({
            placeholder: "-- Pilih --",
            allowClear: true,
            width: '100%',
        });

        <?php if(isset($_REQUEST['city_id']) || !empty($_REQUEST['city_id'])) { ?>
        $('html,body').animate({scrollTop: $("#map").offset().top}, 500);
        <?php } ?>
    });

    function initialize() {
        var map = new google.maps.Map(
            document.getElementById("map"), {
                center: new google.maps.LatLng(37.4419, -122.1419),
                zoom: 9,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
        var xml = parseXml(xmlStr);
        var markers = xml.getElementsByTagName("marker");
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            var polygonStr = markers[i].getAttribute("shape"),
                names = markers[i].getAttribute("name"),
                year = markers[i].getAttribute("t_2014"),
                years = markers[i].getAttribute("t_2015"),
                colors = markers[i].getAttribute("color");
            if (polygonStr.indexOf("POLYGON") != -1) {
                var paths = parsePolyStrings(polygonStr);
                for (var j = 0; j < paths[0].length; j++) {
                    bounds.extend(paths[0][j]);
                }
                var poly = new google.maps.Polygon({
                    paths: paths,
                    strokeColor: colors,
                    strokeOpacity: 0.5,
                    strokeWeight: 0.5,
                    fillColor: colors,
                    fillOpacity: 0.5
                });
                poly.setMap(map);

                poly.center = bounds.getCenter();
                var infowindow = new google.maps.InfoWindow();



                poly.addListener('click', (function (content) {
                    return function () {
                        console.log(content);
                        infowindow.setContent('<h3>'+names+'<h3><hr>'+
                                '<p><h4>Persentase Angka Partisipasi Sekolah<br>'+
                                'Anak Usia 13-15 Tahun</h4></p>'+
                                '<p><b>Tahun 2014 : '+year+'<br>'+
                                'Tahun 2015 : '+years+'</b></p><hr>'
                                );
                        infowindow.setPosition(this.center);
                        infowindow.open(map);
                    }
                })(year));
            }
        }

        map.fitBounds(bounds);
    }

    function parsePolyStrings(ps) {
        var i, j, lat, lng, tmp, tmpArr,
            arr = [],
            m = ps.match(/\([^\(\)]+\)/g);
        if (m !== null) {
            for (i = 0; i < m.length; i++) {
                tmp = m[i].match(/-?\d+\.?\d*/g);
                if (tmp !== null) {
                    for (j = 0, tmpArr = []; j < tmp.length; j += 2) {
                        lng = Number(tmp[j]);
                        lat = Number(tmp[j + 1]);
                        tmpArr.push(new google.maps.LatLng(lat, lng));
                    }
                    arr.push(tmpArr);
                }
            }
        }
        return arr;
    }

    function parseXml(str) {
        if (window.ActiveXObject) {
            var doc = new ActiveXObject('Microsoft.XMLDOM');
            doc.loadXML(str);
            return doc;
        } else if (window.DOMParser) {
            return (new DOMParser).parseFromString(str, 'text/xml');
        }
    }

    var xmlStr = '<markers>' +
        <?php
        if ($result = $mysqli->query($polygons)) {
        while ($row = $result->fetch_row()) {
        echo "'<marker city_id=\"" . $row[1] . "\" name=\"" . $row[2] . "\" t_2014=\"" . $row[3] . "\" t_2015=\"" . $row[4] . "\" shape=\"" . $row[5] . "\" color=\"" . $row[6] . "\">' + ";
        ?>
        <?php } $result->free_result(); } $mysqli->close(); ?>
        '</markers>';

    $city_id.on("select2:select select2:unselect", function (e) {
        $("#form-filter")[0].submit();
    });
</script>
</body>
</html>