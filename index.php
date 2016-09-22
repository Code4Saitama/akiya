<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" lang="en"></meta>
  <title>AKIYA Map</title>
  <script src="http://openlayers.org/api/2.11/OpenLayers.js"></script>
  <script src="http://openstreetmap.org/openlayers/OpenStreetMap.js"></script>
  <script src="http://overpass-api.de/overpass.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
  <script type="text/javascript">
	//大宮,35.906295,139.623999
      	var _initlat = 35.906295;
      	var _initlng = 139.623999;
      	var _initzoom = 16;
      	var map;	
      function init(){
          var hash = location.hash;
          if (hash){
            var hashes = hash.split("=")[1].split("/");
            var zoom = ~~hashes[0];
            var lat  = hashes[1] * 1;
            var lng  = hashes[2] * 1;
          }else{
            var lat = $.cookie("cur_lat") ? $.cookie("cur_lat") * 1 : _initlat;
            var lng = $.cookie("cur_lng") ? $.cookie("cur_lng") * 1 : _initlng;
            var zoom = $.cookie("cur_zoom") ? ~~$.cookie("cur_zoom") : _initzoom;
          } 
          map = new OpenLayers.Map ("map", {
          controls:[
              new OpenLayers.Control.Navigation(),
              new OpenLayers.Control.PanZoomBar(),
              new OpenLayers.Control.LayerSwitcher(),
              new OpenLayers.Control.Attribution()],
              maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34),
              maxResolution: 156543.0399,
              numZoomLevels: 19,
              units: 'm',
              projection: new OpenLayers.Projection("EPSG:900913"),
              displayProjection: new OpenLayers.Projection("EPSG:4326")
          } );

          layerMapnik = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
          map.addLayer(layerMapnik);
        
          var editLayer = new OpenLayers.Layer.Vector("AkiyaEdit");
          var akiyaLayer = new OpenLayers.Layer.Vector("Akiya");
          map.addControl(new OpenLayers.Control.EditingToolbar(editLayer));
          map.addLayers([editLayer, akiyaLayer]);

          var lonLat = new OpenLayers.LonLat(lng, lat)
              .transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));

          map.setCenter (lonLat, zoom);

          map.events.register("moveend", map, function(){
            var latlon = this.getCenter().transform(
                new OpenLayers.Projection("EPSG:3857"),
                new OpenLayers.Projection("EPSG:4326")
            );
            var clat = latlon.lat;
            var clng = latlon.lon;
            var cZoom = this.getZoom();
            $.cookie('cur_lat', clat, { expires: 30, path: '/' });
            $.cookie('cur_lng', clng, { expires: 30, path: '/' });
            $.cookie('cur_zoom', cZoom, { expires: 30, path: '/' });
            location.hash="#map=" + cZoom + "/" + clat + "/" + clng;
        });
      }
  </script>
</head>
<body onload="init()">
  <div id="map" class="smallmap"></div>
</body>
</html>
