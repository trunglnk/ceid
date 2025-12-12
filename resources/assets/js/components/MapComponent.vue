<style>
button.gm-ui-hover-effect {
  display: none !important;
}

.gmnoprint {
  display: none !important;
}

.google-map {
  background: gray;
  width: 100%;
  height: 1000px;
  margin-top: 5px;
}

.pac-controls {
  margin-top: 10px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

.pac-input {
  background-color: #fff;
  font-family: Roboto;
  font-size: 15px;
  font-weight: 300;
  margin-left: 12px;
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
  width: 60%;
}

#googleMap {
  width: 100%;
  height: 400px;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
.gm-style .gm-style-iw {
  text-align: center !important;
  width: 100% !important;
  max-height: 350px;
  left: 0px !important;
  top: 0px !important;
}
.gm-style .gm-style-iw div {
  text-align: left !important;
  width: 100% !important;
  max-width: unset !important;
}
.gm-style img {
  max-width: none;
  width: 100%;
}
img.irc_mi {
  opacity: 1;
}
.irc_dad {
  position: relative;
}
.irc_chi {
  background: #4285f4;
  bottom: 0;
  margin: 0px 0px 10px 0px !important;
  padding: 5px;
}
</style>
<template>
  <div>
    <input
      ref="input"
      class="pac-controls pac-input"
      type="text"
      placeholder="Search Box"
      v-show="show == true"
    />
    <div class="google-map" ref="map"></div>
  </div>
</template>

<script>
import store from "../store";

export default {
  name: "google-map",

  props: ["name", "value", "toado", "local", "cososanxuats", "css", "display"],

  data() {
    return {
      show: true,
      markerCoordinates: [
        {
          vi_do: 21.002029,
          kinh_do: 105.84665
        }
      ],
      data: []
    };
  },

  computed: {
    address: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit("input", val);
      }
    }
  },

  created() {
    if (this.hidenInput == 1) {
      this.show = false;
    } else if (this.hidenInput == 0) {
      this.show = true;
    }
  },

  methods: {
    addSearchBox() {
      var searchBox = new google.maps.places.SearchBox(this.$refs["input"]);

      this.map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(
        this.$refs["input"]
      );
      //need this because enter keydown on google place searchbox will trigger form submit event :((
      window.onkeydown = function(event) {
        if (event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      };

      // Bias the SearchBox results towards current map's viewport.
      this.map.addListener("bounds_changed", () => {
        searchBox.setBounds(this.map.getBounds());
      });

      // Listen for the event fired when the user selects a prediction and retrieve
      // more details for that place.
      searchBox.addListener("places_changed", () => {
        this.clearMap();

        var places = searchBox.getPlaces();

        if (places.length == 0) {
          return;
        }

        if (places.length == 1) {
          if (!places[0].geometry) {
            return;
          }

          this.getSelectedAddress(places[0]);

          var googlePlace = places[0];

          var LatLon = new google.maps.LatLng(
            googlePlace.geometry.location.lat(),
            googlePlace.geometry.location.lng()
          );

          var toado = {
            vido: LatLon.lat(),
            kinhdo: LatLon.lng()
          };
          this.$emit("toado", toado);
          this.$emit("tendiachi", $(".pac-input").val());
          var image = "../../../../images/defaults/marker_map_icon.png";

          var marker = new google.maps.Marker({
            map: this.map,
            position: LatLon,
            animation: google.maps.Animation.DROP,
            draggable: true,
            icon: image
          });
          var controller = this;
          marker.addListener("dragend", event => {
            this.address = {
              lat: event.latLng.lat(),
              lon: event.latLng.lng()
            };
            toado = { kinhdo: event.latLng.lat(), vido: event.latLng.lat() };
            controller.$emit("toado", toado);
          });

          this.markers.push(marker);

          this.mapPanTo(LatLon);
        } else {
          var bounds = new google.maps.LatLngBounds();

          places.forEach(place => {
            if (!place.geometry) {
              return;
            }

            var marker = new google.maps.Marker({
              map: this.map,
              title: place.name,
              position: place.geometry.location,
              draggable: true
            });

            marker.set("value", place);
            marker.set("type", "to-choose");

            this.markers.push(marker);

            marker.addListener("click", () => {
              var place = marker.get("value");

              this.getSelectedAddress(place);

              marker.set("type", "selected");

              this.markers.forEach(ele => {
                if (ele.get("type") == "to-choose") {
                  ele.setMap(null);
                }
              });
            });

            marker.addListener("dragend", event => {
              this.address = {
                lat: event.latLng.lat(),
                lon: event.latLng.lng()
              };
            });

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          this.map.fitBounds(bounds);
        }
      });
    },

    clearMap() {
      this.markers.forEach(function(marker) {
        marker.setMap(null);
        marker = null;
      });
      this.markers = [];
    },

    getSelectedAddress(googlePlace) {
      this.address = {
        lat: googlePlace.geometry.location.lat(),
        lon: googlePlace.geometry.location.lng()
      };
    },

    mapPanTo(LatLon) {
      this.map.setCenter(LatLon);
      if (this.map.getZoom() != 16) {
        this.map.setZoom(16);
      }
    }
  },

  watch: {
    toado: {
      handler: function(val, old) {
        if (val.kinhdo != old.kinhdo || val.vido != old.vido) {
          this.map = null;
          this.infoWindow = null;
          this.bounds = null;
          this.markers = [];

          this.bounds = new google.maps.LatLngBounds();

          const mapCentre = val;

          const options = {
            center: new google.maps.LatLng(mapCentre.vido, mapCentre.kinhdo),
            zoom: 10,
            minZoom: 1,
            maxZoom: 22,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
          };

          this.map = new google.maps.Map(this.$refs["map"], options);

          this.infoWindow = new google.maps.InfoWindow({
            maxWidth: 250
          });
          var image = "../../../../images/defaults/marker_map_icon.png";
          let lat = parseFloat(+val.vido);
          let lng = parseFloat(+val.kinhdo);
          var marker = new google.maps.Marker({
            position: {
              lat: lat,
              lng: lng
            },
            map: this.map,
            draggable: true,
            icon: image
          });

          var controller = this;
          marker.addListener("dragend", function() {
            var toado = {
              vido: this.getPosition().lat(),
              kinhdo: this.getPosition().lng()
            };
            controller.$emit("toado", toado);
          });
          this.addSearchBox();
        }
      }
    }
  },

  mounted() {
    if (this.css == 1) {
      //$('.google-map').attr('style', 'height:250px !important;')
    }
    this.map = null;
    this.infoWindow = null;
    this.bounds = null;
    this.markers = [];

    this.bounds = new google.maps.LatLngBounds();

    const mapCentre = this.local ? this.local : this.markerCoordinates[0];

    const options = {
      center: new google.maps.LatLng(mapCentre.vi_do, mapCentre.kinh_do),
      zoom: 16,
      minZoom: 1,
      maxZoom: 22,
      streetViewControl: false
    };

    this.map = new google.maps.Map(this.$refs["map"], options);

    this.infoWindow = new google.maps.InfoWindow({
      maxWidth: 250
    });
    this.data.forEach(element => {
      var marker = new google.maps.Marker({
        position: {
          lat: +element.kinh_do,
          lng: +element.vi_do
        },
        map: this.map,
        draggable: true
      });

      var windowDialog = new google.maps.InfoWindow({
        content:
          '<img height="120px" width="240px"  class="irc_mi" src="https://znews-photo-td.zadn.vn/w660/Uploaded/Sotntb/2016_12_05/512_Anh_1_Vsip.jpg" alt="Image result for khu cong nghiep" onload="typeof google===\'object\'&amp;&amp;google.aft&amp;&amp;google.aft(this)" width="469" height="337" style="margin-top: 0px;">' +
          '<h5 style="font-size:15px;color: #0073b7;"><i class="fa fa-industry"></i>\n&nbsp&nbsp' +
          element.ten +
          "</h5>" +
          '<p><i class="fa fa-bullseye" style="font-size:15px;color: #0073b7;"></i>\n&nbsp&nbsp' +
          element.mien.ten +
          "</p>" +
          '<p><i class="fa fa-university" style="font-size:15px;color: #0073b7;"></i>\n&nbsp' +
          element.tinh_thanh.ten +
          "</p>" +
          '<p><i class="fa fa-map-marker" style="margin-left:3px;font-size:15px;color: #0073b7;"></i>\n&nbsp&nbsp' +
          element.dia_chi_hoat_dong +
          "</p>" +
          "<br>",
        maxWidth: 240,
        height: 150
      });
      marker.addListener("mouseover", function() {
        windowDialog.open(this.map, this);
      });
      marker.addListener("mouseout", function() {
        windowDialog.close();
      });
      marker.addListener("click", () => {
        this.$emit("tencoso", element.ten);
        this.$emit("diachi", element.dia_chi_hoat_dong);
        this.$emit(
          "hinhanh",
          "https://znews-photo-td.zadn.vn/w660/Uploaded/Sotntb/2016_12_05/512_Anh_1_Vsip.jpg"
        );
      });
    });

    this.addSearchBox();
  }
};
</script>
