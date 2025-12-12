import jquery from "jquery";
export default {
  showSuccessMessage(title = "Thông báo", message = [" "]) {
    var _message = this.changeMessenger(message);

    jquery.notify(
      {
        title: title,
        message: _message,
        icon: "fa fa-check"
      },
      {
        type: "success",
        animate: {
          enter: "animated bounceInRight",
          exit: "animated bounceOutRight"
        },
        placement: {
          from: "bottom",
          align: "right"
        },
        z_index: 99999,
        template:
          '<div style="width:25%" class="alert alert-div bg-olive alert-dismissible flat">\
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\
        <h4><i class="icon fa fa-check"></i>{1}</h4>\
        {2}</div>'
      }
    );
  },

  showWarningMessage(title = "Thông báo", message = [" "]) {
    var _message = this.changeMessenger(message);
    jquery.notify(
      {
        title: title,
        message: _message,
        icon: "fa fa-warning"
      },
      {
        type: "warning",
        animate: {
          enter: "animated bounceInRight",
          exit: "animated bounceOutRight"
        },
        placement: {
          from: "bottom",
          align: "right"
        },
        z_index: 99999,
        template:
          '<div style="width:25%" class="alert alert-div alert-warning alert-dismissible flat">\
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\
        <h4><i class="icon fa fa-warning"></i>{1}</h4>\
        {2}</div>'
      }
    );
  },

  showDangerMessage(title = "Thông báo", message = [" "]) {
    var _message = this.changeMessenger(message);
    jquery.notify(
      {
        title: title,
        message: _message,
        icon: "fa fa-danger"
      },
      {
        type: "danger",
        animate: {
          enter: "animated wobble",
          exit: "animated bounceOutRight"
        },
        placement: {
          from: "bottom",
          align: "right"
        },
        z_index: 99999,
        template:
          '<div style="width:25%" class="alert alert-div alert-danger alert-dismissible flat">\
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\
        <h4><i class="icon fa fa-danger"></i>{1}</h4>\
        {2}</div>'
      }
    );
  },

  showInfoMessage(title = "Thông báo", message = [" "]) {
    var _message = this.changeMessenger(message);
    jquery.notify(
      {
        title: title,
        message: _message,
        icon: "fa fa-info"
      },
      {
        type: "info",
        animate: {
          enter: "animated wobble",
          exit: "animated bounceOutRight"
        },
        placement: {
          from: "bottom",
          align: "right"
        },
        z_index: 99999,
        template:
          '<div style="width:25%" class="alert alert-div alert-info alert-dismissible flat">\
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\
        <h4><i class="icon fa fa-info"></i>{1}</h4>\
        {2}</div>'
      }
    );
  },

  changeMessenger(message) {
    var _message = "";
    if (message.length == 1) _message = "<p>" + message[0] + "</p>";
    else {
      _message = "<ul>";
      message.forEach(e => {
        _message += "<li>" + e + "</li>";
      });
      _message += "</ul>";
    }
    return _message;
  }
};
