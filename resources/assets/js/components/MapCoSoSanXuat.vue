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

/* .block {
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
} */
</style>

<script>
// import { endpoint, endpointhttp } from "../env.js";
// import "leaflet/dist/leaflet.js";
// import "mapbox-gl-leaflet/leaflet-mapbox-gl.js";
// import "leaflet/dist/leaflet.css";
// import "leaflet.markercluster";
// import "leaflet.markercluster/dist/MarkerCluster.css";
// import "leaflet.markercluster/dist/MarkerCluster.Default.css";

export default {
  // name: "Map",
  props: [
    // "name",
    // "css",
    // "display",
    // "checklogin",
    // "hideText",
    // "map_popup_id", 
    "toaDo"
  ],
  data() {
    return {
      marker:null,
    };
  },
  mounted() {
    this.map = L.map("mapid", {
      maxZoom: 22,
      minZoom: 8
    }).setView([this.toaDo.vi_do, this.toaDo.kinh_do],20);
    console.log(2)
    if(this.marker===null){
      var icon = new L.Icon.Default();
      icon.options.shadowSize = [0,0];
      this.marker=L.marker([this.toaDo.vi_do, this.toaDo.kinh_do],{icon : icon}).addTo(this.map);
    }
    this.getMap();
  },
  methods: {
    getMap() {
      L.tileLayer("https://maps.vnpost.vn/api/tm/{z}/{x}/{y}.png?apikey=81c9873bc91e3b4f32c598dbb6ec6b83e528dc04a50d2a42", {
        maxZoom: 22,
      }).addTo(this.map);
      
      this.map.setZoom(8);
      // if (!this.hideText) {
      //   $(".leaflet-control-attribution a")
      //     .text("CEID - TRUNG TÂM THÔNG TIN & DỮ LIỆU MÔI TRƯỜNG")
      //     .css("font-size", "16px")
      //     .removeAttr("href")
      //     .css("cursor", "default")
      //     .css("text-decoration", "none");
      //   $(".leaflet-control-attribution a")
      //     .parent()
      //     .parent()
      //     .removeClass("leaflet-right")
      //     .addClass("leaflet-left");
      //   $(".leaflet-control-attribution a")
      //     .parent()
      //     .css("background", "none");
      // }

      // $(".leaflet-marker-shadow leaflet-zoom-animated").css("display", "none");
    },
  }
};
</script>
