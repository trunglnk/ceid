<template>
  <div class="wrap-map">
    <div id="mapid"></div>
    <!-- <div v-if="!noLogin" class="" style="position: absolute !important;right: 20px !important;top: 20px !important;z-index:99999 !important">
            <a v-if="checklogin" class="button-action login" href="/cososanxuat" >Trang Chủ</a>
            <a v-else class="button-action login" href="/login" >Đăng Nhập</a>
    </div>-->
    <!-- Leaflet -->
  </div>
</template>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
.wrap-map {
  height: 100%;
}

#mapid {
  background-color: #f6f6f4;
  height: 100%;
}

.block {
  display: block;
}

#filter {
  width: 140px;
  height: 180px;
  background-color: red;
  position: fixed;
  top: 22px;
  z-index: 1000;
  right: 62px;
}

.leaflet-control-layers-overlays {
  text-align: left;
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

.login {
  padding: 5px 15px;
  color: #fff;
  border: 1px solid #0073b7;
  font-weight: 700;
  outline: none;
  background: #0073b7;
  cursor: pointer;
  border-radius: 25px;
}
</style>

<script>
import { endpoint, endpointhttp } from "../env.js";
import "leaflet/dist/leaflet.js";
import "mapbox-gl-leaflet/leaflet-mapbox-gl.js";
import "leaflet/dist/leaflet.css";
import "leaflet.markercluster";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";

export default {
  name: "Map",
  props: [
    "name",
    "value",
    "toado",
    "local",
    "cososanxuats",
    "css",
    "display",
    "checklogin",
    "hideText",
    "map_popup_id"
  ],
  data() {
    return {
      radio2: 3,
      data: [],
      marketArr: []
    };
  },
  watch: {
    cososanxuats: {
      handler: function (val) {
        this.marketArr = [];
        var controller = this;
        this.data = val;
        var markers = L.markerClusterGroup();
        var a = this.map;
        const mapCentre = this.local
          ? [this.local.vi_do, this.local.kinh_do]
          : [21.011397, 105.850594];
        this.map.setView(mapCentre, 8);
        this.map.eachLayer(function (layer) {
          a.removeLayer(layer);
        });
        this.getMap();
        var myIcon = L.icon({
          iconUrl: "../../../../images/defaults/marker_map_icon.png"
        });

        this.data.forEach(e => {
          var content =
            '<div  class="irc_dad"><img height="120px" width="301px"  class="irc_mi" src="https://znews-photo-td.zadn.vn/w660/Uploaded/Sotntb/2016_12_05/512_Anh_1_Vsip.jpg" alt="Image result for khu cong nghiep" onload="typeof google===\'object\'&amp;&amp;google.aft&amp;&amp;google.aft(this)" width="469" height="337" style="margin-top: 0px;">' +
            '<div class="irc_chi"><h5 style="font-size:15px;color: #ffffff;margin-left: 10px">\n&nbsp&nbsp' +
            e.ten +
            "</h5>" +
            '<p style="color: #ffffff;margin-left: 10px">\n&nbsp&nbsp' +
            e.khu_cong_nghiep.ten +
            "</p></div></div>" +
            '<p style="margin-left: 10px"><i class="fa fa-bullseye" style="font-size:15px;color: #0073b7;"></i>\n&nbsp&nbsp' +
            e.mien.ten +
            "</p>" +
            '<p style="margin-left: 10px"><i class="fa fa-bullseye" style="font-size:15px;color: #0073b7;"></i>\n&nbsp' +
            e.tinh_thanh.ten +
            "</p>" +
            '<p style="margin-left: 10px"><i class="fa fa-map-marker" style="margin-left:3px;font-size:15px;color: #0073b7;"></i>\n&nbsp&nbsp' +
            e.dia_chi_hoat_dong +
            "</p>" +
            "<br>";
          if (e.vi_do && e.kinh_do) {
            let item = L.marker([e.vi_do, e.kinh_do], {
              icon: myIcon,
              id: e.id,
              lat: e.vi_do,
              long: e.kinh_do
            })
              .bindPopup(content)
              .openPopup()
              .on("click", () => {
                // controller.$emit("macoso", e.id);
                // controller.$emit("tencoso", e.ten);
                // controller.$emit("khucongnghiep", e.khu_cong_nghiep.ten);
                // controller.$emit("diachi", e.dia_chi_hoat_dong);
                // controller.$emit("hinhanh", e.image_cover);
                this.map.setView([e.vi_do, e.kinh_do], 12);
              });
            item.getPopup().on("remove", () => {
              this.$emit("remove-popup", null);
            });
            markers.addLayer(item);
            this.marketArr.push(item);
          }
        });
        this.map.addLayer(markers);

        if (!this.hideText) {
          $(".leaflet-control-attribution a")
            .text("CEID - TRUNG TÂM THÔNG TIN & DỮ LIỆU MÔI TRƯỜNG")
            .css("font-size", "16px")
            .removeAttr("href")
            .css("cursor", "default")
            .css("text-decoration", "none");
          $(".leaflet-control-attribution a")
            .parent()
            .parent()
            .removeClass("leaflet-right")
            .addClass("leaflet-left");
          $(".leaflet-control-attribution a")
            .parent()
            .css("background", "none");
        }
      }
    },
    map_popup_id: {
      handler: function (val) {
        if (val) {
          console.log(val);
          this.markerFunction(val);
        }
      }
    }
  },
  mounted() {
    this.map = L.map("mapid", {
      maxZoom: 22,
      minZoom: 8
    }).setView([21.011397, 105.850594], 20);
    this.getMap();
  },
  methods: {
    getMap() {
      // L.mapboxGL({
      //   style: "/map/vietnam_style.json",
      //   accessToken: "no-token"
      // }).addTo(this.map);
      L.tileLayer("https://maps.vnpost.vn/api/tm/{z}/{x}/{y}.png?apikey=81c9873bc91e3b4f32c598dbb6ec6b83e528dc04a50d2a42", {
        maxZoom: 19,
      }).addTo(this.map);
      
      this.map.setZoom(8);
      if (!this.hideText) {
        $(".leaflet-control-attribution a")
          .text("CEID - TRUNG TÂM THÔNG TIN & DỮ LIỆU MÔI TRƯỜNG")
          .css("font-size", "16px")
          .removeAttr("href")
          .css("cursor", "default")
          .css("text-decoration", "none");
        $(".leaflet-control-attribution a")
          .parent()
          .parent()
          .removeClass("leaflet-right")
          .addClass("leaflet-left");
        $(".leaflet-control-attribution a")
          .parent()
          .css("background", "none");
      }

      $(".leaflet-control-attribution").css("display", "none");
    },
    markerFunction(id) {
      for (var i in this.marketArr) {
        var markerID = this.marketArr[i].options.id;
        if (markerID == id) {
          this.map.setView(
            [this.marketArr[i].options.lat, this.marketArr[i].options.long],
            12
          );
          this.marketArr[i].openPopup();
          break;
        }
      }
    }
  }
};
</script>
