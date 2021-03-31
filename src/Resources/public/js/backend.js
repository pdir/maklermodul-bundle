
window.addEvent("domready", function() {
  var pdirMaklerInfo = document.getElementById('pdirMaklerInfo');

  if(pdirMaklerInfo) {
    // move makler info to bottom
    var fragment = document.createDocumentFragment();
    fragment.appendChild(document.getElementById('pdirMaklerInfo'));
    var cont = document.getElementById('tl_listing') !== null ? document.getElementById('tl_listing') : document.getElementsByClassName('tl_empty')[0];

    if (typeof cont !== 'null') {
      cont.appendChild(fragment);
    }
  }

});
