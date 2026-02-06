function show_Notify(type, label_type, Menssege) {
  // Use Alertify for notifications (site already uses alertify elsewhere).
  // If alertify.notify is available, use it with a type and duration.
  // Fallback to alertify.success/error/message where appropriate, or console.log.
  try {
    if (typeof alertify !== "undefined" && typeof alertify.notify === "function") {
      var aType = type === "danger" ? "error" : type;
      // Compose a concise message (avoid HTML to be safe with alertify config)
      var text = label_type ? label_type + ": " + Menssege : Menssege;
      alertify.notify(text, aType, 5);
      return;
    }

    if (typeof alertify !== "undefined") {
      var text2 = label_type ? label_type + ": " + Menssege : Menssege;
      if (type === "success" && typeof alertify.success === "function") {
        alertify.success(text2);
      } else if ((type === "warning" || type === "danger") && typeof alertify.error === "function") {
        // map warning/danger to error if no dedicated warning method
        alertify.error(text2);
      } else if (typeof alertify.message === "function") {
        alertify.message(text2);
      } else {
        console.log(text2);
      }
      return;
    }
  } catch (e) {
    // If alertify usage throws for any reason, fallback to console
    console.log(label_type + ": " + Menssege);
  }
}