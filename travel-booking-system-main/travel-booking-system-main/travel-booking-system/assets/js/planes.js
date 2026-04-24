/**
 * TravelLux — Animated Planes
 * Uses inline SVG airplane shapes — transparent background, always works
 */
(function () {

  // FontAwesome plane path (fa-plane) — proper aircraft silhouette
  var PATH = 'M480 192H365.71L260.61 8.06A16.014 16.014 0 0 0 246.71 0h-65.5c-10.63 0-18.3 10.17-15.38 20.39L214.86 192H112l-43.2-57.6c-3.02-4.03-7.77-6.4-12.8-6.4H16.01C5.6 128-2.04 137.78.49 147.88L32 256 .49 364.12C-2.04 374.22 5.6 384 16.01 384H56c5.03 0 9.78-2.37 12.8-6.4L112 320h102.86l-49.03 171.6c-2.92 10.22 4.75 20.4 15.38 20.4h65.5c5.74 0 11.04-3.08 13.9-8.06L365.71 320H480c35.35 0 96-28.65 96-64s-60.65-64-96-64z';

  // Each plane: CSS class, fill color, glow color
  var planes = [
    { cls: 'p1', fill: '#c4b5fd', glow: 'rgba(167,139,250,.9)' },
    { cls: 'p2', fill: '#67e8f9', glow: 'rgba(6,182,212,.9)'   },
    { cls: 'p3', fill: '#fde68a', glow: 'rgba(251,191,36,.9)'  },
  ];

  function makeSVG(fill) {
    var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('viewBox', '0 0 576 512');
    svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    svg.classList.add('psvg');
    var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', PATH);
    path.setAttribute('fill', fill);
    svg.appendChild(path);
    return svg;
  }

  function init() {
    var old = document.getElementById('planesLayer');
    if (old) old.remove();

    var layer = document.createElement('div');
    layer.className = 'planes-layer';
    layer.id = 'planesLayer';

    planes.forEach(function (p) {
      var wrap = document.createElement('div');
      wrap.className = 'plane-wrap ' + p.cls;

      var trail = document.createElement('div');
      trail.className = 'trail';

      var svg = makeSVG(p.fill);

      // p2 flies right→left so trail goes after the plane
      if (p.cls === 'p2') {
        wrap.appendChild(trail);
        wrap.appendChild(svg);
      } else {
        wrap.appendChild(trail);
        wrap.appendChild(svg);
      }

      layer.appendChild(wrap);
    });

    document.body.insertBefore(layer, document.body.firstChild);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
