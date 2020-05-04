(function() {
  var w = window;
  var d = document;
  function l() {
    var s = d.createElement("script");
    s.type = "text/javascript";
    s.async = true;
    s.src = "https://app.usemagnify.com/widget/a1cbb66d-c112-4448-a9c4-ad6455b3dbdb";
    var x = d.getElementsByTagName("script")[0];
    x.parentNode.insertBefore(s, x);
  }
  if (w.attachEvent) {
    w.attachEvent("onload", l);
  } else {
    w.addEventListener("load", l, false);
  }
})();