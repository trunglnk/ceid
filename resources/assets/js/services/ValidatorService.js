export default {
  setAll(obj, val) {
    Object.keys(obj).forEach(function(index) {
      obj[index] = val;
    });
  },
  setNull(obj) {
    this.setAll(obj, null);
  }
};
